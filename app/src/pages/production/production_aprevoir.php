<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$conn = new connBase();
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
?>
<p class="titre_menu_item mb-4">Production à prévoir</p>


<div class="row">
  <div class="col-md-4">
    <div class="card card-body">
      <form autocomplete="off" id="arealiser">

        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Client </span>
          <input type="text" class="form-control" id="client" value="" onfocus="eff_form('#idcli');eff_form(this);">
          <input type="hidden" id="idcli" name="idcli" value="">
          <input type="hidden" id="nomcli" name="nomcli" value="">
        </div>

        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Travaux</span>
          <select class="form-control" id="stype" name="travaux">
            <option value="">Travaux prévus</option>
            <?php
            $reqtitre = "select * from devistitres where cs='$secteur' and type='MO'and titre like '%ntre%' and type not like ' '  order by titre asc";
            $titre = $conn->allRow($reqtitre);
            foreach ($titre as $t) {
            ?>
              <option value="<?= $t['titre'] ?>"><?= $t['titre'] ?></option>
            <?php
            }
            ?>

          </select>
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Temps</span>
          <select class="form-control" id="stype" name="temps">
            <option value="">Temps de production</option>

            <!-- Pas de 0.5 de 1.00 à 10.00 -->
            <?php for ($i = 1.00; $i <= 10.00; $i += 0.5): ?>
              <option value="<?= number_format($i, 2) ?>"><?= number_format($i, 2) ?></option>
            <?php endfor; ?>

            <!-- Pas de 1.00 de 10.00 à 21.00 -->
            <?php for ($i = 11.00; $i <= 21.00; $i++): ?>
              <option value="<?= number_format($i, 2) ?>"><?= number_format($i, 2) ?></option>
            <?php endfor; ?>


          </select>
        </div>

        <div class="input-group mb-2">
          <span class="input-group-text l-9" id="basic-addon3">Infos libre</span>
          <input type="text" class="form-control" name="infos" value="">

        </div>

        <div class="text-right">
          <p>
            <!-- <input type="hidden" name="idcli" value="<?= $idcli ?>"> -->
            <button type="reset" class="btn btn-mag-n"><i class="bx bx-reset icon-bar"></i></button>
            <input name="Envoyer" type="button" class="btn btn-mag-n text-primary" value="Programmer la production" onclick="ajaxForm('#arealiser', '../src/pages/production/production_aprevoir_bd.php', 'sub-target', 'attente_target');" />
            <input name="validation" id="validation" type="hidden" value="<?php echo md5('ok'); ?>" />
          </p>
        </div>
      </form>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card card-body" id="sub-target"></div>
  </div>
</div>
<script>
  $("#client").autocomplete({
    minLength: 1,
    source: function(request, response) {
      $.ajax({
        url: "../inc/suggest_prod.php",
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
      var nomcli = ui.item.label;
      console.log('id : ' + idcli + ' ' + nomcli)
      $("#idcli").val(idcli);
      $("#nomcli").val(nomcli);
      // ajaxData('idcli=' + idcli + '', 'pages/01-contacts/fiche_contact.php', 'resultat', 'attente_resultat');
    },
    close: function(event, ui) {
      $("#inter").val('');
    },
  });

  function eff_form(t) {
    $(t).val('');
  }
  ajaxData('date=<?= $cd ?>', '../src/pages/production/production_aprevoir_bd.php', 'sub-target', 'attente');
</script>
<?php
//include $chemin . '/inc/foot.php';
?>