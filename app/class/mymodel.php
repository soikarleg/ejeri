<?php
class MyIndics
{
  private $secteur;
  private $iduser;

  public function __construct($secteur, $iduser)
  {
    $this->secteur = $secteur;
    $this->iduser = $iduser;
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
}
