<?php
//include $chemin . '/inc/error.php';
$username = $_SESSION['idcompte'] ?? '';


?>
<script>
  // Obtenir la largeur et la hauteur de la fenêtre du navigateur
  var largeur = window.innerWidth;
  var hauteur = window.innerHeight;
  $('#dim').html(`Largeur : ${largeur}px, Hauteur : ${hauteur}px`);
  // Envoyer les dimensions au serveur PHP
  fetch('dim.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        largeur: largeur,
        hauteur: hauteur
      })
    })
    .then(response => response.json())
    .then(data => {
      $('#dim').html(`Largeur : ${data.largeur}px, Hauteur : ${data.hauteur}px`);
      console.log('Dimensions fenêtre :', data);
    })
    .catch(error => {
      console.error('Erreur :', error);
    });
</script>
<title>enooki - <?= $title ?></title>
<div id="myoverlay" class="myoverlay">
  <div class="loader"><i class='bx bx-loader-circle bx-tada bx-rotate-90 bx-lg' style='color:#ffffff'></i></div>
</div>
<!-- style="display:none;" -->
<div class="container-fluid" id="mycontent">
  <div class="row">
    <div class="col-md-12 mt-2">
      <nav class="navbar navbar-expand-lg mybandeau" style="min-height:45px">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse show" id="navbarTogglerDemo02">
          <ul class="navbar-nav me-auto mt-2 mt-lg-0">
            <li class="nav-item">
              <a href="/" class="nav-link"><img class="" src="/favicon.ico" alt="logo" width="25px"></a>
            </li>
            <li class="nav-item">
              <form autocomplete="off" class="">
                <input type="text" id="recherche_dossier" style="margin-top:0.2rem" class="form-control-n placeholder " placeholder="Recherche dossier..." onmouseover="eff_form(this);">
              </form>
            </li>
            <li class="nav-item">
              <a class="nav-link btn btn-mag-n" href="/indicateurs?annee=<?= date('Y') ?>"><i class="bx  bx-line-chart"></i>Indicateurs <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link  btn btn-mag-n" href="/contacts"><i class="bx  bx-user"></i>Contacts </a>
            </li>
            <li class="nav-item">
              <a class="nav-link  btn btn-mag-n" href="/intervenants"><i class="bx  bx-user-check"></i>Intervenants </a>
            </li>
            <li class="nav-item">
              <a class="nav-link  btn btn-mag-n" href="/devis"><i class="bx  bx-file"></i>Devis </a>
            </li>
            <li class="nav-item">
              <a class="nav-link  btn btn-mag-n" href="/production"><i class="bx  bx-qr"></i>Productions </a>
            </li>
            <li class="nav-item">
              <a class="nav-link  btn btn-mag-n" href="/facturation"><i class="bx  bxs-file-export"></i>Facturations </a>
            </li>
            <li class="nav-item">
              <a class="nav-link  btn btn-mag-n" href="/encaissement"><i class="bx  bxs-file-import"></i>Encaissements </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link  btn btn-mag-n dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bx  bxs-file-import"></i> Options</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item text-warning" href="/test">Ma page de test</a></li>
                <li><a class="dropdown-item" href="#" id="dim">dim</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="#">Something else here</a></li>
              </ul>
            </li>
            <!-- <li class="nav-item">
              <a class="nav-link  btn btn-mag-n" href="https://app.enooki.com<?= $pageref ?>"><em><?= $pageref ?></em> </a>
            </li> -->
          </ul>
          <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
            <li class="nav-item">
              <a class="nav-link  btn btn-mag-n text-color-change" href="/parametres"><i class="bx  bx-cog"></i>Paramètres <?= $idcompte ?> </a>
            </li>
            <li class="nav-item">
              <a class="nav-link  btn btn-mag-n" href="/deconnexion"><i class="bx bxs-log-in-circle"></i>Déconnexion</a>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 mt-2">
      <nav class="navbar navbar-expand-lg mylight height40">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02A" aria-controls="navbarTogglerDemo02A" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse show" id="navbarTogglerDemo02A">

          <ul class="navbar-nav me-auto mt-2 mt-lg-0">
            <?php
            // MENUS
            switch ($url) {
              case 'bug':
              case 'bugs_ajouter':
              case 'bugs_liste':
              case 'bugs_resolus':
                include $chemin . '/src/menus/menu_bugs.php';
                break;
              case 'indicateurs':
              case '':
                include $chemin . '/src/menus/menu_indicateurs.php';
                break;
              case 'parametres':
              case 'modifier_entreprise':
              case 'modifier_administrateur':
                include $chemin . '/src/menus/menu_parametres.php';
                break;
              case 'contacts':
              case 'contacts_ajouter':
              case 'contacts_liste':
                include $chemin . '/src/menus/menu_contacts.php';
                break;
              case 'fiche_client':
                include $chemin . '/src/menus/menu_fiche.php';
                break;
            }
            ?>
          </ul>
        </div>
      </nav>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 mt-2">
      <!-- mylight p-3 -->
      <div class="myscroll p-3">
        <?php
        $verif_idcompte = $magesquo->bilanData($idcompte, $idusers, 'idcompte');
        $verif_users = $magesquo->bilanData($idcompte, $idusers, 'users_infos');
        $bilan_idcompte = $verif_idcompte['pourcentage'];
        $bilan_user = $verif_users['pourcentage'];
        if ($bilan_idcompte < 10 || $bilan_user < 10) {
          switch ($url) {
            case 'deconnexion':
              include $chemin . '/src/menus/menu_deconnexion.php';
              break;
            case 'test':
              include $chemin . '/src/pages/test/test_class.php';
              break;
            case 'obligation_user':
            case 'obligation_entreprise':
              include $chemin . '/src/pages/obligation/obligation.php';
              break;
            default:
              $title = 'Obligation';
              include $chemin . '/src/pages/obligation/obligation.php';
              break;
          }
        } else {
          switch ($url) {
            case 'indicateurs':
              //echo $_GET['annee'];
              include $chemin . '/src/menus/myindic.php';
              break;
            case 'contacts':
              $title = 'Obligation';
              include $chemin . '/src/pages/contacts/contacts_garde.php';
              break;
            case 'contacts_ajouter':
              include $chemin . '/src/pages/contacts/contacts_ajouter.php';
              break;
            case 'contacts_liste':
              include $chemin . '/src/pages/contacts/contacts_liste.php';
              break;
            case 'intervenants':
              include $chemin . '/src/pages/intervenants/intervenant_ajouter.php';
              break;
            case 'devis':
              include $chemin . '/src/menus/menu_devis.php';
              break;
            case 'fiche_client':
              include $chemin . '/src/pages/contacts/contacts_fiche.php';
              break;
            case 'planning':
              include $chemin . '/src/pages/calendar/calendar_planning.php';
              break;
            case 'test':
              include $chemin . '/src/pages/test/test_class.php';
              break;
            case 'encaissement':
              include $chemin . '/src/pages/reglements/reglements_liste.php';
              break;
            case 'parametres':
            case 'modifier_entreprise':
            case 'modifier_administrateur':
              $title = 'Parametres';
              include $chemin . '/src/pages/parametres/parametres_garde.php';
              break;

            case 'param_factures':
              include $chemin . '/src/pages/parametres/parametres_bills.php';
              include $chemin . '/src/menus/menu_parametres.php';
              break;
            case 'deconnexion':
              include $chemin . '/src/menus/menu_deconnexion.php';
              break;
            case 'bug':
              include $chemin . '/src/pages/bug/bug_etat.php';
              break;
            case 'bugs_ajouter':
              include $chemin . '/src/pages/bug/bug_ajouter.php';
              break;
            case 'bugs_resolus':
              include $chemin . '/src/pages/bug/bug_resolu.php';
              break;
            case 'bugs_liste':
              include $chemin . '/src/pages/bug/bug_liste.php';
              break;
            default:
              include $chemin . '/src/menus/myindic.php';
              break;
          }
        }
        ?>
        <div id="attente_target" class="attente" style="display:none"><i class='bx bx-refresh bx-spin bx-lg text-primary'></i></div>
      </div>
    </div>
  </div>
  <?php
  include $chemin . '/src/menus/mymenupied.php';
  ?>
  <!-- <nav class="navbar fixed-bottom bg-body-tertiary">
    <div class="container-fluid">
      <div class="mybandeau nav-link" style="min-height:45px">
        
        <p class="text-white" id="horloge"></p>
        <?php
        if ($url != "bug" && $url != "bugs_ajouter" && $url != "bugs_liste" && $url != "bugs_resolus") {
        ?>
          <a href="/bug?page=<?= $pageref ?>" class="btn btn-mag-n" style="margin-left:50px"><i class='bx bx-bug mt-1 text-warning'></i>Signaler un bug sur ou suggérer une amélioration sur la page '<?= $pageref ?>'</a>
          <p class="btn btn-mag-n"></p>
        <?php
        }
        ?>
      </div>
    </div>
  </nav> -->
</div>
</div>
</div>
<!-- <script src="../assets/js/horloge.js?<?= time(); ?>"></script>
<script>
  var h = date_heure('horloge');
  console.log('Heure : ' + h);
</script> -->
<script>
  //  $(document).ready(function() {
  function eff_form(t) {
    $(t).val('');
  }
  $("#recherche_dossier").autocomplete({
    minLength: 2,
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
    create: function() {
      $(this).data("ui-autocomplete")._renderItem = function(ul, item) {
        return $("<li>")
          .append(item.label) // Utiliser le label avec HTML
          .appendTo(ul);
      };
    },
    select: function(event, ui) {
      if (ui.item.label.startsWith("<p>Aucun résultat pour")) {
        $("#recherche_dossier").html('');
        window.location.href = "/contacts";
      } else {
        var idcli = ui.item.ncli;
        $("#recherche_dossier").html('');
        window.location.href = "/fiche_client?idcli=" + idcli;
      }
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