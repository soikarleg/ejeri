<?php
foreach ($_COOKIE as $k => $v) {
  ${$k} = $v;
  //echo '$' . $k . ' = ' . $v . '</br>';
}

$mode = !$_COOKIE['mode'] ? $mode = 'nuit' : $_COOKIE['mode'];

if ($_COOKIE) {
  $mode = $_COOKIE['mode'];
} else {
  $mode = 'nuit';
}
if ($mode == 'nuit') {
  $class = 'class="dark fond"';
} else {
  $class = 'class="dark fond"';
}
?>
<!DOCTYPE html>
<html lang="fr" <?= $class ?>>

<head>
  <meta charset="UTF-8">
  <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../assets/img/enooki_ico_blanc.png" type="image/x-icon">
  <!-- Inclusion de la bibliothèque jQuery -->
  <script src="https://app.enooki.com/assets/js/jquery_3.6.js"></script>

  <link rel="stylesheet" href="https://app.enooki.com/assets/css/jquery.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://app.enooki.com/assets/css/bootstrap.css">
  <link rel="stylesheet" href="https://app.enooki.com/assets/css/richtext.min.css">
  <link rel="stylesheet" href="https://app.enooki.com/assets/css/style.css?time=<? time() ?>">
  <link rel="stylesheet" href="https://app.enooki.com/assets/boxicons/css/boxicons.css">
  <link rel="stylesheet" href="https://app.enooki.com/assets/boxicons/css/animations.css">
  <link rel="stylesheet" href="https://app.enooki.com/assets/boxicons/css/transformations.css">
  <link href="assets/css/enooki.css" rel="stylesheet">
  <!-- <link rel="stylesheet" href="https://app.enooki.com/assets/css/magesquo.css"> -->
  <!--<link rel="stylesheet" href="https://app.enooki.com/assets/css/tw/output.css"> -->
  <!-- <link rel="stylesheet" href="https://app.enooki.com/public/landing/assets/css/main.css"> -->
  <!-- <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> -->
  <!----======== JS ======== -->


  <script src="https://app.enooki.com/assets/js/popper.js"></script>
  <script src="https://app.enooki.com/assets/js/bootstrap.js"></script>
  <script src="https://app.enooki.com/assets/js/jquery.js"></script>
  <script src="https://app.enooki.com/assets/js/jquery_ui.js"></script>
  <script src="https://app.enooki.com/assets/js/hash_md5.js"></script>
  <script src="https://app.enooki.com/assets/js/script.js?time=<? time() ?>"></script>
  <script src="https://app.enooki.com/assets/js/moment.js"></script>
  <script src="https://app.enooki.com/assets/js/touch.js"></script>
  <script src="https://app.enooki.com/assets/js/jquery.richtext.js"></script>
  <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/simple-notify@0.5.5/dist/simple-notify.min.js"></script>
  <!-- <script src="https://app.enooki.com/assets/js/notify.js?time=<? time() ?>"></script> -->
  <script src='https://app.enooki.com/assets/js/index.global.js'></script>
  <script src='https://app.enooki.com/assets/js/fr.global.js'></script>
  <script src='https://app.enooki.com/assets/js/fullcalendar.js?time=<? time() ?>'></script>
  <!-- <script src="https://cdn.tailwindcss.com"></script> -->



  <script type="text/javascript">
    window.onload = function() {
      // Masquer l'overlay lorsque la page est complètement chargée
      document.getElementById('myoverlay').style.display = 'none';
      // Afficher le contenu de la page
      document.getElementById('mycontent').style.display = 'block';
    };

    window.$crisp = [];
    $crisp.push(["config", "color:theme", ["#3d76ad"]]);
    $crisp.push(["config", "color:mode", ["dark"]]);

    window.CRISP_WEBSITE_ID = "f0b8d099-ba8a-426a-80c0-3f61dea5e4a0";
    (function() {
      d = document;
      s = d.createElement("script");
      s.src = "https://client.crisp.chat/l.js";
      s.async = 1;
      d.getElementsByTagName("head")[0].appendChild(s);
    })();
  </script>

</head>

<body <?= $class ?>>