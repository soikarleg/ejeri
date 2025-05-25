<title>enooki - <?= $title ?></title>
<div class="container pt-5">
  <div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4 text-center">
      <img class="mb-4 p-1 rounded" style="background:#1b1b1b" src="../assets/img/enooki_jardins_blanc.png" alt="" width="75%">
      <h3 class="text-primary mb-3">Renvoi du lien de validation</h3>
      <p class="small text-muted mb-3">Vous allez recevoir un mail de vérification.</p>
      <form autocomplete="off" action="/" method="post">
        <div class="mb-2">
          <input type="email" class="form-control" id="email" name="email_renvoi" placeholder="Email" onkeydown="Email(this)" onkeypress="Email(this)" onkeyup="Email(this)" autocomplete="off">
          <input type="hidden" name="renvoi" value="renvoi">
        </div>
        <div class="mb-4">
          <input type="submit" class="form-control" value="Réinitialiser">
        </div>
      </form>
      <a class="text-muted pointer" href="/">Je me souviens de mon mot de passe</a>
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

  function Email(field) { //EMAIL
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