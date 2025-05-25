<?php
class AdresseModel
{
    private $pdo;
    private $compte_id;

    public function __construct(PDO $pdo, int $compte_id)
    {
        $this->pdo = $pdo;
        $this->compte_id = $compte_id;
    }

    public function getAllByClient($client_id)
    {
        $sql = "SELECT * FROM adresses WHERE client_id = :client_id AND compte_id = :compte_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['client_id' => $client_id, 'compte_id' => $this->compte_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO adresses (compte_id, type, ligne1, ligne2, cp, ville, pays, client_id, user_idcli, organisation_id, description)
                VALUES (:compte_id, :type, :ligne1, :ligne2, :cp, :ville, :pays, :client_id, :user_idcli, :organisation_id, :description)";
        $stmt = $this->pdo->prepare($sql);
        $data['compte_id'] = $this->compte_id;
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    // Ajoute ici update, delete, getById, etc.
}
?>