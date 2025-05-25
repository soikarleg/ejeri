<?php

class Factures
{

  /** @var string */
  private $secteur;
  private $increment;

  public function __construct($secteur)
  {
    $this->secteur = $secteur;
    // $this->setIncrementFromDatabase();
    $this->setIncrementFacture();
  }

  private function setIncrementFacture()
  {
    $conn = new connBase();
    $secteur = $this->secteur;
    $reqfacture = "select COUNT(*) as total from facturesentete where cs='$secteur' ";
    $facture = $conn->oneRow($reqfacture);
    $this->increment = $facture['total'];
  }

  public function getNumFacture()
  {
    $conn = new connBase();
    $idsect = $this->secteur;
    $racine_devis = $conn->oneRow("select * from idcompte_infos where idcompte = '$idsect' ");
    $racine = $racine_devis['fac_racine'];
    $num = $this->increment;
    $date = date('ym');
    $numero = $idsect . '-' . $racine . '-' . $date . '-' . $num;
    return $numero;
  }

  public function getNumAvoir()
  {
    $conn = new connBase();
    $idsect = $this->secteur;
    $racine_devis = $conn->oneRow("select * from idcompte_infos where idcompte = '$idsect' ");
    $racine = $racine_devis['avo_racine'];
    $num = $this->increment;
    $date = date('ym');
    $numero = $idsect . '-' . $racine . '-' . $date . '-' . $num;
    return $numero;
  }

  public function getNomNumCommercial($nom = "", $num = "")
  {
    $conn = new connBase();
    $idsect = $this->secteur;
    $requsers = "select idusers,nom, prenom from users_sagaas where idcompte = '$idsect' and nom like '$nom' or idcompte = '$idsect' and idusers like '$num' limit 1";
    $num = $conn->oneRow($requsers);

    return array(
      "nom" => $num['prenom'] . ' ' . $num['nom'],
      "num" => $num['idusers']
    );
  }

  public function getMoisCA($mois, $annee)
  {
    $conn = new connBase();
    $secteur = $this->secteur;
    $requsers = "select *,SUM(totttc) as total from facturesentete where cs='$secteur' and mois = '$mois' and annee='$annee' and paye='oui' ";
    $num = $conn->oneRow($requsers);
    $data = $num['total'];
    return $data;
  }

  public function getMoisCAn($mois, $annee)
  {
    $conn = new connBase();
    $secteur = $this->secteur;
    $requsers = "select *,SUM(totttc) as total from facturesentete where cs='$secteur' and mois = '$mois' and annee='$annee' and paye='non' ";
    $num = $conn->oneRow($requsers);
    $data = $num['total'];
    return $data;
  }

  public function infoPropre($infos)
  {
    $infos = str_replace('<small>', '', $infos);
    $infos = str_replace('<br>', "\n", $infos);
    return trim($infos);
  }

  public function getSerieCA($a)
  {
    //$annee = date('Y');
    $annee = $a;
    $concatenated = "";
    for ($i = 1; $i < 13; $i++) {
      $mois = strlen($i) < 2 ? "0" . $i : $i;
      $donnees = $this->getMoisCA($mois, $annee);
      $concatenated .= "" . Dec_2($donnees) . ",";
    }
    $concatenated = rtrim($concatenated, ", ");
    $mois_ca = "[" . $concatenated . "]";
    return $mois_ca;
  }

  public function getSerieCAn($a)
  {
    //$annee = date('Y');
    $annee = $a;
    $concatenated = "";
    for ($i = 1; $i < 13; $i++) {
      $mois = strlen($i) < 2 ? "0" . $i : $i;
      $donnees = $this->getMoisCAn($mois, $annee);
      $concatenated .= "" . Dec_2($donnees) . ",";
    }
    $concatenated = rtrim($concatenated, ", ");
    $mois_ca = "[" . $concatenated . "]";
    return $mois_ca;
  }

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
  public function askFacturesLignes($numero, $champ = "*")
  {
    $conn = new connBase();
    $requette = "select $champ from factureslignes where numero='$numero'";
    $infos = $conn->allRow($requette);
    $data = array();
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    // if($infos){$data='existe';}else{$data='no data';}
    return $data;
  }

  public function askFacturesEntete($numero)
  {
    $secteur = $this->secteur;
    $conn = new connBase();
    $reqligne = "select * from facturesentete where numero = '$numero' and cs='$secteur'";
    $ligne = $conn->oneRow($reqligne);
    return $ligne;
  }

  public function askFacturesAttente()
  {
    $secteur = $this->secteur;
    $conn = new connBase();
    $reqligne = "select SUM(totttc) as tot, COUNT(numero) as nbr from facturesentete where paye = 'non' and cs='$secteur'";
    $ligne = $conn->oneRow($reqligne);
    return $ligne;
  }

  public function askEtatFactures($mois = "", $annee = "")
  {
    $secteur = $this->secteur;
    $mois = $mois === "" ? "" : "and mois LIKE '$mois'";
    $annee = $annee === "" ? "" : "and annee LIKE '$annee'";
    $conn = new connBase();
    $reqligne = "select SUM(totttc) as tot from facturesentete where cs='$secteur' $mois $annee ";
    $ligne = $conn->oneRow($reqligne);
    return $ligne;
  }

  public function askEtatFacturesAttente($mois = "", $annee = "")
  {
    $secteur = $this->secteur;
    $mois = $mois === "" ? "" : "and mois LIKE '$mois'";
    $annee = $annee === "" ? "" : "and annee LIKE '$annee'";
    $conn = new connBase();
    $reqligne = "select SUM(totttc) as tot from facturesentete where paye='non' and cs='$secteur' $mois $annee ";
    $ligne = $conn->oneRow($reqligne);
    return $ligne;
  }

  public function askFacturesMois($mois, $annee)
  {
    $secteur = $this->secteur;
    $conn = new connBase();
    $reqligne = "select SUM(totttc) as tot from facturesentete where cs='$secteur' and mois = '$mois' and annee='$annee' ";
    $ligne = $conn->oneRow($reqligne);
    return $ligne;
  }

  public function askFacturesRelance()
  {
    $secteur = $this->secteur;
    $conn = new connBase();
    $reqligne = "select * from facturesentete where paye = 'non' and cs='$secteur' group by id order by nom asc";
    $ligne = $conn->allRow($reqligne);
    return $ligne;
  }

  public function askFacturesAttenteClient($idcli)
  {
    $secteur = $this->secteur;
    $conn = new connBase();
    $reqligne = "select * from facturesentete where paye = 'non' and cs='$secteur' and id='$idcli' ";
    $ligne = $conn->allRow($reqligne);
    return $ligne;
  }

  public function askFacturesRelanceClientTotal($idcli)
  {
    $secteur = $this->secteur;
    $conn = new connBase();
    $reqligne = "select SUM(totttc) as tot from facturesentete where paye = 'non' and cs='$secteur' and id='$idcli' group  by id";
    $ligne = $conn->oneRow($reqligne);
    return $ligne;
  }

  public static function askRemises($secteur)
  {

    $conn = new connBase();
    $requette = "select * from reglements where cs='$secteur' ";
    $infos = $conn->allRow($requette);
    $data = array();
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    // if($infos){$data='existe';}else{$data='no data';}
    return $data;
  }

  public function getChemin()
  {
    $secteur = $this->secteur;
    $chemin = $_SERVER['DOCUMENT_ROOT'];
    if (!is_dir($chemin . '/documents/pdf/factures')) {
      mkdir($chemin . '/documents/pdf/factures');
    }
    if (!is_dir($chemin . '/documents/pdf/factures/' . $secteur)) {
      mkdir($chemin . '/documents/pdf/factures/' . $secteur);
    }
    $path = $chemin . '/documents/pdf/factures/' . $secteur . '/';
    return $path;
  }

  public function getCheminRelance($idcli)
  {
    $secteur = $this->secteur;
    $chemin = $_SERVER['DOCUMENT_ROOT'];
    if (!is_dir($chemin . '/documents/pdf/relances')) {
      mkdir($chemin . '/documents/pdf/relances');
    }
    if (!is_dir($chemin . '/documents/pdf/relances/' . $secteur)) {
      mkdir($chemin . '/documents/pdf/relances/' . $secteur);
    }

    if (!is_dir($chemin . '/documents/pdf/relances/' . $secteur . '/client_' . $idcli)) {
      mkdir($chemin . '/documents/pdf/relances/' . $secteur . '/client_' . $idcli);
    }


    $path = $chemin . '/documents/pdf/relances/' . $secteur . '/client_' . $idcli;
    return $path;
  }

  public function getFichier($numero)
  {
    $secteur = $this->secteur;
    $conn = new connBase;
    $fac = new Factures($secteur);
    $facture = $conn->askFactureNum($secteur, $numero);
    $repertoire = $this->getChemin();
    //$nomFichierRecherche = 'Facture_' . $numero . '.pdf';
    $mont = $fac->askFacturesEntete($numero);
    $mont = Dec_2($mont['totttc']);

    $u_nom = str_replace(' ', '_', $facture['nom']);
    $u_nom = trim(strtoupper($u_nom));

    $nomFichierRecherche = 'Facture_' . $u_nom . '_' .  $facture['id'] . '_' . $numero . '_' . $mont . '.pdf';

    $fichiers = glob($repertoire . '*' . $nomFichierRecherche);
    if (!empty($fichiers)) {
      $res = $nomFichierRecherche;
    } else {
      $res = 0;
    }
    return $res;
  }

  public function getRelance($idcli)
  {
    $secteur = $this->secteur;
    // $chemin = $_SERVER['DOCUMENT_ROOT'];
    // $repertoire = $chemin . '/documents/pdf/devis/' . $secteur . '/';
    $repertoire = $this->getCheminRelance($idcli);
    $nomFichierRecherche = 'Relance_' . $idcli . '.pdf';
    //$fichiers = glob($repertoire . '*' . $nomFichierRecherche);
    $fichiers = $repertoire . '/' . $nomFichierRecherche;
    //var_dump($fichiers);
    if (!empty($fichiers)) {
      $res = $nomFichierRecherche;
    } else {
      $res = 0;
    }
    return $res;
  }

  public function askAcompte($numero, $client)
  {
    $conn = new connBase();
    $secteur = $this->secteur;
    $requette = "select * from reglements where cs='$secteur' and id like '$client' COLLATE utf8_general_ci and factref like '$numero' COLLATE utf8_general_ci and acompte = '1' COLLATE utf8_general_ci";
    $infos = $conn->oneRow($requette);
    $data = array();
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    // if($infos){$data='existe';}else{$data='no data';}
    return $data;
  }
}
