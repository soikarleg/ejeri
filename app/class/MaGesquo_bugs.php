<?php

use MaGesquo;

class Bugs extends MaGesquo
{
    private $conn;
    private $idcompte;
    private $iduser;
    private $valid;
    private $csrf;
    private $mail;
    public function __construct($iduser, $idcompte)
    {
        $this->idcompte = $idcompte;
        $this->iduser = $iduser;
        $this->valid = new DataValidator();
        $this->conn = new MaGesquo($this->idcompte);
        $this->mail = new MyMail('enooki - Bugs', 'noreply@magesquo.com');

        $this->csrf = $_SESSION['csrf_token'];
    }
    /**
     * getIduser
     *
     * @return mixed
     */
    public function getIduser(): mixed
    {
        return $this->iduser;
    }
    public function getIdcompte(): mixed
    {
        return $this->idcompte;
    }

    public function getToken(): mixed
    {
        return $this->csrf;
    }
    /**
     * askIduser
     * Basée sur 'users_infos'
     */
    public function askBugs()
    {
        $idcompte = $this->getIdcompte();
        $param = ['idcompte' => $idcompte];
        //prettyc($param);
        $requette = "select * from bug where idcompte = :idcompte";
        $infos = $this->conn->allRow(requete: $requette, params: $param);
        $data = [];
        foreach ($infos as $k => $v) {
            $data[$k] =  $v;
        }
        return $data;
    }


    public function insertBug($idcompte, $data): array
    {
        $erreurs = [];
        foreach ($data as $key => $value) {
            ${$key} = $value; // Assigner la valeur
        }
        $message = DataValidator::purgeStr($message);
        if (!empty($erreurs)) {
            return $erreurs;
        }
        // idcompte page message
        // statut = ouvert, resolu, en cours
        $param = ['idcompte' => $idcompte, 'message' => $message, 'page' => $page, 'statut' => 'ouvert', 'iduser' => $iduser, 'type' => $type];
        //prettyc($param);
        $insert_data = 'INSERT INTO `bug`( `statut`, `idcompte`, `iduser`, `type`, `page`, `message`) VALUES (:statut,:idcompte,:iduser,:type,:page,:message)';
        //$update_data = 'UPDATE users_infos SET nom = :nom, prenom = :prenom,adresse = :adresse,ville = :ville, cp = :cp, telephone = :telephone, portable = :portable, email = :email  WHERE id = :id';
        $this->conn->handleRow($insert_data, $param, 'Bugs', $iduser, 'Déclaration bug/amélioration "' . $message . '" ' . $idcompte . '@' . $iduser);
        $mailidcompte = new Idcompte($idcompte);
        $mail = $mailidcompte->askIdcompte();
        $m = $mail['email'] . ' ' . $mail['nom_legal'];
        $messmail = "<h3>" . strtoupper($type) . "</h3><pre>https://app.enooki.com" . $page . "\n\n" . $idcompte . "-" . $iduser . " " . $m . "\n\n" . $message . "\n\n" . date('l d-m-Y à H:i:s - W') . "</pre>";
        $this->mail->sendEmail('[MaGESQUO] Signalement : ' . $type, $messmail, 'flxxx@flxxx.fr');
        return ['<span class="text-success">Bug signalé, merci.</span>']; // Retourner un tableau vide si tout s'est bien passé
    }

    public function deleteBug($idbug): array
    {

        $iduser = $this->getIduser();
        $idcompte = $this->getIdcompte();
        // idcompte page message
        // statut = ouvert, resolu, en cours
        $param = ['idbug' => $idbug];
        //prettyc($param);


        $delete_data = 'DELETE FROM `bug` WHERE idbug = :idbug';

        $this->conn->handleRow($delete_data, $param, 'Bugs supression', $iduser, 'Effacement bug n°' . $idbug . ' ' . $idcompte . '@' . $iduser);

        return ['<span class="text-warning">Demande effacée</span>']; // Retourner un tableau vide si tout s'est bien passé
    }

    public function inviBug($idbug): array
    {

        $iduser = $this->getIduser();
        $idcompte = $this->getIdcompte();
        // idcompte page message
        // statut = ouvert, resolu, en cours
        $param = ['idbug' => $idbug];
        //prettyc($param);


        $delete_data = 'DELETE FROM `bug` WHERE idbug = :idbug';

        $this->conn->handleRow($delete_data, $param, 'Bugs', $iduser, 'Effacement bug ' . $idbug . ' ' . $idcompte . ' ' . $iduser);

        return ['<span class="text-warning">Demande effacée</span>']; // Retourner un tableau vide si tout s'est bien passé
    }

    public function getIcon($info, $num = null)
    {
        // Définir les icônes pour les types
        $typeIcons = [
            "anomalie" => "<span class='text-bold text-danger' ><span class='mypuce mb-2'>N° $num</span>  " . strtoupper($info) . "</span>",
            "amelioration" => "<span class='text-bold text-info' ><span class='mypuce mb-2'>N° $num</span>  " . strtoupper($info) . "</span>",
            "autre" => "<span class='text-bold' ><span class='mypuce mb-2'>N° $num</span>  " . strtoupper($info) . "</span>",
            "ouvert" => "<span class='ml-2 small text-warning' >" . ucfirst($info) . "</span>",
            "encours" => "<span class='ml-2 small text-info' >" . ucfirst($info) . "</span>",
            "resolu" => "<span class='ml-2 small text-success' >" . ucfirst($info) . "</span>"
        ];
        // Récupérer l'icône pour le type
        $typeIcon = isset($typeIcons[$info]) ? $typeIcons[$info] : "";

        return $typeIcon; // Retourner la combinaison d'icônes
    }
}
