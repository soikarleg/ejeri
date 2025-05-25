<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
//$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
//session_start();
$secteur = $_SESSION['idcompte'];
$idusers = $_SESSION['idusers'];
$conn = new connBase();
$infos = $conn->askIdcompte($secteur);
$compte = new Compte($idusers);
$date_debut = $compte->getDateDebut();
//var_dump($date_debut);
//$facturation_totale = $compte->getFacturation($secteur, '09', '2023');
$ok = $compte->verifPaye();
$date_fin_essai = $compte->getFinEssai();
//var_dump($date_debut);
?>
<p class="puce pull-right">N° <?= $secteur ?></p>
<p class="text-bold mb-2">Facturation du compte <span class="small"><?= $date_debut['date'] ?></span></p>
<?php
// $reqentreprise = "select * from idcompte_infos where idcompte = '$secteur'";
// $infos = $conn->oneRow($reqentreprise);
?>
<div class="scroll">
  <div class="row">
    <div class="col-md-12">
      <?php
      if ($date_debut) {
        $fact = $facturation_totale[0]['t'];
        $du = 0.0015;
      ?>
        <div class="card card-body p-4">
          <p>Période essai à partir du <?= $date_debut['date'] ?></p>
          <p class="mb-2">Facturation à partir du <?= $date_fin_essai ?></p>
          <p><?php
              $mois_debut = $compte->getMoisDebutFacture(); // Septembre (09)
              $annee_debut = 2023;
              $annee_fin = 2024;
              $mois_fin = 8; // Août (08)
              ?></p>
          <?php
          $mois = $mois_debut;
          $annee = $annee_debut;

          while (!($mois == $mois_fin + 1 && $annee == $annee_fin)) {
            $m = str_pad($mois, 2, '0', STR_PAD_LEFT); // Ajoute un zéro si nécessaire pour obtenir deux chiffres
            $mois_lettre = moisLettre($mois); // Appelle la fonction pour obtenir le mois en lettres

            $facturation_mensuelle = $compte->getFacturation($secteur, $m, $annee);
            // var_dump($facturation_mensuelle);
          ?>
            <div class="border-dot p-3 mb-2">
              <?php
              $gratuit = $mois_debut >= $mois - 1 && $annee == $annee_debut ? "<span class='pull-right puce  text-bold'>Période offerte</span>" : "";
              $ht = calculerHT($facturation_mensuelle[0]['t'], $infos['t7'])

              ?>
              <p class="text-bold"><?= $mois_lettre . ' ' . $annee . ' ' . $gratuit ?></p>
              <p>Votre facturation du mois : <?= Dec_0($facturation_mensuelle[0]['t'], ' TTC') ?>, soit <?= Dec_0($ht, ' HT') ?></p>
              <p> <?= Dec_2($ht * $du, ' euros HT') ?> </p>

            </div>
          <?php

            // Passe au mois suivant
            $mois++;
            if ($mois > 12) {
              $mois = 1; // Reviens à janvier
              $annee++;  // Incrémente l'année
            }
          }
          ?>
        </div>

      <?php
        //$log = $conn->insertLog('Facturation compte',$idusers,'Consultation compte de facturation');
      }
      $date_str = '13/10/2023';
      $date = DateTime::createFromFormat('d/m/Y', $date_str);
      $timestamp = $date->getTimestamp();
      //   echo $timestamp;
      ?>
    </div>
  </div>
</div>
<script>
  $(function() {
    $('.bx').tooltip();
  });
</script>