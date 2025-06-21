<?php
class AdresseModel
{
    private $pdo;
    private $client_id;

    public function __construct(PDO $pdo, int $client_id)
    {
        $this->pdo = $pdo;
        $this->client_id = $client_id;
    }

    public function getAllByClient($client_id)
    {
        $sql = "SELECT * FROM adresses WHERE user_idcli = :client_id ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['client_id' => $client_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAdresseById($id)
    {
        $sql = "SELECT * FROM adresses WHERE id = :id AND user_idcli = :client_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id, 'client_id' => $this->client_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function ajoutAdresse(string $ligne1, string $cp, string $ville, string $type = 'livraison')
    {
        $sql = "INSERT INTO adresses (ligne1, cp, ville, user_idcli, type) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$ligne1, $cp, $ville, $this->client_id, $type]);
        return $this->pdo->lastInsertId();
    }
    
    public function updateAdresse($id, $ligne1, $cp, $ville)
    {
        $sql = "UPDATE adresses SET ligne1 = ?, cp = ?, ville = ? WHERE id = ? AND user_idcli = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$ligne1, $cp, $ville, $id, $this->client_id]);
    }

    public function deleteAdresse($id)
    {
        $sql = "DELETE FROM adresses WHERE id = ? AND user_idcli = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id, $this->client_id]);
    }
}
