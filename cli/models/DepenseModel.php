<?php
// filepath: cli/models/DepenseModel.php
// Modèle MVC pour la table depenses

require_once __DIR__ . '/Database.php';

class DepenseModel
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
        $stmt = $this->pdo->prepare("SELECT * FROM depenses WHERE compte_id = ?");
        $stmt->execute([$this->compte_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM depenses WHERE id = ? AND compte_id = ?");
        $stmt->execute([$id, $this->compte_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO depenses (compte_id, date_depense, montant, categorie, description, commentaire) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $this->compte_id,
            $data['date_depense'],
            $data['montant'],
            $data['categorie'],
            $data['description'],
            $data['commentaire']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE depenses SET date_depense=?, montant=?, categorie=?, description=?, commentaire=? WHERE id=? AND compte_id=?");
        return $stmt->execute([
            $data['date_depense'],
            $data['montant'],
            $data['categorie'],
            $data['description'],
            $data['commentaire'],
            $id,
            $this->compte_id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM depenses WHERE id = ? AND compte_id = ?");
        return $stmt->execute([$id, $this->compte_id]);
    }
}
