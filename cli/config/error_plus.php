
<?php
// config/error.php
// Gestion centralisée des erreurs et exceptions pour le module CLI
ini_set('log_errors', 1);
ini_set('display_errors', 0); // Désactive l'affichage des erreurs en production
ini_set('error_log', __DIR__ . '/../logs/err_cli_enooki.log');
error_reporting(E_ALL);

// Gestion des erreurs PHP
set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        // Cette erreur n'est pas incluse dans error_reporting
        return false;
    }
    $entry = "[" . date('Y-m-d H:i:s') . "] - \n ENOOKI - $message in $file on line $line\n";
    error_log($entry, 3, __DIR__ . '/../logs/err_cli_enooki.log');
    // Convertit les erreurs en exceptions pour une gestion unifiée
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// Gestion des exceptions non attrapées
set_exception_handler(function ($exception) {
    $entry = "[" . date('Y-m-d H:i:s') . "]\nENOOKI - Uncaught Exception: " . $exception->getMessage() .
        " in " . $exception->getFile() . " on line " . $exception->getLine() . "\nStack trace:\n" .
        $exception->getTraceAsString() . "\n\n";
    error_log($entry, 3, __DIR__ . '/../logs/err_cli_enooki.log');
});

// Gestion des erreurs fatales (shutdown)
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $entry = "[" . date('Y-m-d H:i:s') . "]\nENOOKI - Fatal error: {$error['message']} in {$error['file']} on line {$error['line']}\n";
        error_log($entry, 3, __DIR__ . '/../logs/err_cli_enooki.log');
    }
});
