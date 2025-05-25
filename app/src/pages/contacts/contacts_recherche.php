<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include '../../../inc/function.php';
$conn = new connBase();
session_start();
$secteur = $_SESSION['idcompte'];
$term = isset($_POST['term']) ? $_POST['term'] : null;

if (!empty($term) || isset($term)) {
  $transterm = $term;
  $term = "
  AND (
    nom LIKE '%$term%'
    OR prenom LIKE '%$term%'
    OR adresse LIKE '%$term%'
    OR portable LIKE '%$term%'
    OR ville LIKE '%$term%'
    OR cp LIKE '%$term%'
    OR telephone LIKE '%$term%'
    OR email LIKE '%$term%'
  ) ";
} else {
  $transterm = "";
  $term = "";
}
$annref  = date('Y');
$reqcli = "select * from client_chantier where idcompte='$secteur' and nom not like '%---' and nom not like '%***'  $term order by nom asc ";
$cli = $conn->allRow($reqcli);


?>
<p class="titre_menu_item">Recherche contacts</p>
<ul class="nav  mb-4"><!-- ajaxData('limit=1&term='+this.value+'', '../src/pages/contacts/contacts_recherche.php', 'action', 'attente_target'); -->
  <!-- <li class=" nav-item mr-1">
    
    <input type="text" class="form-control-n mt-2" value="" onkeyup="delaiExecution();" onmouseover="eff_form(this);">
    <span><?= $term ?></span>
  </li> -->
  <!-- <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('idcli=<?= $idcli ?>', '../src/pages/contacts/contacts_devis.php', 'action', 'attente_target');">Devis</a>
  </li>
  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('idcli=<?= $idcli ?>&annee=<?= date('Y') ?>&paye=', '../src/pages/contacts/contacts_production.php', 'action', 'attente_target');">Production</a>
  </li>
  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('idcli=<?= $idcli ?>&annee=<?= date('Y') ?>&paye=', '../src/pages/contacts/contacts_factures.php', 'action', 'attente_target');">Factures</a>
  </li>
  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('idcli=<?= $idcli ?>&annee=<?= date('Y') ?>&paye=', '../src/pages/contacts/contacts_reglements.php', 'action', 'attente_target');">Règlements</a>
  </li>
  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1 mt-2 ml-2 text-primary" onclick="ajaxData('idcli=<?= $idcli ?>', '../src/pages/contacts/contacts_partage.php', 'action', 'attente_target');"><i class='bx bx-share-alt icon-bar'></i></a>
  </li>
  <li class=" nav-item">
    <a href="#" class="btn btn-mag-n mr-1 mt-2 text-primary" onclick="ajaxData('idcli=<?= $idcli ?>', '../src/pages/contacts/contacts_modifier.php', 'action', 'attente_target');"><i class='bx bx-edit icon-bar'></i></a>
  </li> -->
</ul>
<div id="action" class="scroll">
  <?php

  if (!empty($cli)) {
  ?>

    <table class="table table-hover mt-2">
      <?php


      foreach ($cli as $c) {



        $idcli = $c['idcli'];
        $reqadr = "select * from client_adresse  where idcompte='$secteur' and idcli='$idcli'";
        $adr = $conn->oneRow($reqadr);
        $reqinfos = "select * from client_infos where idcompte='$secteur' and idcli='$idcli'";
        $infos = $conn->oneRow($reqinfos);

        $actif = isset($infos['actif']) ? $infos['actif'] : null;
        if (!empty($actif) || isset($actif)) {
          $af = $adr['adressfact'];
          $afse = str_replace(' ', '', $af);
          $afsem = strtolower($afse);
        } else {
          $afsem = "";
        }


        $ac = $c['adresse'];
        $acse = str_replace(' ', '', $ac);
        $acsem = strtolower($acse);

        if (strcmp($afsem, $acsem) !== 0) {
          $adressecorres = 1;
          $color = "danger";
        } else {
          $color = "primary";
          $adressecorres = 0;
        }
        $email = isset($c['email'])  ? ($c['email']) : null;
        if (!empty($email) || isset($email)) {
          $puce = "puce-mag";
          $affemail = 1;
        } else {
          $email = "";
          $puce = "";
          $affemail = 0;
        }

        $teltiret = afficherTiret(Tel($c['telephone']), Tel($c['portable']));
        $transterm = $term;
      ?>
        <tr>
          <!-- <td><i title="Accès dossier" class='bx bxs-file text-primary icon-bar bx-sm pointer' onclick="ajaxData('idcli=<?= $idcli ?>', '../src/pages/contacts/contacts_fiche.php', 'target-one', 'attente_target');"></i></td> -->
          <td class=""><span onclick="ajaxData('idcli=<?= $idcli ?>', '../src/pages/contacts/contacts_fiche.php', 'target-one', 'attente_target');" class="pointer" title="Accès au dossier N°<?= $c['idcli'] . ' ' . $c['nom'] ?>"><i class='bx bx-link-external'></i></span></td>
          <td class="l-9"><span onclick="ajaxData('idcli=<?= $idcli ?>', '../src/pages/contacts/contacts_fiche.php', 'target-one', 'attente_target');" class="puce">N°<?= $c['idcli'] ?></span></td>
          <td class="l-25"><?= '<span class="text-bold">' . $c['civilite'] . ' ' . $c['nom'] . ' ' . $c['prenom'] . '</span></br>' . ($teltiret) ?> </td>
          <td class="l-20"><?= $c['adresse'] . '</br>' . $c['cp'] . ' ' . $c['ville'] ?></td>
          <td class="l-20 text-<?= $color ?>">
            <?php
            if ($adressecorres === 1) {
            ?>
              <?= $adr['adressfact'] . '</br>' . $adr['cpfact'] . ' ' . $adr['villefact'] ?>
            <?php
            }
            ?>
          </td>
          <td class="l-20">
            <span onclick="ajaxData('term=<?= $transterm ?>&modinfos=actif-<?= $infos['actif'] ?>', '../src/pages/contacts/contacts_recherche.php', 'action', 'attente_target');" class="puce-mag bg-primary text-white pointer"><?= $infos['actif'] ?></span>
            <span onclick="ajaxData('term=<?= $transterm ?>&modinfos=categorie-<?= $infos['categorie'] ?>', '../src/pages/contacts/contacts_recherche.php', 'action', 'attente_target');" class="puce-mag text-bold pointer"><?= $infos['categorie'] ?></span>
            <span onclick="ajaxData('term=<?= $transterm ?>&modinfos=type-<?= $infos['type'] ?>', '../src/pages/contacts/contacts_recherche.php', 'action', 'attente_target');" class="puce-mag text-bold pointer"><?= $infos['type'] ?></span>
          </td>
          <td class="text-right text-muted text-bold l-49">
            <?php
            if ($affemail === 1) {
            ?>
              <span title="Envoyer un email à <?= $email ?>" onclick="ajaxData('emailcli=<?= $email ?>', '../src/pages/contacts/contacts_email.php', 'action', 'attente_target');" class="mail pointer <?= $puce ?>"> <?= $email ?></span>
            <?php
            }
            ?>
          </td>
        </tr>


      <?php
      }


      ?>
    </table>

  <?php
  } else {
  ?>

    <p class="text-bold text-danger">Aucun enregistrement ne correspond à "<?= $transterm ?>"</p>
  <?php
  }
  ?>
</div>
<?php
include $chemin . '/inc/foot.php';
?>
<script>
  $(function() {
    $('.pointer').tooltip();
  });
</script>
<script src="../assets/js/script.js?=<?= time(); ?>"></script>