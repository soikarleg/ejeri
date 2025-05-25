<title>enooki - <?= $title ?></title>
<div class="container pt-5">
  <div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4 text-center">
      <img class="mb-4 p-1 rounded" style="background:#1b1b1b" src="../assets/img/enooki_jardins_blanc.png" alt="" width="75%">
      <h3 class="text-primary mb-3">Application de gestion</h3>
      <div class="">
        <form autocomplete="off" action="/connexion" method="post">
          <div class="mb-2">
            <input type="text" class="form-control" id="username_log" name="username" placeholder="Identifiant">
          </div>
          <div class="mb-2">
            <input type="password" class="form-control" id="password_log" name="password" placeholder="Mot de passe">
            <!-- <span id="mytog" onclick="togglePassword('mypass', 'mytog')"><i class='bx bx-show icon-bar mt-2 ml-2 pointer absplus text-primary bx-flxxx'></i></span> -->
          </div>
          <div class="mb-2">
            <input type="submit" value="Connexion" class="btn btn-mag text-center">
          </div>
        </form>
        <a class="text-muted pointer" href="/mdp_oubli">J'ai perdu mon mot de passe</a>
        /
        <a class="text-muted pointer" href="/signup">Je n'ai pas de compte</a>
        <p class="mt-4 text-<?= $code ?> "><?= $erreur  ?></p>
      </div>
    </div>
    <div class="col-md-4">
    </div>
  </div>
</div>