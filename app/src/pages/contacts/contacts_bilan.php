<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$magesquo = new Magesquo($secteur);
$client = new Clients($secteur);
$purge = new DataValidator();
$conn = new connBase();
$annee = "";
$paye = "";
if ($annee) {
  $annaff = $annee;
  $annee = "and annee = '$annee'";
} else {
  $annee = '';
  $annaff = 'global';
}
switch ($paye) {
  case 'oui':
    $paye = "and paye='oui'";
    break;
  case 'non':
    $paye = "and paye='non'";
    break;
  default:
    $paye = "";
    break;
}
$reqdevis = "select * from facturesentete where id='$idcli' $paye $annee and cs='$secteur' order by annee desc, mois desc, jour asc ";
$devis = $conn->allRow($reqdevis);
?>
<div class="scroll">

  <div class="row">
    <?php for ($anneeref = date('Y'); $anneeref >= date('Y'); $anneeref--) {
      $params = ['idcli' => $idcli, 'annref' => $anneeref];
      $reqinter = "select SUM(quant) as inter from production where idcli= :idcli and annee= :annref group by annee";
      $inter = $magesquo->oneRow($reqinter, $params);
      $reqfact = "select SUM(totttc) as fact from facturesentete where id= :idcli and annee= :annref group by annee";
      $fact = $magesquo->oneRow($reqfact, $params);
      $reqreg = "select SUM(montant) as regl from reglements where id= :idcli and annee= :annref group by annee";
      $regl = $magesquo->oneRow($reqreg, $params);
      // $inter['inter'] = 25;
      // $fact['fact'] = 25;
      // $regl['regl'] = 30;
    ?>
     
      <div class="col-md-3 mb-4">
        <div class="border-fiche">
          <p class="titre_menu_item mb-4">Activité <?= $anneeref ?></p>
          <?php
          if (is_array($inter) && isset($inter['inter']) && $inter['inter'] != "0") {
          ?>
            <p class=" mb-2">Heures effectué<span class="pull-right"><?= $h = Dec_2($inter['inter']) ?> h</span></p>
            <p class=" mb-2">Facturations établies<span class="pull-right"><?= $f = Dec_2($fact['fact']) ?> €</span></p>
            <p class=" mb-4">Règlements pointés<span class="pull-right"><?= $r = Dec_2($regl['regl']) ?> €</span></p>
            <p class=" mb-0">Ecart<span class="pull-right"><?= $e = Dec_2($f - $r) ?> €</span></p>
          <?php
          } else {
          ?>
            <p class="text-danger">Aucune activité en <?= $idcli ?></p>
          <?php
          }
          ?>
        </div>
      </div>
 <!-- <div class="col-md-3">
        <div class="border-fiche">
          Ici
        </div>
      </div> -->

    <?php
    }
    ?>
    <div class="col-md-9">
      <div class="border-fiche">
        <?php
        //echo $_POST['adress'];
        include 'contacts_carte.php';
        ?>
      </div>
    </div>
  </div>
</div>
<script>
  $(function() {
    $('.title').tooltip();
  });
</script>