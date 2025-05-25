<?php
class Production
{
  /** @var string */
  private $secteur;
  public $heures;
  public $hmo;
  public $hnf;
  public function __construct($secteur)
  {
    $this->secteur = $secteur;
  }
  /**
   * getHeures
   *
   * @param  mixed $mois
   * @param  mixed $annee
   * 
   */
  public function getHeures($mois = "", $annee = "")
  {
    $secteur = $this->secteur;
    $mois = $mois === "" ? "" : "and mois LIKE '$mois'";
    $annee = $annee === "" ? "" : "and annee LIKE '$annee'";
    $conn = new connBase();
    $reqHeures = "SELECT
              SUM(CASE WHEN codeprod IN ('MO', 'NF') THEN quant ELSE 0 END) AS heures,
              SUM(CASE WHEN codeprod = 'MO' THEN quant ELSE 0 END) AS heures_mo,
              SUM(CASE WHEN codeprod = 'NF' THEN quant ELSE 0 END) AS heures_nf
          FROM
              production
          WHERE
          idcompte ='$secteur'
          $mois
          $annee
          ";
    $heures = $conn->oneRow($reqHeures);
    $this->heures = $heures['heures'];
    $this->hmo = $heures['heures_mo'];
    $this->hnf = $heures['heures_nf'];
    return array("heures" => $heures['heures'], "hmo" => $heures['heures_mo'], "hnf" => $heures['heures_nf']);
  }
  /**
   * getTauxHoraire
   *
   * 
   */
  public function getTauxHoraire()
  {
    $secteur = $this->secteur;
    $conn = new connBase();
    $reqth = "select tr from idcompte_infos where idcompte ='$secteur'";
    $th = $conn->oneRow($reqth);
    $th = Dec_0($th['tr']);
    return $th;
  }
  /**
   * getHMO
   *
   * 
   */
  public function getHMO()
  {
    $hmo = $this->hmo;
    return $hmo;
  }
  /**
   * getHNF
   *
   * 
   */
  public function getHNF()
  {
    $hnf = $this->hnf;
    return $hnf;
  }
  /**
   * getTOT
   *
   * 
   */
  public function getTOT()
  {
    $heures = $this->heures;
    return $heures;
  }
  /**
   * getRatio
   *
   * 
   */
  public function getRatio()
  {
    $hnf = $this->hnf;
    //echo $heures = $this->heures;
    if ($this->heures !== '0') {
      $heures = 1;
    } else {
      $heures = $this->heures;
    }
    $ratio = ($hnf * 100) / $heures;
    return $ratio;
  }
  /**
   * getSerieAnnee
   *
   * 
   */
  public function getSerieAnnee()
  {
    $mois_lettre = array();
    $mois = array(
      'Janvier',
      'Février',
      'Mars',
      'Avril',
      'Mai',
      'Juin',
      'Juillet',
      'Août',
      'Septembre',
      'Octobre',
      'Novembre',
      'Décembre'
    );
    for ($i = 0; $i < count($mois); $i++) {
      $mois_lettre[] = $mois[$i];
    }
    $concatenated = "";
    foreach ($mois_lettre as $serie) {
      $concatenated .= "'" . $serie . "',";
    }
    $concatenated = rtrim($concatenated, ", ");
    $lettre = "[" . $concatenated . "]";
    return $lettre;
  }
  /**
   * getSerieHNF
   *
   * 
   */
  public function getSerieHNF()
  {
    $annee = date('Y');
    $concatenated = "";
    for ($i = 1; $i < 13; $i++) {
      $mois = strlen($i) < 2 ? "0" . $i : $i;
      $donnees = $this->getHeures($mois, $annee);
      $concatenated .= "" . Dec_0($donnees['hnf']) . ",";
    }
    $concatenated = rtrim($concatenated, ", ");
    $mois_hnf = "[" . $concatenated . "]";
    return $mois_hnf;
  }
  /**
   * getSerieHMO
   *
   * 
   */
  public function getSerieHMO()
  {
    $annee = date('Y');
    $concatenated = "";
    for ($i = 1; $i < 13; $i++) {
      $mois = strlen($i) < 2 ? "0" . $i : $i;
      $donnees = $this->getHeures($mois, $annee);
      $concatenated .= "" . Dec_0($donnees['hmo']) . ",";
    }
    $concatenated = rtrim($concatenated, ", ");
    $mois_hmo = "[" . $concatenated . "]";
    return $mois_hmo;
  }
  /**
   * getSerieMoyenne
   *
   * 
   */
  public function getSerieMoyenne()
  {
    $annee = date('Y');
    $tour = 0;
    for ($i = 1; $i < 13; $i++) {
      $mois = strlen($i) < 2 ? "0" . $i : $i;
      $donnees = $this->getHeures($mois, $annee);
      $tour = $tour + $donnees['heures'];
      $moyenne = $tour / $i;
    }
    $moyenne_finale = $moyenne;
    $serie_moyenne = "";
    for ($i = 1; $i < 13; $i++) {
      $serie_moyenne .= "" . Dec_0($moyenne_finale) . ",";
    }
    $serie_moyenne = rtrim($serie_moyenne, ", ");
    $mois_moyenne = "[" . $serie_moyenne . "]";
    return $mois_moyenne;
  }
  public function getNonFacture()
  {
    $secteur = $this->secteur;
    $conn = new connBase();
    $reqnonfacturee = "select * from production where idcompte ='$secteur' and factok ='non' and codeprod='MO'";
    $result = $conn->allRow($reqnonfacturee);
    return $result;
  }
  public function getTotNonFacture()
  {
    $secteur = $this->secteur;
    $conn = new connBase();
    $reqnonfacturee = "select SUM(quant) as tot from production where idcompte ='$secteur' and factok ='non' and codeprod='MO' group by idcompte";
    $result = $conn->oneRow($reqnonfacturee);
    return $result['tot'];
  }
  public function getInterIK($idusers, $annref)
  {
    $secteur = $this->secteur;
    $conn = new connBase();
    $reqnonfacturee = "select * from production where idcompte ='$secteur' and annee='$annref' and idinter='$idusers' and codeprod='MO'";
    $result = $conn->allRow($reqnonfacturee);
    return $result;
  }
  public function getAdresseCli($idcli)
  {
    $secteur = $this->secteur;
    $conn = new connBase();
    $reqnonfacturee = "select * from client_chantier where idcompte ='$secteur' and  idcli='$idcli' limit 1";
    $result = $conn->oneRow($reqnonfacturee);
    return $result['adresse'] . ' ' . $result['cp'] . ' ' . $result['ville'];
  }
  public function getInterTotal($idusers, $annref)
  {
    $secteur = $this->secteur;
    $conn = new connBase();
    $reqnonfacturee = "select *, SUM(quant) as tot from production where idcompte ='$secteur' and annee='$annref' and idinter='$idusers' and codeprod='MO' group by idinter";
    $result = $conn->oneRow($reqnonfacturee);
    return $result['tot'];
  }
  public function getProdJour($secteur, $date)
  {
    $d = explode('/', $date);
    $jour = $d[0];
    $mois = $d[1];
    $annee = $d[2];
    $secteur = $this->secteur;
    $conn = new connBase();
    $prodjour = "select *,SUM(quant) as tot from production where 
    idcompte ='$secteur' and jour='$jour' and mois='$mois' and annee = '$annee' and codeprod='MO'      
    group by idcli order by idcli desc";
    $result = $conn->allRow($prodjour);
    return $result;
  }
  public function getProdClient($idcli, $annee)
  {

    $secteur = $this->secteur;
    $conn = new connBase();
    $prodjour = "select * from production where 
    idcompte ='$secteur' and annee = '$annee' and codeprod='MO' and idcli='$idcli' order by mois desc, jour desc";
    $result = $conn->allRow($prodjour);
    return $result;
  }
  public function getHFJourInter($secteur, $idinter, $date)
  {
    $d = explode('/', $date);
    $jour = $d[0];
    $mois = $d[1];
    $annee = $d[2];
    $secteur = $this->secteur;
    $conn = new connBase();
    $prodjour = "select * from production where 
    idcompte ='$secteur' and jour='$jour' and mois='$mois' and annee = '$annee' and codeprod='MO' and idinter='$idinter'";
    $result = $conn->allRow($prodjour);
    return $result;
  }
  public function getHFSemInter($idinter, $sem, $annee)
  {
    $secteur = $this->secteur;
    $conn = new connBase();
    $prodjour = "select * from production where 
    idcompte ='$secteur' and sem='$sem' and annee = '$annee' and codeprod='MO' and idinter='$idinter'order by jour asc";
    $result = $conn->allRow($prodjour);
    return $result;
  }
  public function getNFSemInter($idinter, $sem, $annee)
  {
    $secteur = $this->secteur;
    $conn = new connBase();
    $prodjour = "select * from production where 
    idcompte ='$secteur' and sem='$sem' and annee = '$annee' and codeprod='NF' and idinter='$idinter' order by jour asc";
    $result = $conn->allRow($prodjour);
    return $result;
  }
  public function getTitreTravaux()
  {
    $secteur = $this->secteur;
    $conn = new connBase();
    $prodjour = "select travaux from production where idcompte='$secteur'
    and codeprod='NF' group by travaux order by travaux";
    $result = $conn->allRow($prodjour);
    return $result;
  }
  public function getNFJourInter($secteur, $idinter, $date)
  {
    $d = explode('/', $date);
    $jour = $d[0];
    $mois = $d[1];
    $annee = $d[2];
    $secteur = $this->secteur;
    $conn = new connBase();
    $prodjour = "select * from production where 
    idcompte ='$secteur' and jour='$jour' and mois='$mois' and annee = '$annee' and codeprod='NF' and idinter='$idinter'";
    $result = $conn->allRow($prodjour);
    return $result;
  }
  public function derniersJours($nbr)
  {
    $dates = [];
    for ($i = 0; $i <= $nbr; $i++) {
      $date = new DateTime();
      $date->modify("-$i day");
      $dates[] = $date->format('d/m/Y');
    }
    return $dates;
  }
  public function userList()
  {
    $secteur = $this->secteur;
    $conn = new connBase();
    $reqnonfacturee = "select * from users where idcompte ='$secteur' and actif ='1'";
    $result = $conn->allRow($reqnonfacturee);
    return $result;
  }
}
