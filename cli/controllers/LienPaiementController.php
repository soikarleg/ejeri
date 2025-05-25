<?php
// filepath: cli/controllers/LienPaiementController.php
// Contrôleur CRUD pour la table lien_paiement

require_once __DIR__ . '/../models/LienPaiementModel.php';

class LienPaiementController
{
    private $model;

    public function __construct($compte_id)
    {
        $this->model = new LienPaiementModel($compte_id);
    }

    public function index()
    {
        return $this->model->getAll();
    }

    public function show($id)
    {
        return $this->model->getById($id);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        return $this->model->update($id, $data);
    }

    public function delete($id)
    {
        return $this->model->delete($id);
    }
}
