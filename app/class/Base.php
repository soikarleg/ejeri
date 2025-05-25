<?php
class connBase
{
    private $link;
    public function __construct()
    {
        $chemin = $_SERVER['DOCUMENT_ROOT'];
        $chemin = rtrim($chemin, '/app');
        //$chemin = __DIR__;
        $base = include $chemin . '/app/config/base_ionos.php';
        // base_ionos_old.php pour ancienne base.
        $host_name = $base['db_host'];
        $database = $base['db_name'];
        $user_name = $base['db_user'];
        $password = $base['db_password'];

        try {
            $this->link = new \PDO("mysql:host=$host_name; dbname=$database;", $user_name, $password);
        } catch (PDOException $e) {
            //' . $e->getMessage() . '
            $erreur = '<p style="color:#f80">Nous avons une erreur MySQL</p><br/>';
            return $erreur;
            die();
        }
    }

    public function ConnexionMag()
    {
        return $this->link;
    }
    public function allRow($requete)
    {
        try {
            $q = $this->link->prepare($requete);
            $q->execute();
            $variable = $q->fetchAll(PDO::FETCH_ASSOC);
            return $variable;
        } catch (PDOException $e) {
            // $message = "Erreur : " . $e->getMessage();
            // return $message;
            throw new Exception("Erreur lors allRow() de la manipulation de la ligne : " . $e->getMessage(), 0, $e);
        }
    }
    public function oneRow($requete)
    {
        try {
            $q = $this->link->prepare($requete);
            $q->execute();
            $variable = $q->fetch(PDO::FETCH_ASSOC);
            return $variable;
        } catch (PDOException $e) {
            // $message = "Erreur : " . $e->getMessage();
            // return $message;
            throw new Exception("Erreur lors oneRow() de la manipulation de la ligne : " . $e->getMessage(), 0, $e);
        }
    }
    public function handleRow($requet)
    {
        try {
            $req = $this->link->prepare(query: $requet);
            $req->execute();
            $last = $this->link->lastInsertId();
            return $last;
        } catch (PDOException $e) {
            // $message = "Erreur : " . $e->getMessage();
            // return $message;
            throw new Exception("Erreur lors handleRow() de la manipulation de la ligne : " . $e->getMessage(), 0, $e);
        }
    }
    /**
     * insertLog
     * Insertion dans log_action type, idcolla, contenu.
     * @param  mixed $type
     * @param  mixed $idcolla
     * @param  mixed $contenu
     *
     */
    public function insertLog(string $type, int $idcolla, string $contenu)
    {
        try {
            $insert_log = "INSERT INTO log_action (type, idcolla, contenu) VALUES ('$type', '$idcolla', '$contenu')";
            $this->handleRow($insert_log);
        } catch (Exception $e) {
            $erreur = '<p style="color:#f80">Nous avons une erreur MySQL : ' . $e->getMessage() . '</p><br/>';
            return $erreur;
        }
    }
    /**
     * askIdcompte
     * Basée sur 'idcompte_infos'
     * @param  mixed $idcompte
     * @param  mixed $champ
     * @return array
     */
    // public function askIdcompte(string $idcompte, $champ = "*")
    // {
    //     $requette = "select $champ from idcompte_infos where idcompte like '$idcompte'";
    //     $infos = $this->oneRow($requette);
    //     $data = array();
    //     if (!$infos) {
    //         return;
    //     } else {
    //         foreach ($infos as $k => $v) {
    //             $data[$k] =  $v;
    //         }
    //         return $data;
    //     }
    // }

    public function askIdcompte(string $idcompte, string $champ = "*")
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
            error_log("Erreur SQL : " . $e->getMessage());
            throw new RuntimeException("Une erreur s'est produite lors de la récupération des informations.");
        }
    }


    /**
     * askIduser
     * Basée sur 'users_infos'
     * @param  mixed $id
     * @param  mixed $champ
     * @return array
     */
    public function askIduser($id, $champ = "*")
    {
        $requette = "select $champ from users_infos where id like '$id'";
        $infos = $this->oneRow($requette);
        $data = array();
        foreach ($infos as $k => $v) {
            $data[$k] =  $v;
        }
        return $data;
    }
    public function showUser($id, $champ = "civilite, nom, prenom, id")
    {
        $requette = "select $champ from users_infos where id like '$id'";
        $infos = $this->oneRow($requette);
        $data = array();
        foreach ($infos as $k => $v) {
            $data[$k] =  $v;
        }
        $show = '<span class="px-0 py-2"><span class="small puce-mag mr-1">N° ' . $data['id'] . '</span><span class="small text-muted mr-1">' . $data['civilite'] . '</span><span class="small text-muted mr-1">' . $data['prenom'] . '</span><span class="small text-muted">' . $data['nom'] . '</span></span>';
        return $show;
    }



    public function askDossierIntervenant($id, $champ = "*")
    {
        $requette = "select $champ from users_sagaas where idusers like '$id'";
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
    /**
     * checkClient
     * Basée sur 'client_chantier'
     * @param  mixed $nom
     * @param  mixed $prenom
     * @param  mixed $adresse
     * @param  mixed $cp
     * @return array
     */
    public function checkClient($nom, $prenom, $adresse, $cp)
    {
        $requette = "select * from client_chantier where nom = '$nom' and prenom='$prenom' and adresse = '$adresse' and cp = '$cp' limit 1";
        $infos = $this->oneRow($requette);
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
        $delj = $this->askIdcompte($secteur, 'delj');
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
