<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
foreach ($_POST as $k => $v) {
  ${$k} = $v;
  //echo '$' . $k . ' = ' . $v . '</br>';
}
$conn = new connBase();
$prod = new Production($secteur);


if ($immat != null) {
  $curl = curl_init();

  curl_setopt_array($curl, [
    CURLOPT_URL => "https://api-siv-systeme-d-immatriculation-des-vehicules.p.rapidapi.com/$immat",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
      "x-rapidapi-host: api-siv-systeme-d-immatriculation-des-vehicules.p.rapidapi.com",
      "x-rapidapi-key: deb175f49dmsh447a80ac79a5a2fp10fbc5jsnef57fa3de9c0"
    ],
  ]);

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    echo $response;
  }
} else {
  echo "Pas d'immatriculation";
}

?>
<p class="text-bold">Immatriculation API </p>
<p><?= $immat ?></p>
<form action="" id="fimmat">
  <input type="text" class="form-control l-9" value="" name="immat" placeholder="AA-000-YY">
  <input type="hidden" name="test" value="test">
  <input id="val" name="envoyer" type="button" class="btn btn-mag-n text-primary" value="Infos sur l'immat" onclick="ajaxForm('#fimmat', '../src/pages/production/production_immat.php', 'action', 'attente_target');">
</form>
<?php
