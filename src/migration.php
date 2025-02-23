<?php

require('include/db_connection.php');

$pdo = db_connect();

$queries = [
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        validts INT NOT NULL DEFAULT 0,
        confirmed TINYINT(1) NOT NULL DEFAULT 0,
        checked TINYINT(1) NOT NULL DEFAULT 0,
        valid TINYINT(1) NOT NULL DEFAULT 0,
        INDEX (validts),
        INDEX (confirmed),
        INDEX (valid)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    "CREATE TABLE IF NOT EXISTS email_queue (
        id INT AUTO_INCREMENT PRIMARY KEY,
        recipient VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        sent_at TIMESTAMP NULL DEFAULT NULL,
        INDEX (created_at),
        INDEX (sent_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    "CREATE TABLE IF NOT EXISTS email_validity_check_queue (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        email VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        checked_at TIMESTAMP NULL DEFAULT NULL,
        INDEX (created_at),
        INDEX (checked_at),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
];

foreach ($queries as $query) {
    $pdo->exec($query);
}
