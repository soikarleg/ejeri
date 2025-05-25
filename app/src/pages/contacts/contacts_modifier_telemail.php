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

$param = ['idcompte' => $secteur, 'idcli' => $idcli];
$reqtelemail = "SELECT * FROM client WHERE idcompte=:idcompte AND idcli = :idcli LIMIT 1 ";
$telemail = $conn->oneRow($reqtelemail, $param);
foreach ($telemail as $k => $v) {
  ${$k} = $v;
}

// $reqadresse = "SELECT
// *
// FROM
// client_adresse
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
    <p class="titre_menu_item mb-2">Modifier les données de contacts</p>
    <form autocomplete="off" id="form_chantier">
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Portable <i title="Numéro de téléphone sans les points (.)" class='bx bxs-info-circle ml-2 text-primary'></i></span>
        <input type="text" class="form-control" id="portable" name="portable" value="<?= supprimePointTelephone($telemail['portable']) ?>" placeholder="Portable" onkeydown="Portable(this)" onkeypress="Portable(this)" onkeyup="Portable(this)">
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Fixe</span>
        <input type="text" class="form-control" id="telephone" name="telephone" value="<?= supprimePointTelephone($telemail['telephone']) ?>" placeholder="Fixe" onkeydown="Fixe(this)" onkeypress="Fixe(this)" onkeyup="Fixe(this)">
      </div>
      <div class="input-group mb-2">
        <span class="input-group-text l-9" id="basic-addon3">Email</span>
        <input type="email" class="form-control" placeholder="adresse@mail.fr" value="<?= $telemail['email'] ?>" id="email" name="email" onkeydown="Email(this)" onkeypress="Email(this)" onkeyup="Email(this)" autocomplete="off">
      </div>

      <div class="text-right">
        <p>
          <input type="hidden" class="form-control" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
          <input type="hidden" class="form-control" name="modifier_telemail" value="<?php echo md5('modifier_telemail'); ?>" />
         
        <div class="pull-right" style="display: flex; justify-content: flex-end; gap: 10px;">
          <a class="btn btn-annuler mt-2 text-center" href="/fiche_client?idcli=<?= $idcli ?>">Annuler</a>
         
          <input type="submit" class="btn btn-valider pull-right mt-2" value="Modifier les données de contact" />

        </div>
        <input type="hidden" name="idcli" id="idcli" value="<?= $idcli ?>">
        </p>
      </div>
    </form>
  </div>
  <div class="col-md-6">
    <div id="sub-target"></div>
  </div>
</div>
<script>
  $(function() {
    $('.bx').tooltip();
  });
</script>
<script type="text/javascript">
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
</script>