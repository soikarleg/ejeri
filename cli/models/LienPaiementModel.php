<?php
// filepath: cli/models/LienPaiementModel.php
// Modèle MVC pour la table lien_paiement

require_once __DIR__ . '/Database.php';

class LienPaiementModel
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
        $stmt = $this->pdo->prepare("SELECT * FROM lien_paiement WHERE compte_id = ?");
        $stmt->execute([$this->compte_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM lien_paiement WHERE id = ? AND compte_id = ?");
        $stmt->execute([$id, $this->compte_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO lien_paiement (compte_id, facture_id, url, statut, date_creation, date_expiration) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $this->compte_id,
            $data['facture_id'],
            $data['url'],
            $data['statut'],
            $data['date_creation'],
            $data['date_expiration']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE lien_paiement SET facture_id=?, url=?, statut=?, date_creation=?, date_expiration=? WHERE id=? AND compte_id=?");
        return $stmt->execute([
            $data['facture_id'],
            $data['url'],
            $data['statut'],
            $data['date_creation'],
            $data['date_expiration'],
            $id,
            $this->compte_id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM lien_paiement WHERE id = ? AND compte_id = ?");
        return $stmt->execute([$id, $this->compte_id]);
    }
}
