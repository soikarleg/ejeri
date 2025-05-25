<?php
// filepath: cli/models/PaiementModel.php
// Modèle MVC pour la table paiement

require_once __DIR__ . '/Database.php';

class PaiementModel
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
        $stmt = $this->pdo->prepare("SELECT * FROM paiement WHERE compte_id = ?");
        $stmt->execute([$this->compte_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM paiement WHERE id = ? AND compte_id = ?");
        $stmt->execute([$id, $this->compte_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO paiement (compte_id, facture_id, montant, date_paiement, mode, reference, commentaire) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $this->compte_id,
            $data['facture_id'],
            $data['montant'],
            $data['date_paiement'],
            $data['mode'],
            $data['reference'],
            $data['commentaire']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE paiement SET facture_id=?, montant=?, date_paiement=?, mode=?, reference=?, commentaire=? WHERE id=? AND compte_id=?");
        return $stmt->execute([
            $data['facture_id'],
            $data['montant'],
            $data['date_paiement'],
            $data['mode'],
            $data['reference'],
            $data['commentaire'],
            $id,
            $this->compte_id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM paiement WHERE id = ? AND compte_id = ?");
        return $stmt->execute([$id, $this->compte_id]);
    }
}
