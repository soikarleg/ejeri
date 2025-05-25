<?php
//session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
include $chemin . '/vendor/autoload.php';
//include $chemin . '/inc/dbconn.php';
//include $chemin . '/inc/error.php';
//$db = Connexion();
$auth = new Delight\Auth\Auth($db);
$conn = new connBase();
$connauth = new authBase();
$idusers = $auth->getUserId();
$username = $auth->getUsername();
$emailuser = $auth->getEmail();

$magesquo = new Magesquo($secteur);
?>
<div class=" mb-4">

  <?php

  $param = ['idcompte' => $secteur, 'iduser' => $iduser];
  $reqentreprise = "select * from users_infos where id=:iduser and idcompte = :idcompte";
  $infos = $magesquo->oneRow($reqentreprise, $param);
  if ($infos) {
    foreach ($infos as $key => $value) {
      ${$key} = $value;
      //echo '$' . $key . '=' . $value . '<br>';
    }
  }
  ?>
  <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-6">
      <p class="puce pull-right">N° <?= $secteur ?></p>
      <p class="titre_menu_item">Modifier l'administrateur du compte</p>
      <p class="text-muted mb-4"> N° <?= $iduser . ' / ' . $username . ' / ' . $emailuser  ?></p>
      </p>
      <form autocomplete="off" id="form">
        <!-- <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Identifiant</span>
        <input type="text" class="form-control readonly" value="<?= $username ?>" id="username" name="username" readonly>
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Email</span>
        <input type="text" class="form-control readonly" id="emailuser" name="emailuser" value="<?= $emailuser ?>" readonly>
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Réf compte</span>
        <input type="text" class="form-control readonly" id="secteur" name="secteur" readonly value="<?= $secteur ?>">
      </div> -->
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Civilité *</span>
          <select class="form-control" name="civilite">
            <option value="">Choix</option>
            <option value="Mme" <?php if ($civilite === "Mme") echo "selected='selected'"  ?>>Madame</option>
            <option value="M." <?php if ($civilite === "M.") echo "selected='selected'"  ?>>Monsieur</option>
            <option value="Mlle" <?php if ($civilite === "Mlle") echo "selected='selected'"  ?>>Mademoiselle</option>
          </select>
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Nom *</span>
          <input type="text" class="form-control" id="nom" name="nom" value="<?= $infos['nom'] ?>">
          <input type="hidden" name="statut" value="admin">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Prénom *</span>
          <input type="text" class="form-control" id="prenom" name="prenom" value="<?= $infos['prenom'] ?>">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Adresse *<i title="Une adresse normalisée va vous être proposée. Cliquez, et les champs ville et code postal seront automatiquement renseignés." class='bx bxs-info-circle ml-2 text-primary'></i></span>
          <input type="text" class="form-control" id="adresse1" name="adresse" autocomplete="off" value="<?= $infos['adresse'] ?>">
          <!-- <div class="address-feedback position-absolute list-group" style="z-index:1100;">
          </div> -->
        </div>
        <div class=" mb-2">
          <div class="input-group mb-2">
            <span class="input-group-text l-9" id="basic-addon3">Ville *</span>
            <input type="text" class="form-control" id="ville1" name="ville" placeholder="Ville" readonly value="<?= $infos['ville'] ?>">
          </div>
          <div class="input-group mb-2">
            <span class="input-group-text l-9" id="basic-addon3">Code Postal *</span>
            <input type="text" class="form-control" id="cp1" name="cp" placeholder="Code postal" readonly value="<?= $infos['cp'] ?>">
          </div>
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Téléphone *</span>
          <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Fixe" onkeydown="Fixe(this)" onkeypress="Fixe(this)" onkeyup="Fixe(this)" value="<?= $infos['telephone'] ?>">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Email *</span>
          <input type="email" class="form-control" placeholder="adresse@mail.fr" id="email" name="email" onkeydown="Email(this)" onkeypress="Email(this)" onkeyup="Email(this)" autocomplete="off" value="<?= $infos['email'] ?>">
        </div>
        <p class="small text-right text-muted">* champ obligatoire</p>
        <div class="text-right">
          <p>
            <button type="reset" class="btn btn-mag-n text-danger" onclick="ajaxData('cs=cs', '../src/menus/menu_parametres.php', 'target-one', 'attente_target');">Retour</button>
            <button type="reset" class="btn btn-mag-n"><i class="bx bx-reset icon-bar"></i></button>
            <input name="Envoyer" type="button" class="btn btn-mag-n text-primary" value="Enregistrer le référent" onclick="ajaxForm('#form', 'https://app.enooki.com/src/pages/parametres/parametres_inscription_referent_bd.php', 'sub-target', 'attente_target');" />
          </p>
        </div>
      </form>
    </div>
    <div class="col-md-4">
      <div class="address-feedback position-absolute list-group" style="z-index:1100;">
      </div>
      <div id="sub-target">
      </div>
    </div>
  </div>
</div>
<script>
  $(function() {
    $('.bx').tooltip();
  });

  function HighLight(field, error) { //COULEUR
    if (error)
      field.style.borderBottom = "2px solid #dc3545";
    else
      field.style.borderBottom = "2px solid #28a745";
  };

  function Portable(field) { //TELEPHONE
    var regex = /^(0|\+33)[6-7]([0-9]{2}){4}$/;
    if (!regex.test(field.value)) {
      HighLight(field, true);
      return false;
    } else {
      HighLight(field, false);
      return true;
    }
  };

  function Fixe(field) { //TELEPHONE
    var regex = /^(0|\+33)[1-9]([0-9]{2}){4}$/;
    if (!regex.test(field.value)) {
      HighLight(field, true);
      return false;
    } else {
      HighLight(field, false);
      return true;
    }
  };

  function Email(field) { //TELEPHONE
    var regex = /^((?!\.)[\w_.-]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$/;
    if (!regex.test(field.value)) {
      HighLight(field, true);
      return false;
    } else {
      HighLight(field, false);
      return true;
    }
  };
</script>
<script type="text/javascript">
  function eff_form(t) {
    $(t).val('');
  }
  // $(function() {
  var currentFocus = -1;
  var fetchTrigger = 0;
  // Fonction pour mettre en forme visuellement un résultat sélectionné
  function setActive() {
    var nbVal = $("div.address-feedback a").length;
    if (!nbVal)
      return false; // Si on n'a aucun résultat listé, on s'arrête là.
    // On commence par nettoyer une éventuelle sélection précédente
    $('div.address-feedback a').removeClass("active");
    // Bidouille mathématique pour contraindre le focus dans la plage du nombre de résultats
    currentFocus = ((currentFocus + nbVal - 1) % nbVal) + 1;
    $('div.address-feedback a:nth-child(' + currentFocus + ')').addClass("active");
  }
  // Au clic sur une adresse suggérée, on ventile l'adresse dans les champs appropriés. On espionne mousedown plutôt que click pour l'attraper avant la perte de focus du champ adresse.
  $('div.address-feedback').on("mousedown", "a", function(event) {
    // Stop la propagation par défaut
    event.preventDefault();
    event.stopPropagation();
    $("#adresse1").val($(this).attr("data-name"));
    $("#cp1").val($(this).attr("data-postcode"));
    $("#ville1").val($(this).attr("data-city"));
    $('.address-feedback').empty();
  });
  // On espionne le clavier dans le champ adresse pour déclencher les actions qui vont bien
  $("#adresse1").keyup(function(event) {
    // Stop la propagation par défaut
    event.preventDefault();
    event.stopPropagation();
    if (event.keyCode === 38) { // Flèche HAUT
      currentFocus--;
      setActive();
      return false;
    } else if (event.keyCode === 40) { // Flèche BAS
      currentFocus++;
      setActive();
      return false;
    } else if (event.keyCode === 13) { // Touche ENTREE
      if (currentFocus > 0) {
        // On simule un clic sur l'élément actif
        $("div.address-feedback a:nth-child(" + currentFocus + ")").mousedown();
      }
      return false;
    }
    // Si on arrive ici c'est que l'user a avancé dans la saisie : on réinitialise le curseur de sélection.
    $('div.address-feedback a').removeClass("active");
    currentFocus = 0;
    // On annule une éventuelle précédente requête en attente
    clearTimeout(fetchTrigger);
    // Si le champ adresse est vide, on nettoie la liste des suggestions et on ne lance pas de requête.
    let rue = $("#adresse1").val();
    if (rue.length === 0) {
      $('.address-feedback').empty();
      return false;
    }
    // On lance une minuterie pour une requête vers l'API.
    fetchTrigger = setTimeout(function() {
      // On lance la requête sur l'API
      $.get('https://api-adresse.data.gouv.fr/search/', {
        q: rue,
        limit: 2,
        autocomplete: 1
      }, function(data, status, xhr) {
        let liste = "";
        $.each(data.features, function(i, obj) {
          // données phase 1 (obj.properties.label) & phase 2 : name, postcode, city
          // J'ajoute chaque élément dans une liste
          let cooladdress = "<i class='bx bx-envelope icon-bar titre'></i> " + obj.properties.name + "</br>" + obj.properties.postcode + " <strong>" + obj.properties.city + "</strong>";
          liste += '<a class="list-group-item list-group-item-action py-1" href="#" name="' + obj.properties.label + '" data-name="' + obj.properties.name + '" data-postcode="' + obj.properties.postcode + '" data-city="' + obj.properties.city + '">' + cooladdress + '</a>';
        });
        $('.address-feedback').html(liste);
      }, 'json');
    }, 500);
  });
  // On cache la liste si le champ adresse perd le focus
  $("#adresse1").focusout(function() {
    $('.address-feedback').empty();
  });
  // On annule le comportement par défaut des touches entrée et flèches si une liste de suggestion d'adresses est affichée
  $("#adresse1").keydown(function(e) {
    if ($("div.address-feedback a").length > 0 && (e.keyCode === 38 || e.keyCode === 40 || e.keyCode === 13)) {
      e.preventDefault();
    }
  });
  // });
</script>