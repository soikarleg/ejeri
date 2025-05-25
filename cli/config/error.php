<?php

// config/error.php
// Gestion centralisée des erreurs et exceptions pour le module CLI

ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/err_cli_enooki.log');
error_reporting(E_ALL);
define('ENOOKI_EOL', PHP_EOL);

set_error_handler(function ($severity, $message, $file, $line) {
    $entry = "[" . date('Y-m-d H:i:s') . "] - $severity\nENOOKI - $message in $file on line $line\n\n";
    error_log($entry, 3, __DIR__ . '/../logs/err_cli_enooki.log');
    return false;
});

set_exception_handler(function ($exception) {
    $entry = "[" . date('Y-m-d H:i:s') . "] - $exception\nENOOKI - Uncaught Exception: " . $exception->getMessage() .
        " in " . $exception->getFile() . " on line " . $exception->getLine() . "\n\n";
    error_log($entry, 3, __DIR__ . '/../logs/err_cli_enooki.log');
});

set_exception_handler(function ($exception) {
    $entry = "[" . date('Y-m-d H:i:s') . "]" . ENOOKI_EOL .
        "ENOOKI - Uncaught Exception: " . $exception->getMessage() .
        " in " . $exception->getFile() . " on line " . $exception->getLine() . ENOOKI_EOL .
        "Stack trace:" . ENOOKI_EOL . $exception->getTraceAsString() . ENOOKI_EOL . ENOOKI_EOL . "\n\n";
    error_log($entry, 3, __DIR__ . '/../logs/err_cli_enooki.log');
});

// Gestion des erreurs fatales (shutdown)
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $entry = "[" . date('Y-m-d H:i:s') . "]" . ENOOKI_EOL .
            "ENOOKI - Fatal error: {$error['message']} in {$error['file']} on line {$error['line']}" . ENOOKI_EOL . ENOOKI_EOL . "\n\n";
        error_log($entry, 3, __DIR__ . '/../logs/err_cli_enooki.log');
    }
});
