<?php
session_start();
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$chemin = $_SERVER['DOCUMENT_ROOT'];

//require $chemin.'/inc/function.php';


$user = new Bugs($iduser);

// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
//include $chemin . '/inc/error.php';

foreach ($_GET as $key => $value) {
    ${$key} = ($value);
    //echo '$' . $key . ' = ' . $value . '<br>';
}

foreach ($_SESSION as $key => $value) {
    ${$key} = ($value);
    //echo '$' . $key . ' = ' . $value . '<br>';
}



$mybugs = $user->askBugs();
// pretty($mybugs);
// var_dump($mybugs);





if ($page != '') {

?><form action="/bugs_ajouter" method="get"></form>
    <input type="text" class="form-control l-12" name="page" value="<?= $page ?>" readonly>
    <input type="text" class="form-control l-12" name="idcompte" value="<?= $idcompte ?>" readonly>
    <input type="submit" value="Ajouter le bug">
    </form>
    <a href="https://app.enooki.com<?= $page ?>">Retour à <?= $page ?></a>
<?php
}
?>
<p>Resolu</p>