<?php
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/dbconn.php';


class Exports
{

  /** @var string */
  private $secteur;
  private $increment;

  public function __construct($secteur)
  {
    $this->secteur = $secteur;
    // $this->setIncrementFromDatabase();
    $this->setIncrement();
  }

  private function setIncrement()
  {

    $reqdevis = "select COUNT(*) as total from users ";
    $devis = DBone($reqdevis);
    $this->increment = $devis['total'] + 1;
  }

  public function genNumDoc()
  {
    // Obtenir le mois et l'année actuels
    $mois = date('m');
    $annee_complete = date('Y');
    $annee = substr($annee_complete, 2);
    $racine = 'DEV';

    // Format du numéro de document : secteur-mois-annee-increment
    $numeroDocument = "{$racine}-{$this->secteur}-{$mois}{$annee}-{$this->increment}";

    return $numeroDocument;
  }
}
