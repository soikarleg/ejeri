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

$phrases = $devis->getPhrases(" and type='DEV'");
?>

<?php
foreach ($_POST as $key => $value) {
  ${$key} = $ins->valFullsansSlashes(stripslashes($value));
  //echo '$' . $key . ' = ' . $value . '<br>';

}

//echo $text . '<br>';


foreach ($phrases as $p) {
  $designation = $p['designation'];

  if ($text === $designation) {
    // echo $text . ' = ' . $designation . '<br>';
    $res = "same";
  }
}

//echo $res;

if ($res === "same") {
  echo "<i class='bx bxs-save text-danger icon-bar'></i>";
?>
  <script>
    pushDoublons('Blocage doublon', 'La phrases existe déjà : ', '<?=$text?>');
  </script>
<?php


} else {

  echo "<i class='bx bxs-save text-success icon-bar'></i>";
  $text = addslashes($text);
  $input_phrases = "insert into phrases (cs,type,designation) values ('$secteur','DEV','$text')";
  $conn->handleRow($input_phrases);
?>
  <script>
    pushSauvegarde('Sauvegarde effectué', 'Phrases enregistrée : ','<?=$text?>');
  </script>
<?php
}

?>