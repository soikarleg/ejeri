<?php
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
?>
<title>enooki - <?= $title ?></title>
<div class="container pt-5">
  <div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4 text-center" id="mark">
      <img class="mb-4 p-1 rounded" style="background:#1b1b1b" src="../assets/img/enooki_jardins_blanc.png" alt="" width="75%">
      <h3 class="text-primary mb-3">Créer votre compte intervenant</h3>
      <!-- <p class="mb-3">30 jours d'essai gratuit</p> -->
      <form autocomplete="off" action="https://app.enooki.com/" method="post">
        <div class="mb-2">
          <input type="email" class="form-control" id="email" name="email_signup" placeholder="Email" onkeydown="Email(this)" onkeypress="Email(this)" onkeyup="Email(this)" autocomplete="off">
        </div>

        <div class="input-group mb-2">
          <input type="text" class="form-control" id="username" name="username_signup" placeholder="Identifiant" autocomplete="off">
          <div id="infos" class="small text-center"></div>
          <span><i title="Pas d'accents et plus de 4 caratères" class='bx bx-info-circle icon-bar mt-2 ml-2 pointer abs text-primary bx-flxxx'></i></span>
        </div>

        <div class="input-group mb-2">
          <input type="password" class="form-control " id="password" name="password_signup" placeholder="Mot de passe">
          <span id="mytog" onclick="togglePassword('password', 'mytog')"><i class='bx bx-show icon-bar mt-2 ml-2 pointer absplus text-primary bx-flxxx'></i></span>

          <span><i title="Au moins 12 caratères. Majuscules, munuscules, chiffres et caratères spéciaux." class='bx bx-info-circle bx-flxxx icon-bar mt-2 ml-2 pointer abs text-primary'></i></span>

        </div>
        <div class="form-check">
          <input class="check" type="checkbox" value="1" name="cgv" id="cgvval">
          <label class="form-check-label small" for="cgvval">
            J'ai pris connaissance des <a href="/cgv" class="">Conditions Générales de Vente</a>
          </label>
        </div>
        <script>
          function HighLight(field, error) { // COULEUR
            if (error)
              field.style.borderBottom = "2px solid #dc3545";
            else
              field.style.borderBottom = "2px solid #28a745";
          }

          function Email(field) { // EMAIL
            var regex = /^((?!\.)[\w_.-]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$/;
            if (!regex.test(field.value)) {
              HighLight(field, true);
              return false;
            } else {
              HighLight(field, false);
              return true;
            }
          }

          function Username(field) { // USERNAME
            var regex = /[\x00-\x1f\x7f\/:\\\\]/;
            return !regex.test(field);
          }

          function cleanString(input) { // Supprimer les caractères spéciaux et les accents
            var cleaned = input.replace(/[^\w\s]|_/g, "")
              .replace(/\s+/g, " ")
              .replace(/[àáâãäå]/g, "a")
              .replace(/[èéêë]/g, "e")
              .replace(/[ìíîï]/g, "i")
              .replace(/[òóôõö]/g, "o")
              .replace(/[ùúûü]/g, "u")
              .replace(/[ýÿ]/g, "y")
              .replace(/[ñ]/g, "n")
              .replace(/[ç]/g, "c")
              .toLowerCase();
            return cleaned;
          }

          $(document).ready(function() {
            const submitButton = $('#submit'); // Bouton "Créer un compte"
            const termsCheckbox = $('#cgvval'); // Case à cocher CGV

            function validateForm() {
              const emailValid = Email(document.getElementById('email'));
              const usernameValid = Username($('#username').val());
              const passwordValid = $('#password').val().length >= 12 &&
                /[A-Z]/.test($('#password').val()) &&
                /[a-z]/.test($('#password').val()) &&
                /\d/.test($('#password').val()) &&
                /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/.test($('#password').val());
              const termsChecked = termsCheckbox.is(':checked');

              // Activer/désactiver le bouton selon les validations
              submitButton.prop('disabled', !(emailValid && usernameValid && passwordValid && termsChecked));
            }

            $('#email, #username, #password').on('input', validateForm);
            termsCheckbox.on('change', validateForm);

            $('#username').on('input', function() {
              var username = $(this).val();
              if (username.length > 3) {
                $.ajax({
                  url: 'https://app.enooki.com/src/menus/validation_username.php', // URL de votre script PHP pour la validation
                  method: 'POST',
                  data: {
                    username: username
                  },
                  success: function(response) {
                    if (response === 'true') {
                      $('#infos').html('Cet indentifiant existe déjà');
                      $('#infos').removeClass('text-success');
                      $('#infos').removeClass('text-primary');
                      $('#infos').addClass('mt-signup text-danger absplus');
                      $('#username').css('border-bottom', '2px solid #dc3545');
                    } else {
                      var propre = cleanString(username);
                      $('#username').val(propre);
                      $('#infos').html('Identifiant disponible');
                      $('#infos').removeClass('text-danger');
                      $('#infos').removeClass('text-primary');
                      $('#infos').addClass('mt-signup text-success absplus');
                      $('#username').css('border-bottom', '2px solid #28a745');
                    }
                    validateForm(); // Re-valider le formulaire après la réponse AJAX
                  }
                });
              } else {
                $('#infos').html('Identifiant trop court');
                $('#infos').removeClass('text-success');
                $('#infos').removeClass('text-danger');
                $('#infos').addClass('mt-signup text-primary absplus');
                $(this).css('border-bottom', '0px solid #dc3545');
              }
            });

            $('#toggleBtn').on('click', function() {
              var passwordInput = $('#password');
              var currentType = passwordInput.attr('type');
              if (currentType === 'password') {
                passwordInput.attr('type', 'text');
                $('#toggleBtn').html("<i class='bx bx-hide icon-bar bx-flxxx mt-2 ml-2 pointer absplus text-warning'>");
              } else {
                passwordInput.attr('type', 'password');
                $('#toggleBtn').html("<i class='bx bx-show icon-bar bx-flxxx mt-2 ml-2 pointer absplus text-primary'>");
              }
            });

            $('#password').on('input', function() {
              var password = $(this).val();
              if (password.length >= 12 &&
                /[A-Z]/.test(password) &&
                /[a-z]/.test(password) &&
                /\d/.test(password) &&
                /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/.test(password)
              ) {
                $(this).css('border-bottom', '2px solid #28a745');
              } else {
                $(this).css('border-bottom', '2px solid #dc3545');
              }
              validateForm(); // Re-valider le formulaire
            });
          });
        </script>
        <div class="mt-2 mb-4">
          <input type="submit" class="form-control" value="Créez votre compte" id="submit" disabled="disabled">
        </div>
      </form>
      <!-- <span class="text-muted pointer" onclick="ajaxData('cs='.secteur, '../src/menus/forgotpass.php', 'target-one', 'attente');">Mot de passe perdu</span>
      /-->
      <a class="text-muted pointer" href="/">J'ai déjà un compte</a>

    </div>
    <div class="col-md-4"></div>
  </div>
</div>
<script>
  $(function() {
    $('.bx').tooltip();
  });
</script>