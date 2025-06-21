<?php
// models/ClientModel.php
class ClientModel
{
    private $pdo;
    public function __construct()
    {
        $config = require __DIR__ . '/../config/mysql.php';
        $this->pdo = new PDO(
            "mysql:host={$config['host_name']};dbname={$config['database']};charset=utf8mb4",
            $config['user_name'],
            $config['password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
    // Exemple de méthode :
    public function getClientById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE idcli = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
        public function getNomById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM clients WHERE idcli = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getClientByEmail($email)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function clientExistsByEmail($email)
    {
        $sql = "SELECT COUNT(*) FROM clients WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

public function updateNomById($id, $nom, $prenom, $civilite = null)
{
    $sql = "UPDATE clients SET nom = :nom, prenom = :prenom" . ($civilite !== null ? ", civilite = :civilite" : "") . " WHERE idcli = :id";
    $stmt = $this->pdo->prepare($sql);
    $params = [
        'nom' => $nom,
        'prenom' => $prenom,
        'id' => $id
    ];
    if ($civilite !== null) {
        $params['civilite'] = $civilite;
    }
    return $stmt->execute($params);
}
   
   
}
