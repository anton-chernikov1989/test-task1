<?php

require('include/db_connection.php');

$pdo = db_connect();
function sendEmail($from, $to, $text) {
    sleep(rand(1, 10));
    echo "Email sent to $to: $text\n";
}

$batchSize = 100; // Берём данные пачками, чтобы скрипт не отвалился из-за проблем с памятью

do {
    $stmt = $pdo->prepare("SELECT id, recipient, message FROM email_queue ORDER BY created_at LIMIT :batchSize");
    $stmt->bindValue(':batchSize', $batchSize, PDO::PARAM_INT);
    $stmt->execute();

    $emails = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($emails as $email) {
        sendEmail('noreply@example.com', $email['recipient'], $email['message']);

        $pdo->prepare("UPDATE email_queue set sent_at = :sentAt WHERE id = :id")->execute([
            'id' => $email['id'],
            'sentAt' => date('Y-m-d H:i:s'),
        ]);
    }
} while (!empty($emails));
