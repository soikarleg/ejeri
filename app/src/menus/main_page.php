<?php
//include $chemin . '/inc/head.php';
?>
<title>enooki - <?= $title ?></title>
<nav class="navbar navbar-expand-lg">
  <div class="container modi">
    <a class="navbar-brand" href="https://app.enooki.com"><img src="https://app.enooki.com/assets/img/svg/logo_liseret.svg" alt="logo" width="70px"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <?php
      $cookmod = !isset($_COOKIE['mode']) ? "" : $_COOKIE['mode'];
      if ($cookmod === 'jour') {
        $iconmode = "bx-moon";
      } else {
        $iconmode = "bx-sun";
      }
      ?>
      <?php if ($logged === true) {
        $user = $auth->getUsername();
        $useremail = $auth->getEmail();
        $userid = $auth->getUserId();
        echo $secteur = $_SESSION['idcompte'];
        $idusers = $_SESSION['idusers'];
        //! a remettre
        $verifier_data = verifData($_SESSION['idcompte']);
        //! a remettre
      ?><input id="idcompte_value" type="hidden" name="idcompte" value="<?= $secteur ?>">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 ">
          <?php
          if (!$verifier_data) {
          ?>
            <li class="nav-item mt-2 mr-1">
              <form autocomplete="off">
                <input type="text" id="recherche_dossier" class="form-control-n placeholder " placeholder="Recherche dossier..." onmouseover="eff_form(this);">
              </form>
            </li>
            <script>
              //  $(document).ready(function() {
              function eff_form(t) {
                $(t).val('');
              }
              $("#recherche_dossier").autocomplete({
                minLength: 1,
                source: function(request, response) {
                  $.ajax({
                    url: "https://app.enooki.com/api/suggest.php",
                    dataType: "json",
                    data: {
                      term: request.term
                    },
                    success: function(data) {
                      response(data);
                    },
                  });
                },
                select: function(event, ui) {
                  var idcli = ui.item.ncli;
                  $("#recherche_dossier").html('');
                  ajaxData('idcli=' + idcli + '', 'src/pages/contacts/contacts_fiche.php', 'target-one', 'attente_target');
                },
                error: function(error) {
                  console.log('Erreur autocomplete', error)
                },
                close: function(event, ui) {
                  $("#recherche_dossier").val('');
                },
              });
              //   });
            </script>
          <?php
          }
          ?>
          <!---->
          <?php
          if ($idusers == '61') {
          ?>

            <li class="nav-item dropdown">
              <a class=" dropdown-toggle btn btn-mag-n mt-2 mr-1" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class='bx bxs-grid-alt bx-flxxx icon-bar text-warning'></i>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a href="#" class="dropdown-item" onclick="ajaxData('cs=cs', '../src/menus/myindic.php', 'target-one', 'attente_target');$('#action').addClass('rel');"><i class='bx bxs-grid-alt bx-flxxx icon-bar text-primary'></i> MyIndics</a>
                </li>
                <li>
                  <a href="#" class="dropdown-item" onclick="ajaxData('cs=cs', '../src/pages/bridge/bridge.php', 'target-one', 'attente_target');$('#action').addClass('rel');"><i class='bx bxs-grid-alt bx-flxxx icon-bar text-danger'></i> Bridge</a>
                </li>
              </ul>
            </li>
          <?php
          }
          ?>
          <li class=" nav-item">
            <a href="#" id="client_menu" class="btn btn-mag-n mr-1 mt-2 " onclick="ajaxData('cs=cs', '../src/menus/menu_contacts.php', 'target-one', 'attente_target');"><i class='bx bxs-user-account bx-flxxx icon-bar'></i> Contacts</a>
          </li>
          <li class=" nav-item">
            <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('cs=cs', '../src/menus/menu_devis.php', 'target-one', 'attente_target');"><i class='bx bxs-folder-open bx-flxxx icon-bar'></i> Devis</a>
          </li>
          <?php
          if (!$verifier_data) {
          ?>

            <?php
            if ($idusers != '61') {
            ?>
              <li class=" nav-item">
                <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('cs=cs', '../src/menus/menu_productions.php', 'target-one', 'attente_target');"><i class='bx bx-qr bx-flxxx icon-bar'></i> Productions</a>
              </li>
            <?php
            } else {
            ?>
              <li class="nav-item dropdown">
                <a class=" dropdown-toggle btn btn-mag-n mt-2 mr-1" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class='bx bx-qr bx-flxxx icon-bar'></i> Prod.
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <a href="#" class="dropdown-item" onclick="ajaxData('cs=cs', '../src/menus/menu_productions.php', 'target-one', 'attente_target');$('#action').addClass('rel');"><i class='bx bx-qr bx-flxxx icon-bar'></i> Productions réalisées</a>
                  </li>
                  <li>
                    <a href="#" class="dropdown-item" onclick="ajaxData('cs=cs', '../src/pages/production/production_aprevoir.php', 'target-one', 'attente_target');$('#action').addClass('rel');"><i class='bx bx-task bx-flxxx icon-bar'></i> Production à prévoir</a>
                  </li>
                  <li class=" nav-item">
                    <a href="#" class="dropdown-item" onclick="ajaxData('cs=cs', '../src/menus/menu_agenda.php', 'target-one', 'attente_target');"><i class='bx bx-calendar bx-flxxx icon-bar'></i> Agenda</a>
                  </li>
                  <li class=" nav-item">
                    <a href="#" class="dropdown-item" onclick="ajaxData('cs=cs', '../src/menus/menu_test.php', 'target-one', 'attente_target');"><i class='bx bxl-redux  bx-flxxx icon-bar'></i> Page de test 'menu_test.php'</a>
                  </li>
                </ul>
              </li>
            <?php
            }
            ?>
            <li class=" nav-item">
              <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('cs=cs', '../src/menus/menu_factures.php', 'target-one', 'attente_target');"><i class='bx bxs-file-export bx-flxxx icon-bar'></i> Factures</a>
            </li>
            <li class=" nav-item">
              <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('cs=cs', '../src/menus/menu_reglements.php', 'target-one', 'attente_target');"><i class='bx bxs-file-import bx-flxxx icon-bar'></i> Règlements</a>
            </li>

          <?php
          }
          ?>
          <li class=" nav-item">
            <!--  -->
            <a href="#" id="compte_menu" class="btn btn-mag-n mr-2 mt-2 " onclick="ajaxData('cs=cs', '../src/menus/menu_parametres.php', 'target-one', 'attente_target');" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Paramètres du compte <?= ($user) ?>"><i class=' bx bxs-user bx-flxxx icon-bar' style="color:var(--primary-color);"></i><?= ($user) ?></a>
          </li>
          <?php
          if ($url === 'test') {
          ?>
            <li class=" nav-item">
              <a href="#" id="compte_menu" class="btn btn-mag-n mr-2 mt-2 text-danger" onclick="ajaxData('cs=cs', '../src/pages/test/test.php', 'target-one', 'attente_target');">TEST</a>
            </li>
          <?php

          }
          ?>
          <li class="nav-item">
            <a href="#" class="btn btn-mag-n mr-1 mt-2" id="toggleLink" onclick="modeJourNuit();" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Jour/Nuit"><i class='text-muted bx <?= $iconmode ?> icon-bar bx-flxxx'></i></a>
          </li>
          <li class="nav-item">
            <a href="#" class="btn btn-mag-n mr-1 mt-2" id="icon-holder" onclick="toggleFullScreen()" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Plein écran"><i class='text-muted bx bx-expand-alt icon-bar bx-flxxx'></i></a>
          </li>
          <li class="nav-item">
            <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="modi();" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Large"><i class='bx bx-move-horizontal  text-muted icon-bar bx-flxxx'></i></a>
          </li>
          <li class=" nav-item">
            <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="ajaxData('cs=cs', '../src/menus/menu_deconnexion.php', 'target-one', 'attente_target');" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Déconnexion"><i class='bx bxs-exit  text-muted icon-bar bx-flxxx'></i></a>
          </li>
        </ul>
      <?php } else { ?>
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">

            <a href="#" class="btn btn-mag-n mr-1 mt-2" id="toggleLink" onclick="modeJourNuit();" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Jour/Nuit :)"><i class='text-muted bx <?= $iconmode ?> icon-bar'></i></a>
          </li>
          <li class="nav-item">
            <a href="#" class="btn btn-mag-n mr-1 mt-2" id="icon-holder" onclick="toggleFullScreen()" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Plein écran"><i class='text-muted bx bx-expand-alt icon-bar'></i></a>
          </li>
          <li class="nav-item">
            <a href="#" class="btn btn-mag-n mr-1 mt-2" onclick="modi();" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Large"><i class='bx bx-move-horizontal  text-muted icon-bar bx-flxxx'></i></a>
          </li>
        </ul>
      <?php }
      ?>
    </div>
  </div>
</nav>
<div class="container modi" id="target-one">
  <div id="attente_target" class="attente" style="display:none"><i class='bx bx-refresh bx-spin bx-lg text-primary'></i></div>
</div>