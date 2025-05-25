<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
$idcompte = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
include $chemin . '/vendor/autoload.php';
//include $chemin . '/inc/error.php';
$auth = new Delight\Auth\Auth($db);
$magesquo = new MaGesquo($idcompte);
$user = new Users($iduser);
$entreprise = new Idcompte($idcompte);
$idusers = $auth->getUserId();
$username = $auth->getUsername();
$emailuser = $auth->getEmail();
$verif_idcompte = $magesquo->bilanData($idcompte,$idsuer, 'idcompte');
$verif_users = $magesquo->bilanData($idcompte,$idsuer, 'users_infos');

$color_user = $verif_users['pourcentage'] < 50 ? 'rouge' : 'vert';
$color_idcompte = $verif_idcompte['pourcentage'] < 50 ? 'rouge' : 'vert';

$bilan_idcompte = Dec_0($verif_idcompte['pourcentage'], ' %');
$bilan_user = Dec_0($verif_users['pourcentage'], ' %');
$post_csrf = isset($_POST['csrf']) ? $_POST['csrf'] : '';
//echo '</br>';
$session_csrf = isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '';
//prettyc($_POST);
if (!empty($_POST) && !isset($_POST['username']) && !isset($_POST['password'])) {
  if (!isset($_SESSION['csrf_token']) || $session_csrf !== $post_csrf) {
    echo "<p class='text-danger mb-2 mr-1'>Erreur de jeton. Mise à jour impossible.</p>";
  } else {
    if ($_POST['update_user'] === 'update') {
      $erreurs = $user->updateUser($idusers, $_POST);
      if (!empty($erreurs)) {
        foreach ($erreurs as $erreur) {
          $er .= $erreur . ' ';
          //echo "<span class='text-muted mr-1'>$erreur</span>"; // Afficher les messages d'erreur
        }
      }
    }
    if ($_POST['update_entreprise'] === 'update') {
      $erreurs = $entreprise->updateIdcompte($idcompte, $_POST);
      if (!empty($erreurs)) {
        foreach ($erreurs as $erreur) {
          $er .= $erreur . ' ';
          //echo "<span class='text-muted mr-1'>$erreur</span>"; // Afficher les messages d'erreur
        }
      }
    }
  }
}
?>
<div class="myalert">
  <div class="row">
    <div class="col-md-6">
      <p class="text-bold">Bonjour <?= ucfirst($username) ?>,</p>
      <p>Avant de continuer, vous devez renseigner les informations sur l'utilisateur du compte et l'entreprise. :)</p>
    </div>
    <div class="col-md-6">
      <div class="pull-right">
        <span class="mypuce mr-1"><i class='bx bx-list-ol bx-xs text-white mr-1'></i>n° <?= $idusers ?> </span>
        <span class="mypuce mr-1"><i class='bx bx-user bx-xs text-white mr-1'></i> <?= $username ?> </span>
        <span class="mypuce mr-1"> <i class='bx bx-buildings bx-xs text-white mr-1'></i> <?= $idcompte ?> </span>
        <span class="mypuce"> <i class='bx bx-envelope bx-xs text-white mr-1'></i> <?= $emailuser ?> </span>
      </div>
    </div>
  </div>
</div>
<form autocomplete="off" id="form" method="post" action="/obligation_entreprise">
  <div class="row">

    <div class="col-md-12">
      <p class="text-warning" style="min-height:30px"><?= $er ?></p>
    </div>
    <div class="col-md-4">
      <?php
      $infos_entreprise = $entreprise->askIdcompte($idcompte);
      if ($infos_entreprise) {
        foreach ($infos_entreprise as $key => $value) {
          ${$key} = !empty($value) ? $value : $_POST[$key];
          //echo '$' . $key . ' = ' . $value . '<br>';
        }
      }
      ?>
      <p class="titre_menu_item mb-2">Informations de l'entreprise <span class="mypills <?= $color_idcompte ?>"><?= $bilan_idcompte ?></span></p>
      <!-- <form autocomplete="off" id="form" method="post" action="/obligation_entreprise"> -->
      <input type="hidden" name="update_entreprise" value="update">
      <input type="hidden" name="referent_id" value="<?= $infos_entreprise['referent_id'] ?>">
      <input type="hidden" name="idcompte" value="<?= $infos_entreprise['idcompte'] ?>">
      <input type="hidden" name="csrf" value="<?= $_SESSION['csrf_token'] ?>">
      <div class="input-group mb-2">
        <span class="input-group-text l-12">SIREN *</span>
        <input type="text" class="form-control" id="siren_search" name="siren" placeholder="Recherche SIREN (9 chiffres)" value="<?= $siren  ?>">
      </div>
      <!-- onkeyup="fetchSIRET(this.value)"  <div id="autocomplete-suggestion"></div>-->
      <div id="suggSiret"></div>
      <div id="formSiret" style="display:none">
        <div class="input-group mb-2">
          <span class="input-group-text l-12" id="basic-addon3">SIRET *</span>
          <input type="text" class="form-control" id="siret_ent" name="siret" autocomplete="off" value="<?= $siret ?>" placeholder="SIRET">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-12" id="basic-addon3">Raison sociale *</span>
          <input type="text" class="form-control" id="nom_legal" name="nom_legal" placeholder="Raison sociale" value="<?= $nom_legal  ?>">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-12" id="basic-addon3">Dénomination *</span>
          <input type="text" class="form-control" id="denomination_commerciale" name="denomination_commerciale" placeholder="Dénomination commerciale" value="<?= $denomination_commerciale  ?>">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-12" id="basic-addon3">Statuts *</span>
          <input type="text" class="form-control" id="statut_ent" name="statuts" placeholder="Statuts" value="<?= $statut ?>">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-12" id="basic-addon3">Adresse *</span>
          <input type="text" class="form-control" id="adresse_ent" name="adresse_ent" autocomplete="off" value="<?= $adresse ?>" placeholder="Adresse">
        </div>
        <div class=" mb-2">
          <div class="input-group mb-2">
            <span class="input-group-text l-12" id="basic-addon3">Ville *</span>
            <input type="text" class="form-control" id="ville_ent" name="ville_ent" placeholder="Ville" readonly value="<?= $ville ?>">
          </div>
          <div class="input-group mb-2">
            <span class="input-group-text l-12" id="basic-addon3">Code Postal *</span>
            <input type="text" class="form-control" id="cp_ent" name="cp_ent" placeholder="Code postal" readonly value="<?= $cp ?>">
          </div>
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-12" id="basic-addon3">Téléphone *</span>
          <input type="text" class="form-control" id="telephone" name="telephone_ent" placeholder="Fixe" onkeydown="Fixe(this)" onkeypress="Fixe(this)" onkeyup="Fixe(this)" value="<?= $telephone ?>">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-12" id="basic-addon3">Portable *</span>
          <input type="text" class="form-control" id="portable" name="portable_ent" placeholder="Portable" onkeydown="Fixe(this)" onkeypress="Fixe(this)" onkeyup="Portable(this)" value="<?= $portable ?>">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-12" id="basic-addon3">Email *</span>
          <input type="email" class="form-control" placeholder="adresse@mail.fr" id="email_ent" name="email_ent" onkeydown="Email(this)" onkeypress="Email(this)" onkeyup="Email(this)" autocomplete="off" value="<?= $email ?>">
        </div>
        <input type="hidden" name="latitude" id="latitude" value="">
        <input type="hidden" name="longitude" id="longitude" value="">
        <input type="hidden" name="activite" id="activite" value="">
        <div class="small text-right text-muted mb-1">* Tous les champs sont obligatoires</div>
        <div class="text-right">
          <!-- <button type="reset" class="btn btn-mag-n text-danger" onclick="ajaxData('cs=cs', '../src/menus/menu_parametres.php', 'target-one', 'attente_target');">Retour</button> -->
          <!-- <input type="hidden" name="actif" value="1"> <input type="hidden" name="payeok" value="1"> -->
          <!-- <input type="submit" class="btn btn-valid  text-muted" value="Enregistrer l'entreprise" />
        <button type="reset" class="btn btn-valid mr-1"><i class="bx bx-reset"></i></button> -->
        </div>
      </div>
      <!-- </form> -->
    </div>

    <div class="col-md-1"></div>
    <div class="col-md-4">
      <?php
      $infos = $user->askIdUser();
      if ($infos) {
        foreach ($infos as $key => $value) {
          ${$key} = !empty($value) ? $value : $_POST[$key];
          //echo '$' . $key . ' = ' . $value . '<br>';
        }
      }
      ?>
      <p class="titre_menu_item mb-2">Informations personnelles de l'utilisateur <span class="mypills <?= $color_user ?>"><?= $bilan_user ?></span></p>
      <!-- <form autocomplete="off" id="form" method="post" action="/obligation_user"> -->
      <div id="formUser" style="display:none">
        <input type="hidden" name="update_user" value="update">
        <input type="hidden" name="statut" value="admin">
        <input type="hidden" name="id" value="<?= $infos['id'] ?>">
        <input type="hidden" name="idcompte" value="<?= $infos['idcompte'] ?>">
        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf_token'] ?>">
        <div class="input-group mb-2">
          <span class="input-group-text l-12" id="basic-addon3">Nom *</span>
          <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" value="<?= $nom  ?>">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-12" id="basic-addon3">Prénom *</span>
          <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom" value="<?= $prenom ?>">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-12" id="basic-addon3">Adresse *<i title="Une adresse normalisée va vous être proposée. Cliquez, et les champs ville et code postal seront automatiquement renseignés." class='bx bxs-info-circle ml-2 text-primary'></i></span>
          <input type="text" class="form-control" id="adresse1" name="adresse" autocomplete="off" value="<?= $adresse ?>" placeholder="Adresse">
          <div class="address-feedback position-absolute list-group active" style="z-index:1100;">
          </div>
        </div>
        <div class=" mb-2">
          <div class="input-group mb-2">
            <span class="input-group-text l-12" id="basic-addon3">Ville *</span>
            <input type="text" class="form-control" id="ville1" name="ville" placeholder="Ville" readonly value="<?= $ville ?>">
          </div>
          <div class="input-group mb-2">
            <span class="input-group-text l-12" id="basic-addon3">Code Postal *</span>
            <input type="text" class="form-control" id="cp1" name="cp" placeholder="Code postal" readonly value="<?= $cp ?>">
          </div>
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-12" id="basic-addon3">Téléphone *</span>
          <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Fixe" onkeydown="Fixe(this)" onkeypress="Fixe(this)" onkeyup="Fixe(this)" value="<?= $telephone ?>">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-12" id="basic-addon3">Portable *</span>
          <input type="text" class="form-control" id="portable" name="portable" placeholder="Portable" onkeydown="Fixe(this)" onkeypress="Fixe(this)" onkeyup="Portable(this)" value="<?= $portable ?>">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-12" id="basic-addon3">Email *</span>
          <input type="email" class="form-control" placeholder="adresse@mail.fr" id="email" name="email" onkeydown="Email(this)" onkeypress="Email(this)" onkeyup="Email(this)" autocomplete="off" value="<?= $email ?>">
        </div>
        <div class="small text-right text-muted mb-1">* Tous les champs sont obligatoires</div>
        <div class="text-right">

          <input type="submit" class="btn btn-valid  text-muted" value="Enregistrer le référent" />
          <button type="reset" class="btn btn-valid mr-1"><i class="bx bx-reset"></i></button>
        </div>
        <!-- </form> -->
      </div>
    </div>

  </div>
</form>



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
<script>
  $(function() {
    async function fetchSIRET(query) {
      const response = await fetch(`https://recherche-entreprises.api.gouv.fr/search?q=${query}`);
      const theData = await response.json();
      //  console.log(theData);
      return theData;
    }
    async function statutCode(code) {
      try {
        // Remplacez 'chemin/vers/votre/fichier.json' par le chemin réel de votre fichier JSON
        const response = await fetch('../api/legal_entity_types.json');
        // Vérifiez si la réponse est correcte
        if (!response.ok) {
          throw new Error('Erreur lors de la récupération des données');
        }
        // Convertir la réponse en JSON
        const data = await response.json();
        //console.log(data);
        // Supposons que les données sont un tableau d'objets et que chaque objet a un 'code' et un 'abrege'
        const info = data.find(item => item.code === code);
        //console.log('Infos', info);
        // Retourner l'info abrégée si elle existe
        return info ? info.abrege : null;
      } catch (error) {
        console.error('Erreur:', error);
        return null;
      }
    }
    document.getElementById('siren_search').addEventListener('input', async function() {
      const query = this.value.trim();
      const suggContainer = document.getElementById('suggSiret');
      const Formulaire = document.getElementById('formSiret');
      const Formuser = document.getElementById('formUser');

      // Réinitialisation des suggestions
      suggContainer.innerHTML = '';
      // Vérification pour éviter les requêtes inutiles
      if (query.length < 8) return;
      suggContainer.style = 'display:block';
      // Récupérer les suggestions depuis l'API
      const myData = await fetchSIRET(query);
      console.log('Données reçues de l’API api.gouv.fr :', myData);
      // const features = myData.resultats_nom_entreprise || myData.resultats_siren || myData.resultats_siret || [];
      const features = myData['results'][0] || [];
      console.log('Données [results] reçues de l’API api.gouv.fr :', features);
      console.log('my features entreprise ', features.nom_raison_sociale);
      console.log('my features siret ', features.siren);
      console.log('my features siren ', features.activite_principale);
      const valid = myData.statusCode || 200; // Par défaut 200 si `statusCode` n'existe pas
      const error = (myData.error || 'Erreur inconnue') + '. ' + (myData.message || '');
      if (valid !== 200) {
        console.log('Erreur API :', error);
        suggContainer.innerHTML = `<div class="error-message">${error}</div>`;
        return; // Arrête l'exécution en cas d'erreur
      }
      if (
        (!features.nom_raison_sociale || features.nom_raison_sociale.length === 0) &&
        (!features.siren || features.siren.length === 0) &&
        (!features.activite_principale || features.activite_principale.length === 0)
      ) {
        console.log('Aucune donnée trouvée.');
        suggContainer.innerHTML = '<div class="text-warning">Aucune donnée trouvée pour ce SIREN : ' + query + ' </div>';
        Formulaire.style.display = "none";
        Formuser.style.display = "none";
        return;
      } else {
        suggContainer.style.display = 'none';
        Formulaire.style.display = "block";
        Formuser.style.display = "block";

        const typeVoie = features.siege.type_voie || '';
        const numeroVoie = features.siege.numero_voie || '';
        const codeStatut = (features.nature_juridique) || '0000';
        console.log('Code du statut', codeStatut);
        statutCode(codeStatut).then(abrege => {
          document.getElementById('statut_ent').value = abrege;
          console.log('Statut abrege', abrege); // Cela affichera "SARL"
        });
        document.getElementById('nom_legal').value = features.nom_raison_sociale || features.nom_complet;
        document.getElementById('denomination_commerciale').value = features.siege.nom_commercial || features.nom_complet;
        document.getElementById('adresse_ent').value = numeroVoie + ' ' + typeVoie + ' ' + features.siege.libelle_voie;
        document.getElementById('adresse1').value = numeroVoie + ' ' + typeVoie + ' ' + features.siege.libelle_voie;

        document.getElementById('cp_ent').value = features.siege.code_postal;
        document.getElementById('cp1').value = features.siege.code_postal;
        document.getElementById('ville_ent').value = features.siege.libelle_commune;
        document.getElementById('ville1').value = features.siege.libelle_commune;

        document.getElementById('siret_ent').value = features.siege.siret;
        document.getElementById('latitude').value = features.siege.latitude;
        document.getElementById('longitude').value = features.siege.longitude;
        document.getElementById('activite').value = features.siege.activite_principale;
        document.getElementById('nom').value = features.dirigeants[0].nom || '';
        document.getElementById('prenom').value = features.dirigeants[0].prenoms || '';
      }
    });
  });
</script>