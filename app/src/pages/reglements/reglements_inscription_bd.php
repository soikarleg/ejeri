<?php
error_reporting(\E_ALL);
ini_set('display_errors', 'stdout');
session_start();
$secteur = $_SESSION['idcompte'];
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$conn = new connBase();
foreach ($_POST as $k => $v) {
  ${$k} = (trim($v));
  //echo '$' . $k . ' = ' . trim($v) . '</br>';
}
if ($acompte === 'acompte') {
  $titre_colonne = 'N° de devis concerné';
  $note_acompte = '1';
} else {
  $titre_colonne = 'N° de facture payée';
  $note_acompte = '0';
}
$reqreg = "select * from reglements where bordereau = '$bordereau'";
$existe = $conn->oneRow($reqreg);
//var_dump($existe);
$message = "";
if ($existe != false) {
  $message = "Cette référence de bordereau existe déjà. Modifiez-la, merci.";
} else {
  $erreur = 0;
  if ($bank === "" || $bordereau === "" || $value === "" || $modereg === "") {
    $message = "Un ou plusieurs renseignements manquent pour valider l'enregistrement.";
  } else {
    // numero facture
    $enregistrement = explode('/', $value);
    $nombre_paiement = count($enregistrement);
    $idrib = explode('/', $bank);
    $bank = $idrib[0];
    $idrib = $idrib[1];
?>
    <p class="titre_menu_item mb-2">Pointage effectué</p>
    <table style=" width: 100%">
      <tr>
        <td>Référence du bordereau</td>
        <td class="text-bold text-right"><?= $bordereau ?></td>
      </tr>
      <tr>
        <td>Banque créditée</td>
        <td class="text-bold text-right"><?= $bank ?></td>
      </tr>
    </table>
    <hr>
    <table style=" width: 100%">
      <tr>
        <td class="text-bold">N° - Client</td>
        <td class="text-bold"><?= $titre_colonne ?></td>
        <?php
        if ($acompte != 'acompte') {
        ?>
          <td class="text-bold text-center">Complet</td>
        <?php
        } else {
        ?>
          <td class="text-bold text-center">-</td>
        <?php
        }
        ?>
        <td class="text-bold text-right">Montant</td>
      </tr>
      <?php
      foreach ($enregistrement as $en) {
        $dat = explode('/', $date);
        $jour = $dat[0];
        $mois = $dat[1];
        $annee = $dat[2];
        $ventilation = explode('_', $en);
        $numero_facture = $ventilation[1];
        $montant_reglement = Dec_2($ventilation[0]);
        if ($acompte === 'acompte') {
          $client_facture = "select * from devisestimatif where numero = '$numero_facture'";
          $client = $conn->oneRow($client_facture);
          $idcli = $client['id'];
          $nomcli = $client['nom'];
          $montant = Dec_2($client['totttc']);
        } else {
          $client_facture = "select * from facturesentete where numero = '$numero_facture'";
          $client = $conn->oneRow($client_facture);
          $idcli = $client['id'];
          $nomcli = $client['nom'];
          $montant = Dec_2($client['totttc']);
        }
        // solde
        $reqsolde = "select SUM(montant) as mo from reglements where factref ='$numero_facture' group by id";
        $regle = $conn->oneRow($reqsolde);
        //var_dump($regle);
        //echo $regle['mo'];
        if ($regle) {
          $montant_deja = Dec_2($regle['mo']);
        } else {
          $montant_deja = 0.00;
        }
        $solde = Dec_2($montant - $montant_deja);
        if ($montant_reglement === $solde) {
          $partiel = 'egal';
          $par = 'non';
          $affpar = "<i class='bx bx-message-alt-check bx-sm text-primary'></i>";
        } else {
          $partiel = 'diff';
          $par = 'oui';
          $affpar = "<i class='bx bx-message-alt-error bx-sm text-danger'></i>";
        }
        $nomc = addslashes($nomcli);
        $insreg = "INSERT INTO reglements(id, client, jour, mois, annee, mode, factref, montant, partiel,acompte, commercial, cs, commentaire, bordereau,bank) VALUES ('$idcli','$nomc','$jour','$mois','$annee','$modereg','$numero_facture','$montant_reglement','$par','$note_acompte','$iduser','$secteur','Inscription $modereg $date','$bordereau','$idrib')";
        $conn->handleRow($insreg);
        $conn->insertLog('Pointage règlement', $iduser, $bordereau . ' ' . $numero_facture . ' ' . $montant_reglement);
        // Update facture marquée comme payée si non partielle
        if ($partiel == 'egal') { //echo 'inscription "oui", payée sur facture';
          $factpaye = "UPDATE facturesentete SET paye='oui' WHERE numero = '$numero_facture'";
          $conn->handleRow($factpaye);
        }
      ?>
        <tr>
          <td><span class="puce mr-2"><?= $idcli ?></span><?= $nomcli ?></td>
          <td><?= $numero_facture ?></td>
          <?php
          if ($acompte != 'acompte') {
          ?>
            <td class="text-center"><?= $affpar ?></td>
          <?php
          } else {
          ?>
            <td class="text-bold text-center">-</td>
          <?php
          }
          ?>
          <td class="text-right"><?= $montant_reglement ?></td>
        </tr>
        <script type='text/javascript'>
          ajaxData('cs=maj', '../src/pages/reglements/reglements_montotal.php', 'montotal', 'attente_target');
        </script>
      <?php
      }
      ?>
      <tfoot>
        <tr class="mt-2">
          <td>Total</td>
          <td></td>
          <td></td>
          <td class="text-right"><?= $total ?></td>
        </tr>
        <tr>
          <td>Nombre règlement</td>
          <td></td>
          <td></td>
          <td class="text-right"><?= $nombre_paiement ?></td>
        </tr>
      </tfoot>
    </table>
    <a href="../src/pages/reglements/reglements_bordereau_02_pdf.php?numbx=<?= $bordereau ?>" class="btn btn-mag-n pull-right mt-4" target="_blank">Impression du bordereau</a>
    <button class="btn btn-warning text-dark mt-4" onclick="ajaxData('annref=<?= date('Y') ?>','../src/pages/reglements/reglements_pointer.php','action','attente_target');"><i class='bx bx-refresh icon-bar'></i> Nouveau pointage</button>
<?php
  }
}
//echo '</br>Total de la remise '.$total;
?>
<div class="text-danger mt-2">
  <?= $message ?>
</div>