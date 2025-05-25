<?php
class Logger
{
    private $pdo;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function log($compte_id, $user_id, $module, $action, $table, $row_id = null, $description = null) {
        $sql = "INSERT INTO logs_operations (compte_id, user_id, module, action, table_name, row_id, description, ip)
                VALUES (:compte_id, :user_id, :module, :action, :table_name, :row_id, :description, :ip)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'compte_id' => $compte_id,
            'user_id' => $user_id,
            'module' => $module,
            'action' => $action,
            'table_name' => $table,
            'row_id' => $row_id,
            'description' => $description,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null
        ]);
    }
}