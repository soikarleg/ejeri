<?php
session_start();
//error_reporting(\E_ALL);
//ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$term = null;
$verifier_client = verifInfosClient($secteur);
$con = new connBase();
$reqevents = "SELECT * FROM `events` WHERE idcompte = '$secteur'";
$event = $con->allRow($reqevents);

foreach ($event as $v) {

  $events[] = [
    'id' => $v['id'],
    'title' => $v['title'],
    'start' => $v['start'],
    'end' => $v['end'],

    'extendedProps' => [
      'description' => $v['description'],
      'iduser' => initialesColla($v['iduser']),
      'user' => $v['iduser'],


    ],
  ];
}


echo $fullevents =  json_encode($events);
//  'initiales' => $v['initiales'],