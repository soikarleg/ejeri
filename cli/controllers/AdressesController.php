<?php
// controllers/AdressesController.php
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/AdresseModel.php';

class AdressesController
{
    private $pdo;
    private $client_id;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->client_id = $_SESSION['client_id'] ?? null;
    }

    private function getSidemenuData($client_id)
    {
        require_once __DIR__ . '/../models/Database.php';
        require_once __DIR__ . '/../models/AdresseModel.php';
        require_once __DIR__ . '/../models/ClientModel.php';
        $pdo = Database::getConnection();
        $adresseModel = new AdresseModel($pdo, $client_id);
        $clientModel = new ClientModel($pdo, $client_id);
        $adresses = $adresseModel->getAllByClient($client_id);
        $client = $clientModel->getNomById($client_id);
        //pretty($client);
        return [
            'adresses' => $adresses,
            'client' => $client
        ];
    }


    // Affiche la liste des adresses du client
    public function index()
    {
        $adresseModel = new AdresseModel($this->pdo, $this->client_id);
        $adresses = $adresseModel->getAllByClient($this->client_id);
        require PROJECT_ROOT . '/cli/views/adresses/index.php';
    }

    // Affiche le formulaire d'ajout
    public function create()
    {
        require PROJECT_ROOT . '/cli/views/adresses/create.php';
    }

    // Traite l'ajout d'une adresse
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ligne1 = trim($_POST['ligne1'] ?? '');
            $cp = trim($_POST['cp'] ?? '');
            $ville = trim($_POST['ville'] ?? '');
            $type = trim($_POST['type'] ?? 'livraison');
            $adresseModel = new AdresseModel($this->pdo, $this->client_id);
            $adresseModel->ajoutAdresse($ligne1, $cp, $ville, $type);
            header('Location: /cli/index.php?action=adresses');
            exit;
        }
    }


 
    // Affiche le formulaire d'édition
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $adresseModel = new AdresseModel($this->pdo, $this->client_id);
            $adresse = $adresseModel->getAdresseById($id);
            require PROJECT_ROOT . '/cli/views/adresses/edit.php';
        }
    }

    // Traite la mise à jour
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $ligne1 = trim($_POST['ligne1'] ?? '');
            $cp = trim($_POST['cp'] ?? '');
            $ville = trim($_POST['ville'] ?? '');
            $adresseModel = new AdresseModel($this->pdo, $this->client_id);
            $adresseModel->updateAdresse($id, $ligne1, $cp, $ville);
            header('Location: /cli/index.php?action=adresses');
            exit;
        }
    }

    // Suppression d'une adresse
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $adresseModel = new AdresseModel($this->pdo, $this->client_id);
            $adresseModel->deleteAdresse($id);
        }
        header('Location: /cli/index.php?action=adresses');
        exit;
    }
}
