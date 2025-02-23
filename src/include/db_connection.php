<?php

function db_connect() {
    $pdo = new PDO('mysql:host=test_task_db;dbname=project;charset=utf8', 'root', 'root', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    return $pdo;
}
