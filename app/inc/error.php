<?php
$chemin = $_SERVER['DOCUMENT_ROOT'];
error_reporting(\E_ALL);
ini_set('display_errors', 1);
ini_set('display_errors', 'stdout');
ini_set('log_errors', 1);
ini_set('error_log', $chemin . '/log/errors_log.log');

// Gestionnaire d'erreurs
// E_STRICT => 'Suggère une amélioration',
function customErrorHandler($severity, $message, $file, $line)
{
  $chemin = $_SERVER['DOCUMENT_ROOT'];
  $errorTypes = [
    E_ERROR => 'Erreur fatale',
    E_WARNING => 'Avertissement',
    E_PARSE => 'Erreur de syntaxe',
    E_NOTICE => 'Notification',
    E_CORE_ERROR => 'Erreur fatale (noyau)',
    E_CORE_WARNING => 'Avertissement (noyau)',
    E_COMPILE_ERROR => 'Erreur de compilation',
    E_COMPILE_WARNING => 'Avertissement de compilation',
    E_USER_ERROR => 'Erreur utilisateur',
    E_USER_WARNING => 'Avertissement utilisateur',
    E_USER_NOTICE => 'Notification utilisateur',
    E_RECOVERABLE_ERROR => 'Erreur récupérable',
    E_DEPRECATED => 'Fonctionnalité dépréciée',
    E_USER_DEPRECATED => 'Fonctionnalité dépréciée (utilisateur)',
  ];
  $errorType = $errorTypes[$severity] ?? 'Erreur inconnue';
  echo "<div style='border: 2px dotted #3d76ad; padding: 10px; margin: 5px; max-width: 50%; color: #959595;'>";
  echo "<strong>$errorType :</strong><br>";
  echo "<strong>Message :</strong> " . htmlspecialchars($message) . "<br>";
  echo "<strong>Fichier :</strong> " . htmlspecialchars($file) . "<br>";
  echo "<strong>Ligne :</strong> " . $line . "<br>";
  echo "</div>";

  $logEntry = [
    'date' => date('Y-m-d H:i:s'),
    'severity' => $severity,
    'message' => $message,
    'file' => $file,
    'line' => $line,
  ];
  file_put_contents($chemin . '/log/errors_log.json', json_encode($logEntry, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);
}

// Gestionnaire d'exceptions
function customExceptionHandler($exception)
{
  $chemin = $_SERVER['DOCUMENT_ROOT'];
  echo "<div style='border: 2px solid red; padding: 10px; margin: 5px; max-width: 75%; color: #959595;'>";
  echo "<strong style='color:red'>ERREUR MAJEURE :(</strong><br>";
  echo "<strong>Message :</strong> " . htmlspecialchars($exception->getMessage()) . "<br>";
  echo "<strong>Fichier :</strong> " . htmlspecialchars($exception->getFile()) . "<br>";
  echo "<strong>Ligne :</strong> " . $exception->getLine() . "<br>";
  echo "
  <span><strong>Trace :</strong>\n" . htmlspecialchars($exception->getTraceAsString()) . "</span>";
  echo "
</div>";

  $logEntry = [
    'date' => date('Y-m-d H:i:s'),
    'message' => $exception->getMessage(),
    'file' => $exception->getFile(),
    'line' => $exception->getLine(),
    'trace' => $exception->getTraceAsString(),
  ];
  file_put_contents($chemin . '/log/exceptions_log.json', json_encode($logEntry, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);
}

// Enregistrer les gestionnaires
set_error_handler("customErrorHandler");
set_exception_handler("customExceptionHandler");
