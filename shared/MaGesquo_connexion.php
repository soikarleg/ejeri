<?php
class MaGesquo
{
  private $link;
  private $idcompte;


  /**
   * __construct
   *
   * @param  mixed $idcompte
   *
   */
  public function __construct($idcompte)
  {
    $this->idcompte = $idcompte;
    $chemin = $_SERVER['DOCUMENT_ROOT'];
    $base = include $chemin . '/config/base_ionos.php';
    // base_ionos_old.php pour ancienne base.
    $host_name = $base['db_host'];
    $database = $base['db_name'];
    $user_name = $base['db_user'];
    $password = $base['db_password'];
    try {
      $this->link = new \PDO("mysql:host=$host_name; dbname=$database;", $user_name, $password);
    } catch (PDOException $e) {
      //' . $e->getMessage() . '
      echo '<p style="color:#f80">Nous avons une erreur MySQL</p><br/>';
      die();
    }
  }

  /**
   * ConnexionMag
   */
  public function connexionBD()
  {
    return $this->link;
  }

  /**
   * deconnexionBD
   */
  public function deconnexionBD()
  {
    $this->link = null;
  }

  /**
   * getIdcompte
   */
  public function getIdcompte()
  {
    return $this->idcompte;
  }

  public function getIduser()
  {
    $param = ['idcompte' => $this->idcompte];
    $select = "select referent_id from idcompte where idcompte=:idcompte";
    $id = $this->oneRow($select, $param);
    return $id['referent_id'];
  }

  /**
   * allRow
   * @ Exemple de requete :
   * @ $requete = "SELECT * FROM users WHERE id = :id AND nom= :nom";
   * @ $params = ['id' => $userId,'nom'=>$userNom ];
   * @ $result = $this->allRow($requete, $params);
   * @param  mixed $requete
   * @param  mixed $params
   *
   */
  public function allRow($requete, $params = [])
  {

    try {
      $q = $this->link->prepare($requete);
      $q->execute($params);
      $variable = $q->fetchAll(PDO::FETCH_ASSOC);
      return $variable;
    } catch (PDOException $e) {
      error_log("Erreur lors de allRow() : " . $e->getMessage());
      throw new Exception("Une erreur 'fetchAll' est survenue lors de la manipulation des données.", 0, $e);
    }
  }

  /**
   * oneRow
   * @ Exemple de requete :
   * @ $requete = "SELECT * FROM users WHERE id = :id AND nom= :nom";
   * @ $params = ['id' => $userId,'nom'=>$userNom ];
   * @ $result = $this->oneRow($requete, $params);
   * @param  mixed $requete
   * @param  mixed $params
   *
   */
  public function oneRow($requete, $params = [])
  {

    try {
      $q = $this->link->prepare($requete);
      $q->execute($params);
      $variable = $q->fetch(PDO::FETCH_ASSOC);
      return $variable;
    } catch (PDOException $e) {
      error_log("Erreur lors de allRow() : " . $e->getMessage());
      throw new Exception("Une erreur 'fetch' est survenue lors de la manipulation des données.", 0, $e);
    }
  }

  /**
   *? handleRow
   *
   ** $insertQuery = "INSERT INTO users (name, email) VALUES (:name, :email)";
   ** $params = [
   ** 'name' => $userName,
   ** 'email' => $userEmail
   ** ];
   *
   ** $updateQuery = "UPDATE users SET email = :email WHERE id = :id";
   ** $params = [
   ** 'email' => $newEmail,
   ** 'id' => $userId
   ** ];
   *
   *
   ** $deleteQuery = "DELETE FROM users WHERE id = :id";
   ** $params = [
   ** 'id' => $userId
   ** ];
   *
   ** $lastInsertId = $this->handleRow($insertQuery, $params, 'insert', $idcolla, $contenu);
   ** $this->handleRow($updateQuery, $params, 'update', $idcolla, $contenu);
   ** $this->handleRow($deleteQuery, $params, 'delete', $idcolla, $contenu);
   *
   * @param  mixed $requete
   * @param  mixed $params
   * @param  mixed $operationType
   * @param  mixed $idcolla
   * @param  mixed $contenu
   *
   */
  public function handleRow($requete, $params = [], $operationType = 'Action non définie', $idcolla = null, $contenu = 'Contenu non précisé')
  {
    try {
      $params = $this->sanitizeParams($params);
      $req = $this->link->prepare($requete);
      $req->execute($params);
      $lastInsertId = $this->link->lastInsertId();
      $contenu = empty($contenu) ? "Opération de type '$operationType' effectuée par $idcolla." : $contenu;
      $this->insertLog($operationType, $idcolla, $contenu);
      return $lastInsertId;
    } catch (PDOException $e) {
      error_log("Erreur lors de handleRow() : " . $e->getMessage());
      throw new Exception("Erreur lors de la manipulation de la ligne : " . $e->getMessage(), 0, $e);
    }
  }


  /**
   * sanitizeParams
   *
   * @param  mixed $params
   * 
   */
  private function sanitizeParams($params)
  {
    $sanitizedParams = [];

    foreach ($params as $key => $value) {
      // Vérifier le type de la valeur et la nettoyer si nécessaire
      if (is_string($value)) {
        // Échapper les caractères spéciaux pour les chaînes de caractères
        $sanitizedParams[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
      } elseif (is_int($value) || is_float($value)) {
        // Les entiers et les floats sont considérés comme sûrs
        $sanitizedParams[$key] = $value;
      } elseif (is_bool($value)) {
        // Les booléens sont considérés comme sûrs
        $sanitizedParams[$key] = $value;
      } else {
        // Type non pris en charge, lever une exception
        throw new InvalidArgumentException("Type de paramètre non pris en charge : " . gettype($value));
      }
    }

    return $sanitizedParams;
  }
  /**
   * insertLog
   * Insertion dans log_action type, idcolla, contenu.
   * @param  string $type
   * @param  int $idcolla
   * @param  string $contenu
   */
  public function insertLog(string $type,  $idcolla, string $contenu)
  {
    try {
      // Préparez la requête d'insertion avec des paramètres nommés
      $insert_log = "INSERT INTO log_action (type, idcolla, contenu) VALUES (:type, :idcolla, :contenu)";

      // Créez un tableau de paramètres
      $params = [
        'type' => $type,
        'idcolla' => $idcolla,
        'contenu' => $contenu
      ];

      // Appelez la méthode handleRow avec la requête et les paramètres
      $lastInsertId = $this->handleRow($insert_log, $params);

      return $lastInsertId; // Optionnel : retourner l'ID de la dernière insertion

    } catch (Exception $e) {
      $erreur = '<p style="color:#f80">Nous avons une erreur MySQL : ' . $e->getMessage() . '</p><br/>';
      return $erreur;
    }
  }

  /**
   * montreRows
   *
   * @param  mixed $nomtable
   * @return array
   */
  public function montreRows(string  $nomtable)
  {
    try {
      $select_table = "DESCRIBE $nomtable";
      $params = [];
      $datas = $this->allRow($select_table, $params);
      $fields = [];
      //prettyc($datas);
      foreach ($datas as $row) {

        $fields[] = [
          'champs' => $row['Field'],
          'type' => $row['Type'],
          'key' => $row['Key'],
          'extra' => $row['Extra'],
          // 'Type' => $row['Type'],
          // 'Null' => $row['Null'],
          // 'Clef' => $row['Key'],
          // 'Default' => $row['Default'],
          // 'Extra' => $row['Extra']
        ];
      }
      $modele = $this->generateSQLModels($nomtable, $fields);
      prettyc($fields);
      return $fields;
    } catch (Exception $e) {
      $erreur = [
        'error' => 'Nous avons une erreur MySQL : ' . $e->getMessage()
      ];
      return $erreur;
    }
  }

  public function generateSQLModels($tableName, $columns)
  {

    $insertColumns = [];
    $insertValues = [];
    $updateSet = [];
    $whereClause = [];

    foreach ($columns as $column) {
      $value = $column['champs'];
      $key = $column['key'];

      if ($key != 'PRI' && $value != 'time_maj' && $value != 'time') {
        $paramstr[] = "'" . $value . "'=>$" . $value;
        $insertColumns[] = $value;
        $insertValues[] = ':' . $value;
        $updateSet[] = $value . '=:' . $value;
      }
      if ($key === 'PRI') {
        $whereClause[] = $value . '=:' . $value;
      }
      // if ($value === 'idcompte') {
      //     $whereClause[] = $value . '=:' . $value;
      // }
    }
    // foreach ($columns as $column => $type) {

    //   foreach ($type as $value) {
    //     $paramstr[] = "'" . $value . "'=>$" . $value;
    //     $insertColumns[] = $value;
    //     $insertValues[] = ':' . $value;
    //     $updateSet[] = $value . '=:' . $value;
    //     if ($value === 'idcompte') {
    //       $whereClause[] = $value . '=:' . $value;
    //     }
    //   }
    //   //$insertColumns[] = $column;
    //   // 
    //   // $updateSet[] = $column . ' = ' . (is_numeric($type) ? 'valeur_' . $column : "'valeur_" . $column . "'");
    //   // if ($column === 'idcompte') {
    //   //   $whereClause[] = $column . " = 'valeur_" . $column . "'";
    //   // }
    // }

    $paramStr = implode(', ', $paramstr);
    $insertColumnsStr = implode(',', $insertColumns);
    $insertValuesStr = implode(',', $insertValues);
    $updateSetStr = implode(',', $updateSet);
    $whereClauseStr = implode(' AND ', $whereClause);

    $params = "<b class='text-primary'>\$params</b> = [" . $paramStr . "]";
    $insertModel = "<b class='text-primary'>INSERT</b> INTO $tableName ($insertColumnsStr) VALUES ($insertValuesStr)";
    $updateModel = "<b class='text-primary'>UPDATE</b> $tableName  SET $updateSetStr WHERE $whereClauseStr";
    $deleteModel = "<b class='text-primary'>DELETE</b> FROM $tableName WHERE $whereClauseStr";
    $handleRow = "\$magesquo->handleRow('actionIUD', \$params, 'quoi/titre', 'qui/iduser', 'message... \$client->showNomClient(\$idcli)');";

    return [
      'param' => $params,
      'insert' => $insertModel,
      'update' => $updateModel,
      'delete' => $deleteModel,
      'handle' => $handleRow,
    ];
  }


  /**
   * montreTables
   *
   * @return array
   */
  public function montreTables(): array
  {
    try {
      // Récupérer toutes les tables de la base de données actuelle
      $select_tables = "SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE()";
      $params = [];
      $result = $this->allRow($select_tables, $params);
      $tables = [];
      foreach ($result as $row) {
        $tables[] = $row['TABLE_NAME'];
      }
      return ($tables);
    } catch (Exception $e) {
      $erreur = [
        'error' => 'Nous avons une erreur MySQL : ' . $e->getMessage()
      ];
      return $erreur;
    }
  }

  public function montreContenu($table)
  {
    try {
      // Récupérer toutes les tables de la base de données actuelle
      $select_tables = "SELECT * FROM $table WHERE 1 limit 3";
      $params = [];
      $result = $this->allRow($select_tables, $params);
      // $tables = [];
      // foreach ($result as $k=>$v) {
      //   $tables[] = $k.' '.$v;
      // }
      return (prettyc($result));
    } catch (Exception $e) {
      $erreur = [
        'error' => 'Nous avons une erreur MySQL : ' . $e->getMessage()
      ];
      return $erreur;
    }
  }

  /**
   * bilanData
   *
   * @param  mixed $idcompte
   * @param  mixed $table
   *
   */
  public function bilanData(string $idcompte, string $iduser = '', string $table): array
  {
    $stmt = $this->link->prepare(query: "SELECT * FROM $table WHERE idcompte = :idcompte LIMIT 1");
    $stmt->bindParam(':idcompte', $idcompte, PDO::PARAM_STR);
    //$stmt->bindParam(':statut', 'admin', PDO::PARAM_STR);//AND id = :iduser
    $stmt->execute();
    $data = $stmt->fetch(mode: PDO::FETCH_ASSOC);
    $missingFieldsCount = 0;
    $completeFieldsCount = 0;
    $val_ok = [];
    $val_nok = [];
    foreach ($data as $key => $value) {
      if (is_null(value: $value) || $value === '') {
        if (!isset($val_nok[$key])) {
          $val_nok[$key] = '';
        }
        $val_nok[$key] .= $value ?? '';
        $missingFieldsCount++;
      } else {
        if (!isset($val_ok[$key])) {
          $val_ok[$key] = '';
        }
        $val_ok[$key] .= $value ?? '';
        $completeFieldsCount++;
      }
    }
    $total = $completeFieldsCount + $missingFieldsCount;
    $pourcentage = $completeFieldsCount * 100 / $total;
    return ['data_ok' => $val_ok, 'data_nok' => $val_nok, 'nbr_manquant' => $missingFieldsCount, 'nbr_rempli' => $completeFieldsCount, 'pourcentage' => $pourcentage];
  }







  //********** A TRANSFERE SUR AUTRE CLASS */

  /**
   * askIdcompte
   * Basée sur 'idcompte_infos'
   * @param  mixed $idcompte
   * @param  mixed $champ
   * @return array
   */
  public function askIdcompteAA(string $idcompte, string $champ = "*"): mixed
  {
    // Valider le format de l'identifiant (par exemple, alphanumérique)
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $idcompte)) {
      throw new InvalidArgumentException("Identifiant de compte invalide.");
    }

    // Préparer la requête sécurisée
    $sql = "SELECT $champ FROM idcompte_infos WHERE idcompte = :idcompte";

    try {
      // Exécuter la requête avec des paramètres sécurisés
      $stmt = $this->link->prepare($sql); // Supposons que $this->db est une instance PDO
      $stmt->bindParam(':idcompte', $idcompte, PDO::PARAM_STR);
      $stmt->execute();

      // Récupérer une ligne sous forme de tableau associatif
      $infos = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$infos) {
        return null; // Pas de données trouvées
      }

      return $infos; // Retourner les informations directement
    } catch (PDOException $e) {
      // Gérer les erreurs de la base de données
      error_log(message: "Erreur SQL : " . $e->getMessage());
      throw new RuntimeException(message: "Une erreur s'est produite lors de la récupération des informations.");
    }
  }




  public function askDossierIntervenant($id, $champ = "*")
  {
    $requette = "select $champ from users where idusers like '$id'";
    $infos = $this->oneRow($requette);
    $data = array();
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    return $data;
  }
  /**
   * askClient
   * Basée sur 'client_chantier'
   * @param  mixed $id
   * @param  mixed $champ
   * @return array
   */
  public function askClient($id, $champ = "*")
  {
    $requette = "select $champ from client_chantier where idcli like '$id'";
    $infos = $this->oneRow($requette);
    $data = array();
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    return $data;
  }
  public function askClientFull($id)
  {
    $requette = "SELECT client_chantier.*, client_infos.* FROM client_chantier INNER JOIN client_infos ON client_chantier.idcli = client_infos.idcli WHERE client_chantier.idcli = '$id'; ";
    $infos = $this->oneRow($requette);
    // $data = array();
    // foreach ($infos as $k => $v) {
    //     $data[$k] =  $v;
    // }
    return $infos;
  }
  public function askAllClient($secteur)
  {
    $requette = "select * from client_chantier where idcompte='$secteur' order by nom asc";
    $infos = $this->allRow($requette);
    // $data = array();
    // foreach ($infos as $k => $v) {
    //     $data[$k] =  $v;
    // }
    return $infos;
  }
  public function askClientRegulier($secteur)
  {
    $requette = "select * from client_infos where idcompte='$secteur' and type like 'Rég%' or idcompte='$secteur' and type like 'Reg%' ";
    $infos = $this->allRow($requette);
    return $infos;
  }
  public function askClientAdresse($id, $champ = "*")
  {
    $requette = "select $champ from client_adresse where idcli like '$id'";
    $infos = $this->oneRow($requette);
    $data = array();
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    return $data;
  }
  public function askClientMail($email)
  {
    $requette = "select * from client_chantier where email like '$email'";
    $infos = $this->oneRow($requette);
    $data = array();
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    return $data;
  }
  public function askUserMail($email)
  {
    $requette = "select * from users_sagaas where email like '$email'";
    $infos = $this->oneRow($requette);
    $data = array();
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    return $data;
  }
  public function askClientInfos($id, $champ = "*")
  {
    $requette = "select $champ from client_infos where idcli like '$id'";
    $infos = $this->oneRow($requette);
    $data = array();
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    return $data;
  }
  public function checkClient($nom, $prenom, $adresse, $cp)
  {
    $requette = "
          SELECT c.nom, c.prenom, cc.adresse, cc.cp 
          FROM client c
          JOIN client_correspondance cc ON c.idcli = cc.idcli
          WHERE c.nom = :nom 
          AND c.prenom = :prenom 
          AND cc.adresse = :adresse 
          AND cc.cp = :cp 
          LIMIT 1
      ";
    $params = [
      ':nom' => $nom,
      ':prenom' => $prenom,
      ':adresse' => $adresse,
      ':cp' => $cp
    ];
    $infos = $this->oneRow($requette, $params);
    $data = array();
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    return $data;
  }
  public function askNotes($id, $champ = "*")
  {
    $requette = "select $champ from client_notes where idcli like '$id'";
    $infos = $this->allRow($requette);
    // $data = array();
    // foreach ($infos as $k => $v) {
    //     $data[$k] =  $v;
    // }
    return $infos;
  }
  public function askNotesSecteur($secteur, $champ = "*")
  {
    $requette = "select $champ from client_notes where idcompte like '$secteur' order by rappel asc";
    $infos = $this->allRow($requette);
    // $data = array();
    // foreach ($infos as $k => $v) {
    //     $data[$k] =  $v;
    // }
    return $infos;
  }
  public function askBank($secteur, $champ = "*")
  {
    $requette = "select $champ from bank where cs like '$secteur'";
    $infos = $this->allRow($requette);
    //$data = array();
    // foreach ($infos as $k => $v) {
    //     $data[$k] =  $v;
    // }
    return $infos;
  }
  public function askBankDefault($secteur, $champ = "*")
  {
    $requette = "select $champ from bank where cs like '$secteur' and defaut='1'";
    $infos = $this->allRow($requette);
    //$data = array();
    // foreach ($infos as $k => $v) {
    //     $data[$k] =  $v;
    // }
    return $infos;
  }
  public function askFactureNum($secteur, $numero, $champ = "*")
  {
    $requette = "select $champ from facturesentete where cs like '$secteur' and numero='$numero'";
    $infos = $this->oneRow($requette);
    $data = array();
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    return $data;
  }
  public function askTransac($secteur,  $champ = "*")
  {
    $requette = "select $champ from transactions where cs like '$secteur'";
    $infos = $this->allRow($requette);
    $data = array();
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    return $data;
  }
  public function askTransacNum($secteur, $numero,  $champ = "*")
  {
    $requette = "select $champ from transactions where cs like '$secteur' and id like '$numero' limit 1";
    $infos = $this->oneRow($requette);
    $data = array();
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    return $data;
  }
  /**
   * askTrackNumFact
   * recherche de la facture grace au numero 0000-0000 à partir des relevés Bridge
   *
   * @param  mixed $secteur
   * @param  mixed $numero
   * @param  mixed $champ
   *
   */
  public function askTrackNumFact($secteur, $numero, $champ = "*")
  {
    $requette = "select $champ from facturesentete where cs like '$secteur' and numero like '%$numero%'";
    $infos = $this->oneRow($requette);
    $data = array();
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    return $data;
  }
  public function askTrackCliFact($secteur, $cli, $m, $champ = "*")
  {
    $requette = "select $champ from facturesentete where cs like '$secteur' and nom = '$cli' and totttc = '$m'";
    $infos = $this->oneRow($requette);
    $data = array();
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    return $data;
  }
  public function askRefresh($secteur, $champ = "*")
  {
    $requette = "select $champ from webhooks where idcompte like '$secteur' order by time_maj desc";
    $infos = $this->allRow($requette);
    // $data = array();
    // foreach ($infos as $k => $v) {
    //     $data[$k] =  $v;
    // }
    return $infos;
  }
  public function askDevisNum($secteur, $numero, $champ = "*")
  {
    $requette = "select $champ from devisestimatif where cs like '$secteur' and numero='$numero'";
    $infos = $this->oneRow($requette);
    $data = array();
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    return $data;
  }
  /**
   * askAllFacture
   * Basée sur 'facturesentete'
   * @param  mixed $secteur
   * @param  mixed $annref
   * @param  mixed $champ
   * @return array
   */
  public function askAllFacture($secteur, $criteres, $champ = "*")
  {
    $requette = "select $champ from facturesentete where cs like '$secteur' $criteres order by numdev desc";
    $infos = $this->allRow($requette);
    // $data = array();
    // foreach ($infos as $k => $v) {
    //     $data[$k] =  $v;
    // }
    return $infos;
  }
  public function askRelance($secteur, $idcli)
  {
    $data = array();
    $delj = $this->askIdcompteAA($secteur, 'delj');
    $j = $delj['delj'];
    $requette = "select * from facturesentete where  id ='$idcli' and paye='non' AND DATEDIFF(CURDATE(), DATE(datefact)) > $j ";
    $infos = $this->allRow($requette);
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    return $data;
    // return array(
    //     "commercial"=>$data['commercial'],
    //     "nom" => $data['civilite'] . ' ' . $data['prenom'] . ' ' . $data['nom'],
    //     "idcli" => $data['id']
    // );
  }
  public function askFactureLigne($secteur, $numero, $champ = "*")
  {
    $requette = "select $champ from factureslignes where cs like '$secteur' and numero='$numero'";
    $infos = $this->allRow($requette);
    $data = array();
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    // if($infos){$data='existe';}else{$data='no data';}
    return $data;
  }
  public function askDevisLigne($numero, $champ = "*")
  {
    $requette = "select $champ from devislignes where numero='$numero'";
    $infos = $this->allRow($requette);
    $data = array();
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    // if($infos){$data='existe';}else{$data='no data';}
    return $data;
  }
  public function askAllDevis($secteur, $annref, $champ = "*")
  {
    $requette = "select $champ from devisestimatif where cs like '$secteur' $annref order by numdev asc";
    $infos = $this->allRow($requette);
    // $data = array();
    // foreach ($infos as $k => $v) {
    //     $data[$k] =  $v;
    // }
    return $infos;
  }
  public function landingDevis()
  {
    $requette = "select * from devisestimatif ";
    $infos = $this->allRow($requette);
    $nbr = count($infos);
    return $nbr;
  }
  public function landingFacture()
  {
    $requette = "select * from facturesentete ";
    $infos = $this->allRow($requette);
    $nbr = count($infos);
    return $nbr;
  }
  public function landingClient()
  {
    $requette = "select * from client_chantier ";
    $infos = $this->allRow($requette);
    $nbr = count($infos);
    return $nbr;
  }
  public function landingReglements()
  {
    $requette = "select * from reglements ";
    $infos = $this->allRow($requette);
    $nbr = count($infos);
    return $nbr;
  }
}
