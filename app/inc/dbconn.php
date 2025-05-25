<?php

function Connexion()
{
  $chemin = $_SERVER['DOCUMENT_ROOT'];
  $base = include $chemin . '/config/base_ionos.php';

  $host_name = $base['host_name'];
  $database = $base['database'];
  $user_name = $base['user_name'];
  $password = $base['password'];

  $db = null;

  try {
    $db = new \PDO("mysql:host=$host_name; dbname=$database;", $user_name, $password);
  } catch (PDOException $e) {
    echo '<p style="color:#f80">Nous avons une erreur MySQL : ' . $e->getMessage() . '</p><br/>';
    die('erreur de connexion');
  }
  return $db;
}


function DBAone($requete)
{
  $link = Connexion();
  try {
    $q = $link->prepare($requete);
    $q->execute();
    $variable = $q->fetchAll(PDO::FETCH_ASSOC);
    return $variable;
  } catch (PDOException $e) {
    // Si une exception PDO est levée (une erreur PDO), elle est capturée ici
    // Vous pouvez traiter l'erreur comme vous le souhaitez
    // Par exemple, vous pouvez enregistrer le message d'erreur dans un fichier de log, ou afficher un message d'erreur à l'utilisateur, etc.
    error_log('Erreur dans la requête : ' . $e->getMessage());
    echo ('Erreur dans la requête : ' . $e->getMessage());
    // Vous pouvez également choisir de retourner false ou une valeur par défaut pour indiquer qu'une erreur s'est produite.
    return false;
  }
}

function DBone($requete)
{
  $link = Connexion();
  try {
    $q = $link->prepare($requete);
    $q->execute();
    $variable = $q->fetch(PDO::FETCH_ASSOC);
    return $variable;
  } catch (PDOException $e) {
    // Si une exception PDO est levée (une erreur PDO), elle est capturée ici
    // Vous pouvez traiter l'erreur comme vous le souhaitez
    // Par exemple, vous pouvez enregistrer le message d'erreur dans un fichier de log, ou afficher un message d'erreur à l'utilisateur, etc.
    error_log('Erreur dans la requête : ' . $e->getMessage());
    echo ('Erreur dans la requête : ' . $e->getMessage());
    // Vous pouvez également choisir de retourner false ou une valeur par défaut pour indiquer qu'une erreur s'est produite.
    return false;
  }
}
