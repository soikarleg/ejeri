<?php
session_start();
$titre_page = 'Erreur';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  <!----======== CSS ======== -->
  <link rel="stylesheet" href="../assets/css/jquery.css?=<?= time(); ?>">
  <link rel="stylesheet" href="../assets/css/iconoir.css?=<?= time(); ?>">
  <link rel="stylesheet" href="../assets/css/bootstrap.css?=<?= time(); ?>">
  <link rel="stylesheet" href="../assets/css/style.css?=<?= time(); ?>">
  <!----===== Boxicons CSS ===== -->
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <!----===== JS ===== -->

  <title><?= 'enooki - ' . $titre_page ?></title>
</head>
<?php
foreach ($_COOKIE as $k => $v) {
  ${$k} = $v;
  // echo '$'.$k.' = '.$v.'</br>';
}
if ($mode == 'nuit') {
  $class = 'class="dark"';
} else {
  $class = '';
}
if ($sidebar == 'close') {
  $close = 'close';
} else {
  $close = '';
}
?>

<body <?= $class ?>>
  <section id="home">
    <div id="resultat">
      <div class="container-fluid">
        <div class="container text-center ">
          <div class="row mt-5">
            <div class="col-sm-4"></div>
            <div class="col-sm-4 text-center">
              <!-- <p>Ce n'est pas une Peugeot, c'est une erreur...</p>
              <img src="../assets/img/404_trans.png" alt="404" width="80%" class="text-center smenu"> -->
              <p class="err">401</p>
              <p>Pas de session en cours</p>

              <a class="btn btn-mag text-center mt-2" href=" https://sagaas.fr">Retour au site</a>
              <a class="btn btn-mag text-center mt-2" href=" https://app.enooki.com">Retour a l'application</a>
            </div>
            <div class="col-sm-4 "></div>
          </div>
        </div>
      </div>

    </div>
    <div class="container">
    </div>
  </section>
  <?php
  //require_once('parametre.php');
  // require_once('footer.php');
  ?>