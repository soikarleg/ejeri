<?php
// filepath: cli/models/ProductionModel.php
// Modèle MVC pour la table production

require_once __DIR__ . '/Database.php';

class ProductionModel
{
    private $pdo;
    private $compte_id;

    public function __construct($compte_id)
    {
        $this->pdo = Database::getConnection();
        $this->compte_id = $compte_id;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM production WHERE compte_id = ?");
        $stmt->execute([$this->compte_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM production WHERE id = ? AND compte_id = ?");
        $stmt->execute([$id, $this->compte_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO production (compte_id, event_id, intervenant_id, heures, statut, commentaire) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $this->compte_id,
            $data['event_id'],
            $data['intervenant_id'],
            $data['heures'],
            $data['statut'],
            $data['commentaire']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE production SET event_id=?, intervenant_id=?, heures=?, statut=?, commentaire=? WHERE id=? AND compte_id=?");
        return $stmt->execute([
            $data['event_id'],
            $data['intervenant_id'],
            $data['heures'],
            $data['statut'],
            $data['commentaire'],
            $id,
            $this->compte_id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM production WHERE id = ? AND compte_id = ?");
        return $stmt->execute([$id, $this->compte_id]);
    }
}
