<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
$secteur = $_SESSION['idcompte'];


foreach ($_GET as $k => $v) {
  ${$k} = $v;
  //echo '$' . $k . '= ' . $v . '<br class=""> ';
};

$conn = new Magesquo($secteur);

$param = ['idcompte' => $idcompte, 'idcli' => $idcli];
$reqchantier = "SELECT * FROM client_correspondance WHERE idcompte=:idcompte AND idcli = :idcli LIMIT 1";
$adresse = $conn->oneRow($reqchantier, $param);
foreach ($chantier as $k => $v) {
  ${$k} = $v;
}

// $reqadresse = "SELECT
// *
// FROM
// client_correspondance
// WHERE
// idcompte='$secteur'
// AND idcli = '$idcli'
// LIMIT 1
// ";
// $adresse = $conn->oneRow($reqadresse);
// foreach ($adresse as $k => $v) {
//   ${$k} = $v;
// }


?>


<div class="row">
  <div class="col-md-2"></div>
  <div class="col-md-4">
    <p class="titre_menu_item mb-2">Modifier l'adresse de correspondance</p>
    <form autocomplete="off" id="form_adresse">


      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Mention</span>
        <input type="text" class="form-control" id="mention" name="mention" value="<?= $adresse['mention'] ?>">
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Adresse <i title="Une adresse normalisée va vous être proposée. Cliquez, et les champs ville et code postal seront renseignés." class='bx bxs-info-circle ml-2 text-primary'></i></span>
        <input type="text" class="form-control" id="adresse1" name="adresse" autocomplete="off" value="<?= $adresse['adresse'] ?>">

      </div>
      <div class="">
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Ville</span>
          <input type="text" class="form-control" id="ville1" name="ville" value="<?= $adresse['ville'] ?>" placeholder="Ville" readonly>
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Code Postal</span>
          <input type="text" class="form-control" id="cp1" name="cp" value="<?= $adresse['cp'] ?>" placeholder="Code postal" readonly>
        </div>
      </div>

      <div class="text-right">
        <p>
          <input type="hidden" class="form-control" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
          <input type="hidden" class="form-control" name="ajouter" value="<?php echo md5('modifier_correspondance'); ?>" />
         
          <div class="pull-right" style="display: flex; justify-content: flex-end; gap: 10px;">
          <a class="btn btn-annuler mt-2 text-center" href="/fiche_client?idcli=<?= $idcli ?>">Annuler</a>
          <input type="submit" class="btn btn-valider pull-right mt-2" value="Modifier l'adresse de correspondance" />
          
        </div>

          <input type="hidden" name="idcli" id="idcli" value="<?= $idcli ?>">
        </p>
      </div>
    </form>
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
  <script>
    $(function() {
      $('.bx').tooltip();
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
          limit: 2,
          autocomplete: 1
        }, function(data, status, xhr) {
          let liste = "";
          $.each(data.features, function(i, obj) {
            // données phase 1 (obj.properties.label) & phase 2 : name, postcode, city
            // J'ajoute chaque élément dans une liste
            let cooladdress = "<i class='bx bx-envelope icon-bar titre'></i> " + obj.properties.name + "</br>" + obj.properties.postcode + " <strong>" + obj.properties.city + "</strong>";
            liste += '<a class="border-fiche mb-2" href="#" name="' + obj.properties.label + '" data-name="' + obj.properties.name + '" data-postcode="' + obj.properties.postcode + '" data-city="' + obj.properties.city + '">' + cooladdress + '</a>';
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

    function HighLight(field, error) { //COULEUR
      if (error)
        field.style.borderBottom = "4px solid #dc3545";
      else
        field.style.borderBottom = "4px solid #28a745";
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
    $("#c_civ").change(function() {
      var civ = $('#c_civ').val();
      $('#civilite').addClass("form-control");
      $('#civilite').val(civ);
    });
    // });
  </script>