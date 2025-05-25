<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
session_start();
$secteur = $_SESSION['idcompte'];

$idcli = $_POST['idcli'];

$conn = new connBase();

$reqchantier = "SELECT
*
FROM
client_chantier
WHERE
idcompte='$secteur'
AND idcli = '$idcli'
LIMIT 1
";
$chantier = $conn->oneRow($reqchantier);
foreach ($chantier as $k => $v) {
  ${$k} = $v;
}

$reqadresse = "SELECT
*
FROM
client_adresse
WHERE
idcompte='$secteur'
AND idcli = '$idcli'
LIMIT 1
";
$adresse = $conn->oneRow($reqadresse);
foreach ($adresse as $k => $v) {
  ${$k} = $v;
}


?>

<p class="titre_menu_item mb-2">Modifier le fiche</p>
<div class="row">
  <div class="col-md-4">
    <p class="text-bold">Adresse chantier</p>
    <form autocomplete="off" id="form_chantier">
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Civilité</span>
        <select class="form-control" id="c_civ" onchange="copyCivilite();">
          <option value="">Choix</option>
          <?php
          $req_civ = "select civilite from client_chantier where civilite !=' ' and idcompte='$secteur' group by civilite order by civilite asc";
          $civilite = $conn->allRow($req_civ);
          foreach ($civilite as $civ) {
            if ($civ['civilite'] == $res['civilite']) {
              $check = "selected=\"selected\"";
            } else {
              $check = "";
            }
          ?>
            <option value="<?php echo $civ['civilite']; ?>" <?php echo $check; ?>><?php echo $civ['civilite']; ?>
            </option>
          <?php } ?>
        </select>
        <input type="text" class="form-control civ" id="civilite" name="civilite" value="" placeholder="Nouvelle">
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Nom</span>
        <input type="text" class="form-control" id="nom" name="nom" value="<?= $chantier['nom'] ?>">
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Prénom</span>
        <input type="text" class="form-control" id="prenom" name="prenom" value="<?= $chantier['prenom'] ?>">
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Adresse <i title="Une adresse normalisée va vous être proposée. Cliquez, et les champs ville et code postal seront renseignés." class='bx bxs-info-circle ml-2 text-primary'></i></span>
        <input type="text" class="form-control" id="adresse1" name="adresse" autocomplete="off" value="<?= $chantier['adresse'] ?>">
        <div class="address-feedback position-absolute list-group" style="z-index:1100;">
        </div>
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Ville</span>
        <input type="text" class="form-control" id="ville1" name="ville" value="<?= $chantier['ville'] ?>" placeholder="Ville" readonly>
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Code Postal</span>
        <input type="text" class="form-control" id="cp1" name="cp" value="<?= $chantier['cp'] ?>" placeholder="Code postal" readonly>
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Portable</span>
        <input type="text" class="form-control" id="portable" name="portable" value="<?= $chantier['portable'] ?>" placeholder="Portable" onkeydown="Portable(this)" onkeypress="Portable(this)" onkeyup="Portable(this)">
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Fixe</span>
        <input type="text" class="form-control" id="telephone" name="telephone" value="<?= $chantier['telephone'] ?>" placeholder="Fixe" onkeydown="Fixe(this)" onkeypress="Fixe(this)" onkeyup="Fixe(this)">
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Email</span>
        <input type="email" class="form-control" placeholder="adresse@mail.fr" value="<?= $chantier['email'] ?>" id="email" name="email" onkeydown="Email(this)" onkeypress="Email(this)" onkeyup="Email(this)" autocomplete="off">
      </div>

      <div class="text-right">
        <p>
          <button type="reset" class="btn btn-mag-n"><i class="bx bx-reset icon-bar"></i></button>
          <input name="Envoyer" type="button" class="btn btn-mag-n text-primary" value="Modifier adresse chantier" onclick="ajaxForm('#form_chantier', 'https://app.enooki.com/src/pages/contacts/contacts_inscription_bd.php', 'sub-target', 'attente_target');" />
          <input name="validation" id="validation" type="hidden" value="<?php echo md5('ok'); ?>" />
        </p>
      </div>
    </form>
  </div>
  <div class="col-md-4">
    <p class="text-bold">Adresse correspondance</p>
    <form autocomplete="off" id="form_adresse">


      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Mention</span>
        <input type="text" class="form-control" id="mention" name="mention" value="<?= $adresse['mention'] ?>">
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Adresse <i title="Une adresse normalisée va vous être proposée. Cliquez, et les champs ville et code postal seront renseignés." class='bx bxs-info-circle ml-2 text-primary'></i></span>
        <input type="text" class="form-control" id="adressecor" name="adresse" autocomplete="off" value="<?= $adresse['adressfact'] ?>">
        <div class="address-feedback position-absolute list-group" style="z-index:1100;">
        </div>
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Ville</span>
        <input type="text" class="form-control" id="villecor" name="ville" value="<?= $adresse['villefact'] ?>" placeholder="Ville" readonly>
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Code Postal</span>
        <input type="text" class="form-control" id="cpcor" name="cp" value="<?= $adresse['cpfact'] ?>" placeholder="Code postal" readonly>
      </div>


      <div class="text-right">
        <p>
          <button type="reset" class="btn btn-mag-n"><i class="bx bx-reset icon-bar"></i></button>
          <input name="Envoyer" type="button" class="btn btn-mag-n text-primary" value="Modifier adresse chantier" onclick="ajaxForm('#form_adresse', 'https://app.enooki.com/src/pages/contacts/contacts_inscription_bd.php', 'sub-target', 'attente_target');" />
          <input name="validation" id="validation" type="hidden" value="<?php echo md5('ok'); ?>" />
        </p>
      </div>
    </form>
  </div>
  <div class="col-md-4">
    <p class="text-bold">Infos</p>
    <form autocomplete="off" id="form_infos">



      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Type</span>
        <select class="form-control" id="type" name="type">
          <option value="">Choisir un type</option>
          <option value="PA">Particulier - Actif</option>
          <option value="PR">Particulier - Retraité</option>
          <option value="EN">Entreprise</option>
          <option value="IN">Intermédiaire</option>
        </select>
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Commercial</span>
        <select class="form-control" id="commercial" name="commercial">
          <option value="">Commercial</option>
          <?php $req = "select * from users where idcompte='" . $secteur . "' and actif='1' order by nom ";
          $rep = $conn->allRow($req);
          foreach ($rep as $r) { ?>
            <option value="<?php echo $r['idusers'] ?>"><?php echo ($r['nom'] . " " . $r['prenom'] . ' - n°' . $r['idusers']); ?> </option>
          <?php  } ?>
        </select>
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Intervenant</span>
        <select class="form-control" id="tournee" name="tournee">
          <option value="">Intervenant</option>
          <?php $reqa = "select * from users where idcompte='" . $secteur . "' and actif='1' order by nom ";
          $repa = $conn->allRow($reqa);
          foreach ($repa as $ra) { ?>
            <option value="<?php echo $ra['idusers'] ?>"><?php echo ($ra['nom'] . " " . $ra['prenom'] . ' - n°' . $ra['idusers']); ?>
            </option>
          <?php  } ?>
        </select>
      </div>
      <div class="text-right">
        <p>
          <button type="reset" class="btn btn-mag-n"><i class="bx bx-reset icon-bar"></i></button>
          <input name="Envoyer" type="button" class="btn btn-mag-n text-primary" value="Enregistrer les infos" onclick="ajaxForm('#form_infos', 'https://app.enooki.com/src/pages/contacts/contacts_inscription_bd.php', 'sub-target', 'attente_target');" />
          <input name="validation" id="validation" type="hidden" value="<?php echo md5('ok'); ?>" />
        </p>
      </div>
    </form>
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