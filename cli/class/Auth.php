<?php
// filepath: /media/otto/stock3/INFORMATIQUE/enooki/cli/AuthManager.php

class Auth
{
    private $pdo;

    public function __construct()
    {
        $chemin = $_SERVER['DOCUMENT_ROOT'] ;
        $config = include($chemin . '/cli/config/mysql.php');
        $host = $config['host_name'];
        $dbname = $config['database'];
        $username = $config['user_name'];
        $password = $config['password'];
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function signup($email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);

        $stmt = $this->pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
        try {
            $stmt->execute(['email' => $email, 'password' => $hashedPassword]);
            return "User registered successfully.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                return "Email already exists.";
            }
            return "Error: " . $e->getMessage();
        }
    }

    public function login($email, $password)
    {
        $stmt = $this->pdo->prepare("SELECT password FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return "Login successful.";
        }
        return "Invalid email or password.";
    }

    public function forgetPassword($email)
    {
        $token = bin2hex(random_bytes(16));
        $stmt = $this->pdo->prepare("UPDATE users SET reset_token = :token, reset_token_expiry = :expiry WHERE email = :email");
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        if ($stmt->execute(['token' => $token, 'expiry' => $expiry, 'email' => $email])) {
            // Send the reset link to the user's email (pseudo-code)
            // mail($email, "Password Reset", "Use this link to reset your password: https://cli.enooki.com/reset?token=$token");
            return "Password reset link sent to your email.";
        }
        return "Email not found.";
    }

    public function resetPassword($token, $newPassword)
    {
        $stmt = $this->pdo->prepare("SELECT email FROM users WHERE reset_token = :token AND reset_token_expiry > NOW()");
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $hashedPassword = password_hash($newPassword, PASSWORD_ARGON2ID);
            $updateStmt = $this->pdo->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_token_expiry = NULL WHERE email = :email");
            $updateStmt->execute(['password' => $hashedPassword, 'email' => $user['email']]);
            return "Password reset successfully.";
        }
        return "Invalid or expired token.";
    }

    public function changePassword($email, $oldPassword, $newPassword)
    {
        $stmt = $this->pdo->prepare("SELECT password FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($oldPassword, $user['password'])) {
            $hashedPassword = password_hash($newPassword, PASSWORD_ARGON2ID);
            $updateStmt = $this->pdo->prepare("UPDATE users SET password = :password WHERE email = :email");
            $updateStmt->execute(['password' => $hashedPassword, 'email' => $email]);
            return "Password changed successfully.";
        }
        return "Invalid current password.";
    }
}