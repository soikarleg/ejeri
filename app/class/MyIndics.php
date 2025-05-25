<?php
class MyIndics
{
  private $secteur;
  private $iduser;
  private $conn;
  private $moisref;

  public function __construct($secteur, $iduser)
  {
    $this->secteur = $secteur;
    $this->iduser = $iduser;
    $this->conn = new connBase();
    $this->moisref = date('m');
  }

  // Accès aux variables du constructeur
  public function getSecteur()
  {
    return $this->secteur;
  }

  public function getIdUser()
  {
    return $this->iduser;
  }
  public function getMois()
  {
    return $this->moisref;
  }

  public function getConn()
  {
    return $this->conn;
  }
  public function MyFact($mois = "", $annee = "")
  {
    $secteur = MyIndics::getSecteur();
    $mois = $mois === "" ? "" : "and mois LIKE '$mois'";
    $annee = $annee === "" ? "" : "and annee LIKE '$annee'";
    $conn = MyIndics::getConn();
    $reqligne = "select SUM(totttc) as tot from facturesentete where cs='$secteur' $mois $annee ";
    $ligne = $conn->oneRow($reqligne);
    return $ligne;
  }
}
