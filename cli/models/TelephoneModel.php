<?php
class TelephoneModel
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
        $sql = "SELECT * FROM telephones WHERE client_id = :client_id AND compte_id = :compte_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['client_id' => $client_id, 'compte_id' => $this->compte_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
