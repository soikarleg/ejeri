<?php
//error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
//include $chemin . '/inc/error.php';

$secteur = $_SESSION['idcompte'];
$conn = new Magesquo($secteur);

foreach ($_POST as $k => $v) {
  ${$k} = $v;
  // echo $k . ' ' . $v . '</br>';

};
$param = ['idcompte' => $secteur];
$req_civ = "select civilite from client where civilite !=' ' and idcompte=:idcompte group by civilite order by civilite asc";
$civilite_bd = $conn->allRow($req_civ, $param);

$cp = '';
$ville = '';
?>
<div class="">


  <div class="row">
    <div class="col-md-4">
      <!-- <p class="titre_menu_item mb-2">Normalisation des adresses</p> 
      <p class="myaide mt-1" data-bs-toggle="collapse" href="#infos_adresse" role="button" aria-expanded="true" aria-controls="infos_adresse">
        <i class='bx bxs-help-circle text-info'></i>Tout savoir sur la normalisation des adresses
      </p>-->

      <div class="collapse" id="infos_adresse">
        <div class="mt-2 text-justify px-5 ">



          <span class="text-primary ">Normalisation des adresses</span> :</br>
          Lors de l'ajout d'un contact dans notre plateforme de gestion d'activité, nous utilisons un système d'autocomplétion d'adresse normalisé fourni par 'api-adresse.data.gouv.fr'. Lorsque vous commencez à saisir l'adresse, des suggestions normalisées apparaissent. En sélectionnant l'une de ces suggestions, les champs "Adresse", "Code Postal" et "Ville" sont automatiquement remplis avec les informations correctes et standardisées. Cette fonctionnalité garantit que les adresses enregistrées dans notre système sont cohérentes et conformes aux normes postales, réduisant ainsi les risques d'erreurs et facilitant la gestion des données clients.</br></br><span class="text-primary ">Champs "Civilité", "Nom", "Prénom", "Adresse", "Ville", "Code Postal" </span>: </br>Ces champs sont obligatoires et doivent être remplis pour chaque contact. Cela permet de garantir que vous avez les informations de base nécessaires pour contacter le client.</br></br>
          <span class="text-primary ">Champs "Portable", "Fixe", "Email" </span>:</br>Bien que ces champs ne soient pas obligatoires, il est fortement recommandé de les remplir. Avoir plusieurs moyens de contact permet de faciliter la communication avec le client.</br></br>
        </div>
      </div>



    </div>
    <div class="col-md-4">
      <p class="titre_menu_item mb-2">Ajouter un contact</p>
      <p class="myaide mb-2 small" data-bs-toggle="collapse" href="#infos_adresse" role="button" aria-expanded="true" aria-controls="infos_adresse">
        <i class='bx bx-xs bxs-help-circle text-info'></i>Tout savoir sur la normalisation des adresses
      </p>
      <div class="">
        <form action="/contacts" autocomplete="off" id="form" method="post">
          <div class="input-group mb-2">
            <span class="input-group-text l-9" id="basic-addon3">Civilité *</span>
            <select class="form-control" id="c_civ" onchange="copyCivilite();">
              <option value="">Choix</option>
              <?php
              foreach ($civilite_bd as $civ) {
                if ($civ['civilite'] == $civilite) {
                  $check = "selected=\"selected\"";
                } else {
                  $check = "";
                }
              ?>
                <option value="<?php echo $civ['civilite']; ?>" <?php echo $check; ?>><?php echo $civ['civilite']; ?>
                </option>
              <?php } ?>
            </select>
            <input type="text" class="form-control civ" id="civilite" name="civilite" value="<?= $civilite ?>" placeholder="Nouvelle civilité">
          </div>
          <div class="input-group mb-2">
            <span class="input-group-text l-9" id="basic-addon3">Nom *</span>
            <input type="text" class="form-control" id="nom" name="nom" value="<?= $nom ?>" required>
          </div>
          <div class="input-group mb-2">
            <span class="input-group-text l-9" id="basic-addon3">Prénom</span>
            <input type="text" class="form-control" id="prenom" name="prenom" value="<?= $prenom ?>">
          </div>
          <div class="input-group mb-2">
            <span class="input-group-text l-9" id="basic-addon3">Adresse *<i title="Une adresse normalisée va vous être proposée. Cliquez, et les champs ville et code postal seront renseignés." data-bs-placement="right" class='bx bxs-info-circle ml-2 text-primary'></i></span>
            <input type="text" class="form-control" id="adresse1" name="adresse" value="<?= $adresse ?>" autocomplete="off" required>
            <!-- <div class="address-feedback position-absolute list-group" style="z-index:1100;">
            </div> -->
          </div>
          <div class="border-dot mb-2 p-2" id="adresse_auto" data-bs-placement="right" title="La ville et le code postal seront inscrit dès que vous aurez selectionné l'adresse qui vous ai proposée.">
            <div class="input-group mb-2">
              <span class="input-group-text l-9" id="basic-addon3">Ville *</span>
              <input type="text" class="form-control" id="ville1" name="ville" value="<?= $ville ?>" placeholder="Ville" readonly>
            </div>
            <div class="input-group mb-2">
              <span class="input-group-text l-9" id="basic-addon3">Code Postal *</span>
              <input type="text" class="form-control" id="cp1" name="cp" value="<?= $cp ?>" placeholder="Code postal" readonly>
            </div>
          </div>
          <div class="input-group mb-2">
            <span class="input-group-text l-9" id="basic-addon3">Portable</span>
            <input type="text" class="form-control" id="portable" name="portable" value="<?= $portable ?>" placeholder="Portable" onkeydown="Portable(this)" onkeypress="Portable(this)" onkeyup="Portable(this)">
          </div>
          <div class="input-group mb-2">
            <span class="input-group-text l-9" id="basic-addon3">Fixe</span>
            <input type="text" class="form-control" id="telephone" name="telephone" value="<?= $telephone ?>" placeholder="Fixe" onkeydown="Fixe(this)" onkeypress="Fixe(this)" onkeyup="Fixe(this)">
          </div>
          <div class="input-group mb-2">
            <span class="input-group-text l-9" id="basic-addon3">Email</span>
            <input type="email" class="form-control" placeholder="adresse@mail.fr" id="email" name="email" value="<?= $email ?>" onkeydown="Email(this)" onkeypress="Email(this)" onkeyup="Email(this)" autocomplete="off">
          </div>
          <!-- <div class="input-group mb-2">
            <span class="input-group-text l-9" id="basic-addon3">Type *</span>
            <select class="form-control" id="type" name="type">
              <option value="">Choisir un type</option>
              <option value="PA">Particulier - Actif</option>
              <option value="PR">Particulier - Retraité</option>
              <option value="EN">Entreprise</option>
              <option value="IN">Intermédiaire</option>
              <option value="FO">Fournisseur</option>
            </select>
          </div> -->

          <p class="small  text-muted">* champ obligatoire</p>
          <div class="text-right">
            <p>
              <input type="hidden" class="form-control" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
              <input type="hidden" class="form-control" name="ajouter" value="<?php echo md5('ajouter'); ?>" />
              <input type="submit" class="btn btn-valider pull-right mt-2" value="Enregistrer le contact" disabled />
              <!-- <button type="reset" class="btn btn-reset pull-right mt-2">Rafraichir</button> -->



            </p>
          </div>
        </form>

      </div>
    </div>
    <div class="col-md-4">
      <p class="titre_menu_item mb-2">Adresses proposées</p>
      <p id="ver"></p>
      <div class="scroll">
        <div class="mt-4 address-feedback position-absolute list-group" style="z-index:1100;margin-top:60px">

        </div>
        <div id="sub-target"></div>
      </div>
    </div>
  </div>
</div>

<script>
  $(function() {

    $('[data-bs-toggle="tooltip"]').tooltip();

    $('.bx').tooltip({
      position: {
        my: 'left',
        at: 'right'
      }
    });
    $('#adresse_auto').tooltip({

      position: {
        my: "center",
        at: "right"
      }

    });
  });
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
        limit: 6,
        autocomplete: 1
      }, function(data, status, xhr) {
        let liste = "";
        $.each(data.features, function(i, obj) {
          console.log(obj.geometry.coordinates + '  ' + obj.properties.context + ' ' + obj.type);
          // données phase 1 (obj.properties.label) & phase 2 : name, postcode, city
          // J'ajoute chaque élément dans une liste
          let cooladdress = "<i class='bx bx-envelope icon-bar titre bx-flxxx'></i> " + obj.properties.name + "</br>" + obj.properties.postcode + " " + obj.properties.city + "</br> " + obj.properties.context + "<strong></strong>";
          liste += '<a class="border-dot mt-1 mb-1 py-2 text-white" href="#" name="' + obj.properties.label + '" data-name="' + obj.properties.name + '" data-postcode="' + obj.properties.postcode + '" data-city="' + obj.properties.city + '">' + cooladdress + '</a>';
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
<script type="text/javascript">
  // $(function() {
  function copyCivilite() {
    var civ = $("select#c_civ option:selected").val();
    $(".civ").val(civ);
  }

  function HighLight(field, error) { // COULEUR
    if (error)
        field.style.borderBottom = "2px solid #dc3545"; // Rouge pour erreur
    else
        field.style.borderBottom = "2px solid #28a745"; // Vert pour valide
}
function Portable(field) { // TELEPHONE
    var regex = /^(0|\+33)[6-7]([0-9]{2}){4}$/;
    if (field.value === '' || regex.test(field.value)) {
        HighLight(field, false);
        return true; // Valide si vide ou correspond au regex
    } else {
        HighLight(field, true);
        return false; // Invalide
    }
}
function Fixe(field) { // TELEPHONE
    var regex = /^(0|\+33)[1-9]([0-9]{2}){4}$/;
    if (field.value === '' || regex.test(field.value)) {
        HighLight(field, false);
        return true; // Valide si vide ou correspond au regex
    } else {
        HighLight(field, true);
        return false; // Invalide
    }
}
function Email(field) { // EMAIL
    var regex = /^((?!\.)[\w_.-]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$/;
    if (field.value === '' || regex.test(field.value)) {
        HighLight(field, false);
        return true; // Valide si vide ou correspond au regex
    } else {
        HighLight(field, true);
        return false; // Invalide
    }
}
function areRequiredFieldsFilled() {
    var requiredFields = [
        document.getElementById('civilite').value,
       // document.getElementById('c_civ').value,
        document.getElementById('nom').value,
        document.getElementById('adresse1').value,
        document.getElementById('ville1').value,
        document.getElementById('cp1').value
    ];
    return requiredFields.every(field => field); // Vérifie si tous les champs sont remplis
}
function validateForm() {
    var isPortableValid = Portable(document.getElementById('portable'));
    var isFixeValid = Fixe(document.getElementById('telephone'));
    var isEmailValid = Email(document.getElementById('email'));
    // Active le bouton de soumission si tous les champs obligatoires sont remplis
    // et que les champs de téléphone et email sont valides ou vides
    document.querySelector('input[type="submit"]').disabled = !(
        areRequiredFieldsFilled() && isPortableValid && isFixeValid && isEmailValid
    );
}
// Ajout des écouteurs d'événements
['change', 'input'].forEach(event => {
   // document.getElementById('c_civ').addEventListener(event, validateForm);
    document.getElementById('civilite').addEventListener(event, validateForm);
    document.getElementById('nom').addEventListener(event, validateForm);
    document.getElementById('adresse1').addEventListener(event, validateForm);
    document.getElementById('portable').addEventListener(event, function() {
        Portable(this);
        validateForm();
    });
    document.getElementById('telephone').addEventListener(event, function() {
        Fixe(this);
        validateForm();
    });
    document.getElementById('email').addEventListener(event, function() {
        Email(this);
        validateForm();
    });
});




  $("#c_civ").change(function() {
    var civ = $('#c_civ').val();
    $('#civilite').addClass("form-control");
    $('#civilite').val(civ);
  });
  // });
</script>