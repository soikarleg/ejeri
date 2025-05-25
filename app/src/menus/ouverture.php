<?php


?><div class="container mt-4">
  <div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4 text-center">

      <img class="mb-4 p-1 rounded" style="background:#1b1b1b" src="https://sagaas.fr/public/assets/images/logo_m.png" alt="" width="80%">
      <h3 class="text-primary mb-3">Compte ouvert</h3>
      <!-- <div class="border-dot mb-2">
        <h4 class="text-primary mb-3">MaGESQUO</h4>
        <p>Application de gestion quotidienne des activités de prestations de services</p>
        <p class="small text-muted">Clients - Devis - Productions - Factures - Règlements - Dépenses</p>
        <a href="https://sagaas.fr/public/landing/index.php" class="btn btn-primary mt-4 text-white">Voir les fonctionnalités</a>
      </div> -->
      <div class="">

        <!-- <h4 class="text-primary mb-3">Connexion</h4>
        <form autocomplete="off" action="https://app.enooki.com" method="post">
          <div class="mb-2">
            <input type="text" class="form-control" id="username" name="username" placeholder="Identifiant">
          </div>
          <div class="mb-2">
            <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe">
          </div>
          <div class="mb-2">
            <button class="btn btn-mag text-center">Connexion</button>
          </div>
        </form>
        <span class="text-muted pointer" onclick="ajaxData('cs='.secteur, '../src/menus/forgotpass.php', 'target-one', 'attente-target');">Mot de passe perdu</span>
        /
        <span class="text-muted pointer" onclick="ajaxData('cs='.secteur, '../src/menus/signup.php', 'target-one', 'attente-target');">Inscription</span>-->




        <?php if ($_POST['erreur']) {

          $code = $_POST['code'];
        ?>
          <p class="text-<?= $code ?>  mt-4"><?= $_POST['erreur'] ?></p>
        <?php
        }    ?>

        <?php if ($_GET['erreur']) {

          $code = $_GET['code'];
        ?>
          <p class="text-<?= $code ?>  mt-4"><?= $_GET['erreur'] ?></p>
        <?php
        }    ?>

      </div>
    </div>
    <div class="col-md-4">

    </div>
  </div>
</div>
<script>


</script>