<?php

class Reglements
{

  private $secteur;


  public function __construct($secteur)
  {
    $this->secteur = $secteur;
  }

  public function remiseBordereau($data)
  {
    $base = new connBase;
    $secteur = $this->secteur;
    $select_bordereau = "select *,SUM(montant) as total from reglements where cs = '$secteur' $data group by bordereau order by time_maj desc";
    $data = $base->allRow($select_bordereau);
    return $data;
  }

  public function askBordereau($numbdx)
  {
    $base = new connBase;
    $secteur = $this->secteur;
    $select_bordereau = "select * from reglements where cs = '$secteur' and bordereau ='$numbdx' ";
    $infos = $base->allRow($select_bordereau);

    $data = array();
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    //var_dump($data);
    return $infos;
  }

  public function remiseBordereauUnitaire($data)
  {
    $base = new connBase;
    $secteur = $this->secteur;
    $select_bordereau = "select * from reglements where cs = '$secteur' $data order by time_maj desc";
    $data = $base->allRow($select_bordereau);
    return $data;
  }

  public function remiseTotalBordereau($data)
  {
    $base = new connBase;
    $secteur = $this->secteur;
    $select_bordereau = "select SUM(montant) as total from reglements where cs = '$secteur' $data ";
    $data = $base->oneRow($select_bordereau);
    return $data['total'];
  }

  public function askTotalBordereau($mois = "", $annee = "")
  {
    $mois = $mois === "" ? "" : "and mois LIKE '$mois'";
    $annee = $annee === "" ? "" : "and annee LIKE '$annee'";
    $base = new connBase;
    $secteur = $this->secteur;
    $select_bordereau = "select SUM(montant) as tot from reglements where cs = '$secteur' $mois $annee ";
    $data = $base->oneRow($select_bordereau);
    return $data['tot'];
  }

  public function getNomBank($idrib)
  {
    $base = new connBase;
    $select_bank = "select * from bank where idrib = '$idrib' limit 1";
    $bank = $base->oneRow($select_bank);
    $bank = $bank['nom_bank'];
    return $bank;
  }


  public function remiseClientBordereau($bordereau)
  {
    $base = new connBase;
    $secteur = $this->secteur;
    $select_bordereau = "select * from reglements where cs = '$secteur' and bordereau = '$bordereau'";

    $data = $base->allRow($select_bordereau);

    return $data;
  }
  public function askReglementsClient($idcli)
  {
    $annref = date('Y') - 1;
    $base = new connBase;
    $secteur = $this->secteur;
    $select_bordereau = "select * from reglements where cs = '$secteur' and id='$idcli' and annee='$annref'";
    $reglements = $base->allRow($select_bordereau);
    return $reglements;
  }

  public function askReglementsFact($numero, $idcli)
  {

    $base = new connBase;
    $secteur = $this->secteur;
    $select_bordereau = "select *,SUM(montant) as tot from reglements where cs = '$secteur' and id='$idcli' and factref='$numero'";
    $reglements = $base->oneRow($select_bordereau);
    return $reglements;
  }
}
