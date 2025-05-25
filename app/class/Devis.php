<?php
class Devis
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
    $conn = new connBase();
    $secteur = $this->secteur;
    $reqdevis = "select COUNT(*) as total from devisestimatif where cs='$secteur'";
    $devis = $conn->oneRow($reqdevis);
    $this->increment = $devis['total'];
  }
  public function getNumDevis()
  {
    $conn = new connBase();
    $idsect = $this->secteur;
    $racine_devis = $conn->oneRow("select * from idcompte_infos where idcompte = '$idsect' ");
    $racine = $racine_devis['dev_racine'];
    $num = $this->increment;
    $date = date('ym');
    $numero = $idsect . '-' . $racine . '-' . $date . '-' . $num;
    return $numero;
  }
  public function getNomNumCommercial($nom = "", $num = "")
  {
    $conn = new connBase();
    $idsect = $this->secteur;
    $requsers = "select idusers,nom, prenom from users where idcompte = '$idsect' and nom like '$nom' or idcompte = '$idsect' and idusers like '$num' limit 1";
    $num = $conn->oneRow($requsers);
    return array(
      "nom" => $num['prenom'] . ' ' . $num['nom'],
      "num" => $num['idusers']
    );
  }
  public function askDevisLignes($numero, $champ = "*")
  {
    $conn = new connBase();
    $requette = "select $champ from devislignes where numero='$numero'";
    $infos = $conn->allRow($requette);
    $data = array();
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    // if($infos){$data='existe';}else{$data='no data';}
    return $data;
  }
  public function askDevisEntete($numero)
  {
    $secteur = $this->secteur;
    $conn = new connBase();
    $reqligne = "select * from devisestimatif where numero = '$numero' and cs='$secteur'";
    $ligne = $conn->oneRow($reqligne);
    return $ligne;
  }
  public function askNbrDevis()
  {
    $secteur = $this->secteur;
    $annee = date('Y');
    $conn = new connBase();
    // Attente
    $devis_attente = "select numdev from devisestimatif where cs='$secteur' and annee = '$annee' and validite='En attente' ";
    $attente = $conn->allRow($devis_attente);
    $nbr_attente = count($attente);
    // Valide
    $devis_valide = "select numdev from devisestimatif where cs='$secteur' and annee = '$annee' and validite like 'Valid%' ";
    $valide = $conn->allRow($devis_valide);
    $nbr_valide = count($valide);
    // Refuse
    $devis_refuse = "select numdev from devisestimatif where cs='$secteur' and annee = '$annee' and validite like 'Refu%' ";
    $refuse = $conn->allRow($devis_refuse);
    $nbr_refuse = count($refuse);
    // Montant
    $devis_montant = "select SUM(totttc) as m from devisestimatif where cs='$secteur' and annee = '$annee' and validite like 'Valid%' ";
    $m = $conn->oneRow($devis_montant);
    $montant = $m['m'];

    return array(
      "a" => $nbr_attente,
      "v" => $nbr_valide,
      "r" => $nbr_refuse,
      "t" => $nbr_attente + $nbr_valide + $nbr_refuse,
      "m" => $montant
    );
  }

  public function askNbrDevisMois($mois)
  {
    $secteur = $this->secteur;
    $annee = date('Y');
    $conn = new connBase();
    // Attente
    $devis_attente = "select numdev from devisestimatif where cs='$secteur' and mois = '$mois' and annee = '$annee'  ";
    $attente = $conn->allRow($devis_attente);
    $nbr_mois = count($attente);
    return $nbr_mois;
  }

  public function getPhrases($type = '')
  {
    $conn = new connBase();
    $idsect = $this->secteur;
    $reqphrases = "select * from phrases where cs = '$idsect' $type order by designation asc";
    $phrases = $conn->allRow($reqphrases);
    return $phrases;
  }

  public function getTitres($type = '')
  {
    $conn = new connBase();
    $idsect = $this->secteur;
    $reqphrases = "select * from devistitres where cs = '$idsect' $type order by titre asc";
    $phrases = $conn->allRow($reqphrases);
    return $phrases;
  }

  public function getChemin()
  {
    $secteur = $this->secteur;
    $chemin = $_SERVER['DOCUMENT_ROOT'];
    if (!is_dir($chemin . '/documents/pdf/devis')) {
      mkdir($chemin . '/documents/pdf/devis');
    }
    if (!is_dir($chemin . '/documents/pdf/devis/' . $secteur)) {
      mkdir($chemin . '/documents/pdf/devis/' . $secteur);
    }
    $path = $chemin . '/documents/pdf/devis/' . $secteur . '/';
    return $path;
  }

  public function getFichier($numero)
  {
    $secteur = $this->secteur;
    // $chemin = $_SERVER['DOCUMENT_ROOT'];
    // $repertoire = $chemin . '/documents/pdf/devis/' . $secteur . '/';
    $repertoire = $this->getChemin();
    $nomFichierRecherche = 'Devis_' . $numero . '.pdf';
    $fichiers = glob($repertoire . '*' . $nomFichierRecherche);
    if (!empty($fichiers)) {
      $res = $nomFichierRecherche;
    } else {
      $res = 0;
    }
    return $res;
  }

  public function getRepartitionDevis($secteur)
  {
    $devis = array();
    $nbr = array();
    $color = ['#3d76ad', '#dc3545', '#3a9d23'];
    $conn = new connBase();
    $reqrepart = "SELECT validite, COUNT(*) AS nbr_validite
    FROM devisestimatif
    WHERE cs='$secteur'
    
    GROUP BY validite
    ORDER BY validite ASC
    LIMIT 3;";
    $repart = $conn->allRow($reqrepart);
    foreach ($repart as $r) {
      $devis[] = $r['validite'];
      $nbr[] = $r['nbr_validite'];
    }
    return array("validite" => $devis, "nbr" => $nbr, "color" => $color);
  }

  public function getSerieRepartitionDevis()
  {
    $secteur = $this->secteur;
    $jsonArray = [];
    $donnees = $this->getRepartitionDevis($secteur);
    for ($i = 0; $i < 3; $i++) {
      $jsonArray[] = [
        'value' => $donnees['nbr'][$i],
        'name' => $donnees['validite'][$i],
        'itemStyle' => [
          'color' => $donnees['color'][$i],
        ],
      ];
    }
    $jsonString = json_encode($jsonArray);
    return $jsonString;
  }
}
