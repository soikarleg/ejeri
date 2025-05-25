<?php

class authBase
{
    private $link;

    public function __construct()
    {
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
            echo '<p style="color:#f80">Nous avons une erreur MySQL : ' . $e->getMessage() . '</p><br/>';
            die();
        }
    }

    public function ConnexionMag()
    {
        return $this->link;
    }

    public function allRowAuth($requete)
    {
        try {
            $q = $this->link->prepare($requete);
            $q->execute();
            $variable = $q->fetchAll(PDO::FETCH_ASSOC);
            return $variable;
        } catch (PDOException $e) {
            error_log('Erreur dans la requête : ' . $e->getMessage());
            return false;
        }
    }

    public function oneRowAuth($requete)
    {
        try {
            $q = $this->link->prepare($requete);
            $q->execute();
            $variable = $q->fetch(PDO::FETCH_ASSOC);
            return $variable;
        } catch (PDOException $e) {
            error_log('Erreur dans la requête : ' . $e->getMessage());
            return false;
        }
    }

    public function handleRowAuth($requet)
    {
        try {
            $req = $this->link->prepare($requet);
            $req->execute();
            $last = $this->link->lastInsertId();
            return $last;
        } catch (PDOException $e) {
            $message = "Erreur : " . $e;
            return $message;
        }
    }

    public function askUsers($id)
    {
        $requser = "SELECT * FROM users_sagaas WHERE id = '$id' ";
        $user = $this->oneRowAuth($requser);
        return $user;
    }
}
