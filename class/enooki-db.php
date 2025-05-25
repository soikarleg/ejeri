<?php

class enookiDB
{
    private $pdo;

    public function __construct()
    {
        // Charger la configuration
        $chemin = $_SERVER['DOCUMENT_ROOT'];
        $configPath = $chemin . '/config/mysql.php';
        $logPath = $chemin . '/log/enooki_error.log';

        if (!file_exists($configPath)) {
            die("Erreur : fichier de configuration introuvable.");
        }

        $config = include $configPath;

        // Récupérer les paramètres de connexion
        $host = $config['host_name'] ?? 'localhost';
        $dbname = $config['database'] ?? '';
        $username = $config['user_name'] ?? 'root';
        $password = $config['password'] ?? '';

        // Vérifier que les paramètres essentiels sont définis
        if (empty($dbname)) {
            error_log("[" . date('Y-m-d H:i:s') . "] Erreur : le nom de la base de données n'est pas défini.\n", 3, $logPath);
            die("Erreur : le nom de la base de données n'est pas défini.");
        }


        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Enregistrer l'erreur dans un fichier de log (optionnel)
            error_log("[" . date('Y-m-d H:i:s') . "]\nENOOKI - Erreur de connexion à la base de données : " . $e->getMessage() . "\n", 3, $logPath);

            die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
        }
    }

    public function prepare($query)
    {
        return $this->pdo->prepare($query);
    }
    // Méthode pour insérer des données
    public function insert($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    // Méthode pour sélectionner des données
    public function select($table, $conditions = [], $columns = "*")
    {
        $sql = "SELECT $columns FROM $table";
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "$key = :$key";
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($conditions);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour mettre à jour des données
    public function update($table, $data, $conditions)
    {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $where = [];
        foreach ($conditions as $key => $value) {
            $where[] = "$key = :$key";
        }
        $sql = "UPDATE $table SET " . implode(", ", $set) . " WHERE " . implode(" AND ", $where);
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array_merge($data, $conditions));
    }

    // Méthode pour supprimer des données
    public function delete($table, $conditions)
    {
        $where = [];
        foreach ($conditions as $key => $value) {
            $where[] = "$key = :$key";
        }
        $sql = "DELETE FROM $table WHERE " . implode(" AND ", $where);
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($conditions);
    }
}
