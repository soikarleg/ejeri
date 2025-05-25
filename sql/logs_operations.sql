CREATE TABLE logs_operations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    compte_id INT NOT NULL,
    user_id INT,
    module VARCHAR(20) NOT NULL,
    action VARCHAR(10) NOT NULL, -- INSERT, UPDATE, DELETE
    table_name VARCHAR(50) NOT NULL,
    row_id INT,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    ip VARCHAR(45)
);