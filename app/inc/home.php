<?php
$secteur = $_SESSION['idcompte'];
$idusers = $_SESSION['idusers'];
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/vendor/autoload.php';
//include $chemin . '/inc/function.php';
// $db = Connexion();
// $auth = new Delight\Auth\Auth($db);
$conn = new connBase();
$sect = $conn->askIdcompte($secteur);
$compte = new Compte($idusers);
$date_debut = $compte->getDateDebut();
$facturation_totale = $compte->getFacturation($secteur, '09', '2023');
$ok = $compte->verifPaye();
$date_fin_essai = $compte->getFinEssai();

?>
<div class="container">
  <div class="bg-mag mt-2 mb-4">
    <div id="attente_target" class="attente" style="display:none"><i class='bx bx-refresh bx-spin bx-lg text-danger'></i></div>
  </div>
</div>


<div class="container mt-2">
  <div class="row">
    <div class="col-md-12">

      <nav class="navbar fixed-bottom bg-horloge">

        <div class="container">
          <div class="me-auto  ">

            <!-- <span class=""><?= $idcompte . ' ' . $idusers  ?></span> -->
            <span id="renvoi_page_e" class="text-muted"></span>
            <!-- <i class='bx bx-smile icon-bar bx-sm pointer'></i><i class='bx bx-meh icon-bar bx-sm pointer'></i> -->
            <i class='bx bx-bug icon-bar bx-sm pointer' onclick="Go();"></i>
            <?php
            if ($date_debut) {
              $fact = $facturation_totale[0]['t'];
              $du = $fact * 0.001;
            ?>

              <span class="container bande small pointer" onclick="ajaxData('idcompte=<?= $secteur ?>', '../src/pages/parametres/parametres_bills.php', 'target-one', 'attente_target');">Essai jusqu'au <?= $date_fin_essai ?></span>
            <?php
            }
            ?>


            <div class="bug_bas" style="display:none" id="cavapas">
              <form class="row" id="bug_form">

                <div class="col-auto p-0">

                  <input type="text" class="form-control mr-1" style="width: 850px;" name="message" placeholder="Bug constaté ou amélioration proposée...">
                </div>
                <div class="col-auto p-0">
                  <button type="button" class="btn btn-mag text-warning" onclick="ajaxForm('#bug_form','../src/pages/bug/bug_envoi.php','reponse_bug','attente_target');">Envoyez</button>
                  <input type="hidden" value="" id="renvoi_page" name="page">
                  <input type="hidden" value="<?= $idcompte ?>" id="idcompte" name="idcompte">
                  <input type="hidden" name="email" id="email" value="<?= $sect['email'] ?>">
                </div>
              </form>
              <p id="reponse_bug" class="ml-2"></p>
            </div>
            <span class="small ml-1 text-muted"><a href="https://sagaas.fr/"><i class='bx bx-network-chart  bx-flxxx'></i></a> </span>

          </div>
          <script>
            function Go() {
              $('#cavapas').toggle('slide', 400);

            }
          </script>
          <div class="ms-auto">
            <div id="horloge" class=""></div>
          </div>
        </div>

      </nav>
      <script src="../assets/js/horloge.js?<?= time(); ?>"></script>
      <script>
        var h = date_heure('horloge');
        console.log('Heure : ' + h);
      </script>
    </div>
  </div>
</div>