<?php
// /cli/tests/AuthTest.php
// Test fonctionnel basique pour la redirection si non connecté

require_once __DIR__ . '/../classes/Auth.php';

// Simule une requête sans session client_id
$_SESSION = [];

ob_start();
Auth::requireAuth();
$output = ob_get_clean();

if (headers_list() && in_array('Location: /login', headers_list())) {
    echo "[OK] Redirection vers login si non connecté.";
} else {
    echo "[ERREUR] Pas de redirection détectée.";
}
