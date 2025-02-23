<?php

require('include/db_connection.php');

$pdo = db_connect();

$currentTime = time();
$batchSize = 200; // Берём данные пачками, чтобы скрипт не отвалился из-за проблем с памятью

// Отправляем письма с валидным адресом в очередь для отправки
enqueque_emails($pdo, 1, $currentTime, $batchSize);
enqueque_emails($pdo, 3, $currentTime, $batchSize);

function enqueque_email(PDO $pdo, array $user) {
    $pdo->prepare("INSERT INTO email_queue (recipient, message, created_at) VALUES (:email, :message, NOW())")
        ->execute([
            'email' => $user['email'],
            'message' => "{$user['username']}, your subscription is expiring soon"
        ]);
}

function enqueque_emails(PDO $pdo, int $days, int $currentTime, int $batchSize)
{
    $startOfDay = strtotime("midnight +$days days", $currentTime);
    $endOfDay = strtotime("tomorrow +$days days", $currentTime) - 1;
    $lastId = 0;

    do {
        $stmt = $pdo->prepare("SELECT id, username, email, validts, confirmed, checked, valid FROM users 
                                WHERE validts >= :startOfDay AND validts < :endOfDay 
                                AND valid = 1
                                AND id > :lastId ORDER BY id LIMIT :batchSize");
        $stmt->bindValue(':startOfDay', $startOfDay, PDO::PARAM_INT);
        $stmt->bindValue(':endOfDay', $endOfDay, PDO::PARAM_INT);
        $stmt->bindValue(':batchSize', $batchSize, PDO::PARAM_INT);
        $stmt->bindValue(':lastId', $lastId, PDO::PARAM_INT);
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($users as $user) {
            $lastId = $user['id'];
            enqueque_email($pdo, $user);
        }
    } while (!empty($users));
}
