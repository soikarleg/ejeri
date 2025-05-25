<?php

//namespace Flxxx;

class Relances
{


  private $secteur;
  private $increment;
  private $idcli;

  public function __construct($secteur, $idcli)
  {
    $this->secteur = $secteur;
    $this->idcli = $idcli;
    $this->setNombreRelance($idcli);
  }

  /**
   * setNombreRelance
   *
   * @return int
   */
  public function setNombreRelance()
  {
    $secteur = $this->secteur;
    $idcli = $this->idcli;
    $chemin = $_SERVER['DOCUMENT_ROOT'];
    $path = $chemin . '/documents/pdf/relances/' . $secteur . '/client_' . $idcli;
    $exploreDir = scandir($path);
    $relanceNbr = count($exploreDir) - 2;
    return $relanceNbr;
  }
};
