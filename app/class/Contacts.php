<?php
class Contacts
{
  /** @var string */
  private $secteur;
  private $data;
  public $ville;
  public $nbr;
  // private $base;
  public function __construct($secteur, $data = "")
  {
    $this->secteur = $secteur;
    $this->data  = $data;
  }

  public function Tel($t = null)
  {
    $tel = wordwrap($t, 2, '.', true);
    return $tel;
  }

  public function getRepartition($secteur)
  {
    $ville = array();
    $nbr = array();
    $conn = new Magesquo($secteur);
    $param = ['idcompte' => $secteur];
    $reqrepart = "SELECT ville, COUNT(*) AS nbr_cp
    FROM client_chantier
    WHERE idcompte=:idcompte
    
    GROUP BY cp
    ORDER BY nbr_cp DESC
    LIMIT 4;";
    $repart = $conn->allRow($reqrepart,$param);
    foreach ($repart as $r) {
      $ville[] = $r['ville'];
      $nbr[] = $r['nbr_cp'];
    }
    return array("ville" => $ville, "nbr" => $nbr);
  }


  public function getSerieRepartition()
  {
    $secteur = $this->secteur;
    $jsonArray = [];
    $donnees = $this->getRepartition($secteur);
    for ($i = 0; $i < 4; $i++) {
      $jsonArray[] = [
        'value' => $donnees['nbr'][$i],
        'name' => strtoupper($donnees['ville'][$i]),
        'colorBy' => '#654951',
      ];
    }
    $jsonString = json_encode($jsonArray);
    return $jsonString;
  }
}
