<?php
$auth = new Delight\Auth\Auth($db);
$conn = new connBase();
$erreur = !isset($erreur) ? '' : $erreur;
$token = $_GET['token'] ?? '';
$selector = $_GET['selector'] ?? '';
?>
<title>enooki - <?= $title ?></title>
<div class="container pt-5">
  <div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4 text-center">
      <img class="mb-4 p-1 rounded" style="background:#1b1b1b" src="../assets/img/enooki_jardins_blanc.png" alt="" width="75%">
      <h3 class="text-primary mb-3">Réinitialisation</h3>
      <form autocomplete="off" action="/" method="post" id="forgot">
        <div class="input-group mb-2">
          <input type="password" class="form-control" id="mypass" name="new_password" placeholder="Nouveau mot de passe">
          <span id="mytog" onclick="togglePassword('mypass', 'mytog')"><i class='bx bx-show icon-bar mt-2 ml-2 pointer absplus text-primary bx-flxxx'></i></span>
          <span><i title="Au moins 12 caratères. Majuscules, munuscules, chiffres et caratères spéciaux." class='bx bx-info-circle bx-flxxx icon-bar mt-2 ml-2 pointer abs text-primary'></i></span>
        </div>
        <div class="input-group mb-2">
          <input type="password" class="form-control" id="mypass_conf" name="new_password_conf" placeholder="Confirmez le mot de passe" autocomplete="off">
          <span id="mytog_conf" onclick="togglePassword('mypass_conf', 'mytog_conf')"><i class='bx bx-show icon-bar mt-2 ml-2 pointer absplus text-primary bx-flxxx'></i></span>
          <span><i title="Confirmez votre nouveau mot de passe." class='bx bx-info-circle bx-flxxx icon-bar mt-2 ml-2 pointer abs text-primary'></i></span>
        </div>
        <input type="hidden" name="token" value="<?= $token ?>">
        <input type="hidden" name="selector" value="<?= $selector ?>">
        <input type="hidden" name="new_mdp" id="new_mdp" value="">
        <div class="mb-4">
          <input type="submit" class="form-control" value="Réinitialiser">
        </div>
      </form>
      <a href="/" class="text-muted pointer">Retour à la connexion</a>
      <?php
      if ($erreur) {
      ?>
        <p class="mb-4 text-center text-<?= $code ?>"><?= $erreur ?></p>
        <a href="/" class="text-muted pointer">Retour à la connexion</a>
      <?php
      }
      ?>
    </div>
    <div class="col-md-4"></div>
  </div>
</div>
<script>
  function HighLight(field, error) { //COULEUR
    if (error)
      field.style.borderBottom = "2px solid #dc3545";
    else
      field.style.borderBottom = "2px solid #28a745";
  };
  //$(function() {
  $('#mypass, #mypass_conf').on('input', function() {
    let p1 = $('#mypass');
    let p2 = $('#mypass_conf');
    var password = $(this).val();
    if (password.length >= 12 &&
      /[A-Z]/.test(password) &&
      /[a-z]/.test(password) &&
      /\d/.test(password) &&
      /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/.test(password) &&
      p1.val() == p2.val()
    ) {
      $(this).css('border-bottom', '2px solid #28a745');
      p1.css('border-bottom', '2px solid #28a745');
      p2.css('border-bottom', '2px solid #28a745');
    } else {
      $(this).css('border-bottom', '2px solid #dc3545');
      p1.css('border-bottom', '2px solid #dc3545');
      p2.css('border-bottom', '2px solid #dc3545');
    }
  });
  $('#password').on('input', function() {
    let password = $(this).val();
    let new_mdp = $('#new_mdp');
    new_mdp.val(password);
  });
  //});
  $(function() {
    $('.bx').tooltip();
  });
</script>