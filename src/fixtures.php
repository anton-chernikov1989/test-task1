<?php

require('include/db_connection.php');

$pdo = db_connect();

$pdo->exec("DELETE FROM users");
$pdo->exec("DELETE FROM email_validity_check_queue");
$pdo->exec("DELETE FROM email_queue");

$testUsers = [
    ['username' => 'Alice', 'email' => 'alice@example.com', 'validts' => strtotime('+2 days'), 'confirmed' => 1, 'checked' => 0, 'valid' => 0],
    ['username' => 'Bob', 'email' => 'bob@example.com', 'validts' => strtotime('+4 days'), 'confirmed' => 0, 'checked' => 0, 'valid' => 0],
    ['username' => 'Charlie', 'email' => 'charlie@example.com', 'validts' => strtotime('+1 days'), 'confirmed' => 1, 'checked' => 0, 'valid' => 0],
    ['username' => 'David', 'email' => 'david@example.com', 'validts' => strtotime('+3 days'), 'confirmed' => 1, 'checked' => 0, 'valid' => 0],
    ['username' => 'Eve', 'email' => 'eve@example.com', 'validts' => strtotime('+5 days'), 'confirmed' => 0, 'checked' => 0, 'valid' => 0],
    ['username' => 'Frank', 'email' => 'frank@example.com', 'validts' => strtotime('+2 days'), 'confirmed' => 1, 'checked' => 0, 'valid' => 0],
    ['username' => 'Grace', 'email' => 'grace@example.com', 'validts' => strtotime('+6 days'), 'confirmed' => 1, 'checked' => 0, 'valid' => 0],
    ['username' => 'Hank', 'email' => 'hank@example.com', 'validts' => strtotime('+3 days'), 'confirmed' => 1, 'checked' => 0, 'valid' => 0],
    ['username' => 'Ivy', 'email' => 'ivy@example.com', 'validts' => strtotime('+1 days'), 'confirmed' => 1, 'checked' => 0, 'valid' => 0],
    ['username' => 'Jack', 'email' => 'jack@example.com', 'validts' => strtotime('+4 days'), 'confirmed' => 1, 'checked' => 0, 'valid' => 0],
    ['username' => 'Kate', 'email' => 'kate@example.com', 'validts' => strtotime('+2 days'), 'confirmed' => 0, 'checked' => 0, 'valid' => 0],
    ['username' => 'Leo', 'email' => 'leo@example.com', 'validts' => strtotime('+5 days'), 'confirmed' => 1, 'checked' => 0, 'valid' => 0],
    ['username' => 'Mia', 'email' => 'mia@example.com', 'validts' => strtotime('+3 days'), 'confirmed' => 0, 'checked' => 0, 'valid' => 0],
    ['username' => 'Noah', 'email' => 'noah@example.com', 'validts' => strtotime('+4 days'), 'confirmed' => 1, 'checked' => 0, 'valid' => 0],
    ['username' => 'Olivia', 'email' => 'olivia@example.com', 'validts' => strtotime('+6 days'), 'confirmed' => 0, 'checked' => 0, 'valid' => 0],
    ['username' => 'Paul', 'email' => 'paul@example.com', 'validts' => strtotime('+1 days'), 'confirmed' => 1, 'checked' => 0, 'valid' => 0],
    ['username' => 'Quinn', 'email' => 'quinn@example.com', 'validts' => strtotime('+2 days'), 'confirmed' => 1, 'checked' => 0, 'valid' => 0],
    ['username' => 'Ryan', 'email' => 'ryan@example.com', 'validts' => strtotime('+3 days'), 'confirmed' => 0, 'checked' => 0, 'valid' => 0],
    ['username' => 'Sophia', 'email' => 'sophia@example.com', 'validts' => strtotime('+5 days'), 'confirmed' => 1, 'checked' => 0, 'valid' => 0],
    ['username' => 'Tom', 'email' => 'tom@example.com', 'validts' => strtotime('+4 days'), 'confirmed' => 0, 'checked' => 0, 'valid' => 0],
    ['username' => 'Uma', 'email' => 'uma@example.com', 'validts' => strtotime('+2 days'), 'confirmed' => 1, 'checked' => 0, 'valid' => 0],
    ['username' => 'Victor', 'email' => 'victor@example.com', 'validts' => strtotime('+6 days'), 'confirmed' => 0, 'checked' => 0, 'valid' => 0],
    ['username' => 'Wendy', 'email' => 'wendy@example.com', 'validts' => strtotime('+3 days'), 'confirmed' => 1, 'checked' => 0, 'valid' => 0],
    ['username' => 'Xander', 'email' => 'xander@example.com', 'validts' => strtotime('+4 days'), 'confirmed' => 0, 'checked' => 0, 'valid' => 0],
    ['username' => 'Yara', 'email' => 'yara@example.com', 'validts' => strtotime('-5 days'), 'confirmed' => 1, 'checked' => 0, 'valid' => 0],
    ['username' => 'Zack', 'email' => 'zack@example.com', 'validts' => strtotime('+1 days'), 'confirmed' => 0, 'checked' => 0, 'valid' => 0],
];

$stmt = $pdo->prepare("INSERT INTO users (username, email, validts, confirmed, checked, valid) VALUES (:username, :email, :validts, :confirmed, :checked, :valid)");

foreach ($testUsers as $user) {
    $stmt->execute($user);
}
