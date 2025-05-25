<?php
session_start();
$secteur = $_SESSION['idcompte'];
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$data = $_POST;
$conn = new connBase();
$ins = new FormValidation($data);
$devis = new Devis($secteur);


//var_dump($_POST);

if ($_POST['insert'] === "ok") {
  $phrase = $ins->valAdresse($_POST['phrase']);
  //$phrase = addslashes($phrase);
  $input_phrases = "insert into phrases (cs,type,designation) values ('$secteur','DEV','$phrase')";
  $conn->handleRow($input_phrases);
}

if ($_POST['delete'] === "ok") {
  $numero = $_POST['numero'];
  $delete_phrases = "delete from phrases where numero='$numero'";
  $conn->handleRow($delete_phrases);
}

$phrases = $devis->getPhrases(" and type = 'DEV' ");
?>
<p class="pull-right text-warning" onclick="ajaxData('cs=cs', '../src/pages/devis/devis_garde.php', 'action', 'attente_target');$('#action').removeClass('rel');"><i class='bx bxs-chevron-left bx-flxxx icon-bar text-bold text-white bx-md pointer bx-close'></i></p>
<p class="titre_menu_item mb-2">Phrases des devis <?= $alert ?></p>

<div class="row">
  <div class="col-md-12">
    <?php

    ?>

    <div class="border-dot mb-4" ;>
      <p class="text-bold mb-2">Ajouter une phrase</p>
      <form action="" id="phrases">

        <input type="hidden" name="insert" value="ok">
        <div class="input-group mb-2">
          <!-- <span class="input-group-text l-9">Designation </span> -->

          <input type="text" class="form-control" placeholder="Votre phrase..." name="phrase" value="">
        </div>
        <div class="text-right">
          <button type="reset" class="btn btn-mag-n"><i class="bx bx-reset icon-bar"></i></button>
          <input name="Envoyer" type="button" class="btn btn-mag-n text-primary" value="Enregistrer" onclick="ajaxForm('#phrases', '../src/pages/devis/devis_phrases.php', 'action', 'attente_target');" />
        </div>
      </form>

    </div>
  </div>
</div>

<div class="scroll-m">
  <div class="">

    <?php


    foreach ($phrases as $p) {

      foreach ($p as $key => $value) {
        ${$key} = $value;
        '$' . $key . ' = ' . $value . '<br>';
      }

    ?>
      <p class="border-dot mb-2"><i class='bx bxs-x-circle text-danger pointer icon-bar pull-right' onclick="ajaxData('delete=ok&numero=<?= $numero ?>', '../src/pages/devis/devis_phrases.php', 'action', 'attente_target');"></i><?= $numero . ' - ' . $designation ?></p>
      <!-- <div class="col-md-4">

        <div class="border-dot mb-4" style="height: 200px" ;>
          <p class="text-bold mb-2">Modifier la phrases<span class="puce pull-right mb-2"><?= 'N° ' . $numero  ?></span></p>
          <form action="" id="f<?= $numero ?>">
            <input type="hidden" name="modification" value="ok">
            <input type="hidden" class="form-control" value="<?= $numero ?>" name="numero_phrases">
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="2"><?= $designation ?></textarea>
            <div class="text-right">
              <button type="reset" class="btn btn-mag-n"><i class="bx bx-reset icon-bar"></i></button>
              <input name="Envoyer" type="button" class="btn btn-mag-n text-primary" value="Modifier N° <?= $numero ?>" onclick="ajaxForm('#f<?= $numero ?>', '../src/pages/devis/devis_phrases.php', 'sub-target', 'attente_target');" />
            </div>
          </form>
        </div>
      </div> -->

    <?php

    }
    ?>
  </div>
</div>