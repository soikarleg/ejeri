<?php
// filepath: cli/models/EventModel.php
// Modèle MVC pour la table event (interventions)

require_once __DIR__ . '/Database.php';

class EventModel
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
        $stmt = $this->pdo->prepare("SELECT * FROM event WHERE compte_id = ?");
        $stmt->execute([$this->compte_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM event WHERE id = ? AND compte_id = ?");
        $stmt->execute([$id, $this->compte_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO event (compte_id, intervenant_id, client_id, adresse_id, date_debut, date_fin, heure_debut, heure_fin, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $this->compte_id,
            $data['intervenant_id'],
            $data['client_id'],
            $data['adresse_id'],
            $data['date_debut'],
            $data['date_fin'],
            $data['heure_debut'],
            $data['heure_fin'],
            $data['description']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE event SET intervenant_id=?, client_id=?, adresse_id=?, date_debut=?, date_fin=?, heure_debut=?, heure_fin=?, description=? WHERE id=? AND compte_id=?");
        return $stmt->execute([
            $data['intervenant_id'],
            $data['client_id'],
            $data['adresse_id'],
            $data['date_debut'],
            $data['date_fin'],
            $data['heure_debut'],
            $data['heure_fin'],
            $data['description'],
            $id,
            $this->compte_id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM event WHERE id = ? AND compte_id = ?");
        return $stmt->execute([$id, $this->compte_id]);
    }
}
