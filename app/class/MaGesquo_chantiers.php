<?php

use MaGesquo;

class Chantiers extends MaGesquo
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
     * chantierClient
     * Ramène les infos de client client_chantier
     * @param  mixed $idcli
     * @return array
     */
    public function chantierClient($idcli): array
    {
        $param = ['idcompte' => $this->idcompte, 'idcli' => $idcli,];
        $requette = "SELECT * FROM `client_chantier_vue` WHERE `chantier_idcli`=:idcli AND `chantier_idcompte`= :idcompte";
        $infos = $this->conn->allRow($requette, $param);
        if (empty($infos)) {
            return [];
        }
        return $infos;
    }

    public function chantierClientUnique($idcli, $idchantier): array
    {
        $param = ['idcompte' => $this->idcompte, 'idcli' => $idcli, 'idchantier' => $idchantier];
        $requette = "SELECT * FROM `client_chantier_vue` WHERE `chantier_id`=:idchantier AND `chantier_idcli`=:idcli AND `chantier_idcompte`= :idcompte";
        $infos = $this->conn->oneRow($requette, $param);
        if (empty($infos)) {
            return [];
        }
        return $infos;
    }
    

}