<?php

use MaGesquo;

class Clients extends MaGesquo
{

  private $idcompte;
  private $conn;
  private $champsRequis;

  /**
   * __construct
   *
   * @param  mixed $idcompte
   *
   */
  public function __construct($idcompte)
  {
    $this->idcompte = $idcompte;
    $this->conn = new MaGesquo($this->idcompte);
    $this->champsRequis = ['civilite', 'nom', 'adresse', 'ville', 'cp'];
  }

  /**
   * filtreContacts
   *
   * @param  mixed $data
   * @return array
   */
  public function filtreContacts($data): array
  {
    $erreurs = [];
    foreach ($this->champsRequis as $champ) {
      if (empty($data[$champ])) {
        $erreurs[] = ucfirst($champ) . ' est requis.';
      }
    }
    return $erreurs;
  }

  /**
   * nombreRec
   *
   * @return int
   */
  public function nombreRec()
  {
    $requette = "select MAX(idcli) as max from client";
    $infos = $this->conn->oneRow($requette);
    $len = strlen($infos['max']) + 1;
    return $len;
  }

  /**
   * nombreTotal
   *
   * @return int
   */
  public function nombreTotal(): int
  {
    $param = ['idcompte' => $this->idcompte];
    $requette = "select count(idcli) as nbr from client where idcompte = :idcompte";
    $infos = $this->conn->oneRow($requette, $param);

    return $infos['nbr'];
  }
  /**
   * showNomClient
   *
   * @param  mixed $idcli
   * @return string
   */
  public function showNomClient($idcli): string
  {
    $param = ['idcompte' => $this->idcompte, 'idcli' => $idcli];
    $requette = "select civilite, nom, prenom from client where idcli = :idcli and idcompte = :idcompte";
    $infos = $this->conn->oneRow($requette, $param);
    if (empty($infos)) {
      return '';
    }
    $nom = $infos['civilite'] . ' ' . $infos['prenom'] . ' ' . $infos['nom'];
    return $nom;
  }

  /**
   * showClient
   * Ramène les infos de client client_chantier client_correspondance et client_infos
   * @param  mixed $idcli
   * @return array
   */
  function showClient($idcli): array
  {
    $param = ['idcompte' => $this->idcompte, 'idcli' => $idcli];
    $requette = "select * from client c join client_chantier cch on c.idcli = cch.idcli join client_correspondance cco on c.idcli = cco.idcli join client_infos ci on c.idcli = ci.idcli where ci.actif = 1 and c.idcli =:idcli and c.idcompte = :idcompte";
    $infos = $this->conn->allRow($requette, $param);
    return $infos;
  }


  public function nomClient($idcli): array
  {
    $param = ['idcompte' => $this->idcompte, 'idcli' => $idcli];
    $requette = "select * from client where idcli = :idcli and idcompte = :idcompte";
    $infos = $this->conn->oneRow($requette, $param);
    if (empty($infos)) {
      return [];
    }
    return $infos;
  }


  /**
   * infosClient
   * Ramène les infos de client et client_infos
   * @param  mixed $idcli
   * @return array
   */
  public function infosClient($idcli): array
  {
    $param = ['idcompte' => $this->idcompte, 'idcli' => $idcli];
    $requette = "select * from client_infos where idcli = :idcli and idcompte = :idcompte";
    $infos = $this->conn->oneRow($requette, $param);
    if (empty($infos)) {
      return [];
    }
    return $infos;
  }

  

  /**
   * correspondanceClient
   * Ramène les infos de client client_correspondance
   * @param  mixed $idcli
   * @return array
   */
  public function correspondanceClient($idcli): array
  {
    $param = ['idcompte' => $this->idcompte, 'idcli' => $idcli];
    $requette = "SELECT * FROM `client_correspondance_vue` WHERE `correspondance_idcli`=:idcli AND `correspondance_idcompte`= :idcompte";
    $infos = $this->conn->oneRow($requette, $param);
    if (empty($infos)) {
      return [];
    }
    return $infos;
  }

  /**
   * suffAnneeMois
   *
   * @param  mixed $id
   * @return string
   */
  public function suffAnneeMois($id): string
  {
    $param = ['idcli' => $id];
    $requette = "select datecrea from client where idcli like :idcli";
    $infos = $this->conn->oneRow($requette, $param);

    $date = new DateTime($infos['datecrea']); // Date de création
    $annee = $date->format("y"); // Extrait l'année
    $mois = $date->format("m");
    $suff = $annee . $mois;
    return $suff;
  }

  /**
   * genereNumeroClient
   * A partir de l'autoincrement sur base client
   * @param  mixed $recordNumber
   *
   */
  public function genereNumeroClient($recordNumber)
  {

    $suff = $this->suffAnneeMois($recordNumber);
    if (!is_int($recordNumber) || $recordNumber < 0) {
      throw new InvalidArgumentException("Le numéro d'enregistrement doit être un entier positif.");
    }
    $pad = $this->nombreRec();
    $nbr = $this->nombreTotal();
    $clientNumber = str_pad($recordNumber, $pad, '0', STR_PAD_LEFT);
    // 
    return  $suff . '-'  . $clientNumber;
  }











  //   //********** A TRANSFERE SUR AUTRE CLASS */


  //   /**
  //    * askClient
  //    * Basée sur 'client_chantier'
  //    * @param  mixed $id
  //    * @param  mixed $champ
  //    * @return array
  //    */
  //   public function askClient($id, $champ = "*")
  //   {
  //     $requette = "select $champ from client_chantier where idcli like '$id'";
  //     $infos = $this->oneRow($requette);
  //     $data = array();
  //     foreach ($infos as $k => $v) {
  //       $data[$k] =  $v;
  //     }
  //     return $data;
  //   }
  //   public function askClientFull($id)
  //   {
  //     $requette = "SELECT client_chantier.*, client_infos.* FROM client_chantier INNER JOIN client_infos ON client_chantier.idcli = client_infos.idcli WHERE client_chantier.idcli = '$id'; ";
  //     $infos = $this->oneRow($requette);
  //     // $data = array();
  //     // foreach ($infos as $k => $v) {
  //     //     $data[$k] =  $v;
  //     // }
  //     return $infos;
  //   }
  //   public function askAllClient($secteur)
  //   {
  //     $requette = "select * from client_chantier where idcompte='$secteur' order by nom asc";
  //     $infos = $this->allRow($requette);
  //     // $data = array();
  //     // foreach ($infos as $k => $v) {
  //     //     $data[$k] =  $v;
  //     // }
  //     return $infos;
  //   }
  //   public function askClientRegulier($secteur)
  //   {
  //     $requette = "select * from client_infos where idcompte='$secteur' and type like 'Rég%' or idcompte='$secteur' and type like 'Reg%' ";
  //     $infos = $this->allRow($requette);
  //     return $infos;
  //   }
  //   public function askClientAdresse($id, $champ = "*")
  //   {
  //     $requette = "select $champ from client_adresse where idcli like '$id'";
  //     $infos = $this->oneRow($requette);
  //     $data = array();
  //     foreach ($infos as $k => $v) {
  //       $data[$k] =  $v;
  //     }
  //     return $data;
  //   }
  //   public function askClientMail($email)
  //   {
  //     $requette = "select * from client_chantier where email like '$email'";
  //     $infos = $this->oneRow($requette);
  //     $data = array();
  //     foreach ($infos as $k => $v) {
  //       $data[$k] =  $v;
  //     }
  //     return $data;
  //   }
  //   public function askClientInfos($id, $champ = "*")
  //   {
  //     $requette = "select $champ from client_infos where idcli like '$id'";
  //     $infos = $this->oneRow($requette);
  //     $data = array();
  //     foreach ($infos as $k => $v) {
  //       $data[$k] =  $v;
  //     }
  //     return $data;
  //   }
  //   /**
  //    * checkClient
  //    * Basée sur 'client_chantier'
  //    * @param  mixed $nom
  //    * @param  mixed $prenom
  //    * @param  mixed $adresse
  //    * @param  mixed $cp
  //    * @return array
  //    */
  //   public function checkClient($nom, $prenom, $adresse, $cp)
  //   {
  //     $requette = "select * from client_chantier where nom = '$nom' and prenom='$prenom' and adresse = '$adresse' and cp = '$cp' limit 1";
  //     $infos = $this->oneRow($requette);
  //     $data = array();
  //     foreach ($infos as $k => $v) {
  //       $data[$k] =  $v;
  //     }
  //     return $data;
  //   }
  //   public function askClientNotes($id, $champ = "*")
  //   {
  //     $requette = "select $champ from client_notes where idcli like '$id'";
  //     $infos = $this->allRow($requette);
  //     // $data = array();
  //     // foreach ($infos as $k => $v) {
  //     //     $data[$k] =  $v;
  //     // }
  //     return $infos;
  //   }
}
