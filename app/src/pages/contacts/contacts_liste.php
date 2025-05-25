<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$secteur = $_SESSION['idcompte'];
$chemin = $_SERVER['DOCUMENT_ROOT'];
//include $chemin . '/inc/error.php';
$magesquo = new Magesquo($secteur);
$client = new Clients($secteur);
$term = isset($_POST['term']) ? $_POST['term'] : null;
if (!empty($term) || isset($term)) {
  $transterm = $term;
  $term = "AND (
    c.nom LIKE :term
    OR c.idcli LIKE :term
    OR c.prenom LIKE :term
    OR c.portable LIKE :term
    OR c.telephone LIKE :term
    OR c.email LIKE :term
    OR cc.adresse LIKE :term
    OR cc.ville LIKE :term
    OR cc.cp LIKE :term
    OR cw.adresse LIKE :term
    OR cw.ville LIKE :term
    OR cw.cp LIKE :term
    OR ci.type like :term
    OR ci.categorie like :term
    OR ci.connu like :term
    
  )";
  $params = [':idcompte' => $secteur, ':term' => '%' . $transterm . '%'];
} else {
  $transterm = "";
  $term = "";
  $params = [':idcompte' => $secteur];
}

$annref  = date('Y');
$reqcli = "select * from client c  join client_infos ci on c.idcli = ci.idcli join client_correspondance cc on c.idcli = cc.idcli join client_chantier cw ON c.idcli = cw.idcli where c.idcompte=:idcompte $term order by c.nom asc ";
$cli = $magesquo->allRow($reqcli, $params);
?>
<div class="row">
  <div class="col-md-3">
    <!-- <p class="titre_menu_item">Recherche contacts</p> -->
    <form action="" method="post">
      <div class="input-group mb-3" style="width: 100%;">

        <input type="text" class="form-control" name="term" placeholder="Nom, ville, numero..." aria-label="Rechercher un client" aria-describedby="button-addon2">
        <button class="btn btn-outline-secondary" type="submit">Rechercher</button>
        <!-- <input type="submit" value="Rechercher" class="btn btn-mag-n"> -->

      </div>
    </form>
  </div>
  <div class="col-md-9">

  </div>
  <div class="scroll">

    <div class="row">
      <?php
      if (!empty($cli)) {
        //prettyc($cli);

        foreach ($cli as $c) {
          $teltiret = afficherTiret(Tel($c['telephone']), Tel($c['portable']));
      ?>
          <div class="col-md-3">
            <div onclick="window.open('/fiche_client?idcli=<?= $c['idcli'] ?>','_self')" class="pointer border-fiche mb-2">

              <span class="pull-right">n° <?= $c['idcli'] . ' ' . $c['categorie'] ?></span>
              <p class="text-primary"><?= $client->showNomClient($c['idcli']) ?></p>
              <p><?= $c['adresse'] ?></p>
              <p><?= $c['cp'] . ' ' . $c['ville'] ?></p>
              <span class="pull-right"><?= $c['email'] ?></span>
              <p><?= $teltiret ?></p>
            </div>
          </div>
        <?php
        }
      } else {
        ?>
        <div class="col-md-12">
          <p class="border-fiche text-bold text-danger">Aucun enregistrement ne correspond à "<?= $transterm ?>"</p>
        </div>
      <?php
      }
      ?>
    </div>
  </div>
</div>

<!-- <div id="action" class="scroll">
  <?php
  if (!empty($cli)) {
    //prettyc($cli);
  ?>
    <table class="border-fiche table100 table-hover mt-2">
      <?php
      foreach ($cli as $c) {
        $idcli = $c['idcli'];
        $reqadr = "select * from client_correspondance  where idcompte='$secteur' and idcli='$idcli'";
        $adr = $magesquo->oneRow($reqadr);
        //prettyc($adr);
        $reqinfos = "select * from client_infos where idcompte='$secteur' and idcli='$idcli'";
        $infos = $magesquo->oneRow($reqinfos);
        //prettyc($infos);
        $actif = isset($infos['actif']) ? $infos['actif'] : null;
        if (!empty($actif) || isset($actif)) {
          $af = $adr['adresse'];
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
          <td><i title="Accès dossier" class='bx bxs-file text-primary icon-bar bx-sm pointer' onclick="ajaxData('idcli=<?= $idcli ?>', '../src/pages/contacts/contacts_fiche.php', 'target-one', 'attente_target');"></i></td> 
          <td class="border-fiche"><a href="/fiche_client?idcli=<?= $c['idcli'] ?>" class="pointer" title="Accès au dossier N°<?= $c['idcli'] . ' ' . $c['nom'] ?>"><i class='bx bx-link-external'></i></a></td>
          <td class="l-9"><span onclick="ajaxData('idcli=<?= $idcli ?>', '../src/pages/contacts/contacts_fiche.php', 'target-one', 'attente_target');" class="puce">N°<?= $c['idcli'] ?></span></td>
          <td class="l-25"><?= '<span class="text-bold">' . $c['civilite'] . ' ' . $c['nom'] . ' ' . $c['prenom'] . '</span></br>' . ($teltiret) ?> </td>
          <td class="l-20"><?= $c['adresse'] . '</br>' . $c['cp'] . ' ' . $c['ville'] ?></td>
          <td class="l-20 text-<?= $color ?>">
            <?php
            if ($adressecorres === 1) {
            ?>
              <?= $adr['adresse'] . '</br>' . $adr['cp'] . ' ' . $adr['ville'] ?>
            <?php
            }
            ?>
          </td>
          <td class="l-20">
            <span onclick="ajaxData('term=<?= $transterm ?>&modinfos=actif-<?= $infos['actif'] ?>', '../src/pages/contacts/contacts_recherche.php', 'action', 'attente_target');" class="puce-mag bg-primary text-white pointer"><?= $infos['actif'] ?></span>
            <span onclick="ajaxData('term=<?= $transterm ?>&modinfos=categorie-<?= $infos['categorie'] ?>', '../src/pages/contacts/contacts_recherche.php', 'action', 'attente_target');" class="puce-mag text-bold pointer"><?= $infos['categorie'] ?></span>
            <span onclick="ajaxData('term=<?= $transterm ?>&modinfos=type-<?= $infos['type'] ?>', '../src/pages/contacts/contacts_recherche.php', 'action', 'attente_target');" class="puce-mag text-bold pointer"><?= $infos['type'] ?></span>
            <span onclick="ajaxData('term=<?= $transterm ?>&modinfos=type-<?= $infos['connu'] ?>', '../src/pages/contacts/contacts_recherche.php', 'action', 'attente_target');" class="puce-mag text-bold pointer"><?= $infos['connu'] ?></span>
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
</div> -->
<?php
include $chemin . '/inc/foot.php';
?>
<script>
  $(function() {
    $('.pointer').tooltip();
  });
</script>
<script src="../assets/js/script.js?=<?= time(); ?>"></script>