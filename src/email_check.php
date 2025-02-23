<?php

require('include/db_connection.php');

$pdo = db_connect();

function checkEmail(string $email) {
    sleep(rand(1, 60));
    return rand(0, 1);
}

$stmt = $pdo->prepare("SELECT id, user_id, email FROM email_validity_check_queue ORDER BY created_at LIMIT 1000");
$stmt->execute();

$checks = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($checks as $check) {
    $isValid = checkEmail($check['email']);

    $pdo->prepare("UPDATE users SET checked = 1, valid = :valid WHERE id = :id")
        ->execute(['valid' => $isValid, 'id' => $check['user_id']]);

    $pdo->prepare("UPDATE email_validity_check_queue SET checked_at = :checkedAt WHERE id = :id")
        ->execute(['id' => $check['id'], 'checkedAt' => date('Y-m-d H:i:s')]);
}
