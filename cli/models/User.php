<?php
// filepath: /cli/models/User.php
// Modèle User pour gestion inscription/activation client Enooki (module cli)

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Crée un nouvel utilisateur (statut inactif, token d'activation)
    public function createUser($email, $password, $token)
    {
        $role = 'client'; // Rôle par défaut
        $sql = "INSERT INTO users (role,email, password, activation_token, is_active, created_at) VALUES (?,?, ?, ?, 0, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $result = $stmt->execute([$role, $email, $hashedPassword, $token]);
        // Création de l'organisation pour les rôles admin, intervenant, gerant et ajout de l'ID d'organisation à l'utilisateur
        if ($result && in_array($role, ['admin', 'intervenant', 'gerant'])) {
            // Création de l'organisation
            $userId = $this->pdo->lastInsertId();
            $couleur = '#009400';
            $nom = 'ETS-' . $userId;
            // Insertion de l'organisation dans la base de données
            $stmtOrg = $this->pdo->prepare("INSERT INTO organisations (nom, couleur) VALUES (?,?)");
            $stmtOrg->execute([$nom, $couleur]);
            $orgId = $this->pdo->lastInsertId();
            // Mise à jour de l'utilisateur avec l'ID d'organisation
            $stmtUpdate = $this->pdo->prepare("UPDATE users SET organisation_id = ? WHERE idcli = ?");
            $stmtUpdate->execute([$orgId, $userId]);

            // Création de l'intervenant
            $nominter = ucfirst(explode('@', $email)[0]);
            $stmtInterv = $this->pdo->prepare("INSERT INTO intervenants (idinter, nom, organisation_id, email) VALUES (?,?,?,?)");
            $stmtInterv->execute([$userId, $nominter, $orgId, $email]);
        }
        if ($result && in_array($role, ['client'])) {
            $userId = $this->pdo->lastInsertId();
            // Création du client
            $nominter = ucfirst(explode('@', $email)[0]);
            $stmtInterv = $this->pdo->prepare("INSERT INTO clients (idcli, nom, email) VALUES (?,?,?)");
            $stmtInterv->execute([$userId, $nominter, $email]);
        }

        return $result;
    }

    // Recherche un utilisateur par email
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Recherche un utilisateur par token d'activation
    public function findByToken($token)
    {
        $sql = "SELECT * FROM users WHERE activation_token = ? AND is_active = 0 LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Active un utilisateur via le token
    public function activateUser($token)
    {
        $sql = "UPDATE users SET is_active = 1, activation_token = NULL, activated_at = NOW() WHERE activation_token = ? AND is_active = 0";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$token]);
    }

    // Vérifie si un email existe déjà (actif ou non)
    public function emailExists($email)
    {
        $sql = "SELECT COUNT(*) FROM users WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    // Récupère un utilisateur par token (actif ou non)
    public function getUserByToken($token)
    {
        $sql = "SELECT * FROM users WHERE activation_token = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Trouve un utilisateur par token de reset
    public function findByResetToken($token)
    {
        $sql = "SELECT * FROM users WHERE reset_token = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Met à jour le mot de passe via le token de reset
    public function updatePasswordByToken($token, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$hashedPassword, $token]);
        return $stmt->rowCount() > 0;
    }
}
