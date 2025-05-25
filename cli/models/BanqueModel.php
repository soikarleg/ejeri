<?php
// filepath: cli/models/BanqueModel.php
// Modèle MVC pour la table banque

require_once __DIR__ . '/Database.php';

class BanqueModel
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
        $stmt = $this->pdo->prepare("SELECT * FROM banque WHERE compte_id = ?");
        $stmt->execute([$this->compte_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM banque WHERE id = ? AND compte_id = ?");
        $stmt->execute([$id, $this->compte_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO banque (compte_id, nom, iban, bic, titulaire, commentaire) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $this->compte_id,
            $data['nom'],
            $data['iban'],
            $data['bic'],
            $data['titulaire'],
            $data['commentaire']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE banque SET nom=?, iban=?, bic=?, titulaire=?, commentaire=? WHERE id=? AND compte_id=?");
        return $stmt->execute([
            $data['nom'],
            $data['iban'],
            $data['bic'],
            $data['titulaire'],
            $data['commentaire'],
            $id,
            $this->compte_id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM banque WHERE id = ? AND compte_id = ?");
        return $stmt->execute([$id, $this->compte_id]);
    }
}
