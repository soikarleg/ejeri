<?php
session_start();
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
$chemin = $_SERVER['DOCUMENT_ROOT'];

//require $chemin.'/inc/function.php';


$bugs = new Bugs($iduser);

// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
//include $chemin . '/inc/error.php';

foreach ($_GET as $key => $value) {
    ${$key} = ($value);
    echo '$' . $key . ' = ' . $value . '<br>';
}

foreach ($_SESSION as $key => $value) {
    ${$key} = ($value);
    //echo '$' . $key . ' = ' . $value . '<br>';
}



$mybugs = $bugs->askBugs();
// pretty($mybugs);
// var_dump($mybugs);


