<?php
// /cli/classes/Auth.php
// Classe utilitaire pour la gestion de l’authentification client

class Auth
{
    public static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function isAuthenticated()
    {
        self::startSession();
        return isset($_SESSION['client_id']);
    }

    public static function requireAuth()
    {
        if (!self::isAuthenticated()) {
            header('Location: /login');
            exit();
        }
    }

    public static function login($clientId)
    {
        self::startSession();
        $_SESSION['client_id'] = $clientId;
    }

    public static function logout()
    {
        self::startSession();
        session_unset();
        session_destroy();
    }

    // Retourne l'email du client connecté (ou null)
    public static function getClientEmail()
    {
        self::startSession();
        if (!isset($_SESSION['client_id'])) {
            return null;
        }
        require_once __DIR__ . '/../models/ClientModel.php';
        $client_id = $_SESSION['client_id'];
        
        $clientModel = new \ClientModel();
        $client = $clientModel->getClientById($client_id);
        if ($client && !empty($client['email'])) {
            return $client['email'];
        }
        return null;
    }
}
