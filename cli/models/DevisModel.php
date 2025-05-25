<?php
require_once __DIR__ . '/../../shared/helpers/function.php';

class DevisModel
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public function getAllByClient($client_id)
    {
        $trans = $this->pdo->prepare('SELECT id FROM clients WHERE idcli = :client_id ');
        $trans->execute(['client_id' => $client_id]);
        if ($trans->rowCount() === 0) {
            throw new Exception("Client not found");
        }
        $num = $trans->fetch(PDO::FETCH_ASSOC);
        //pretty("Client ID: " . $num['id']);
        if (!$num) {
            throw new Exception("No client found with the provided ID");
        }
        $client_id = $num['id'];
        //pretty("Fetching all devis for client ID: " . $client_id);
        $stmt = $this->pdo->prepare('SELECT * FROM devis WHERE client_id = :client_id ');
        $stmt->execute(['client_id' => $client_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
