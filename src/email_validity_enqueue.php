<?php

require('include/db_connection.php');

$pdo = db_connect();

// Валидируем только имейлы подтвержденных пользователей, у которых есть подписка. Экономим рубли.
// Валидируем электронные адреса пользователей, подписка которых истечет в будущем
// Валидация в нашем случае долгий процесс, в первую очередь валидируем те, которые скоро истекают
$stmt = $pdo->prepare("SELECT id, email FROM users WHERE checked = 0 AND confirmed = 1 AND validts > UNIX_TIMESTAMP() ORDER BY validts ASC LIMIT 1000");
$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Проверяем один раз
$selectStmt = $pdo->prepare('SELECT * FROM email_validity_check_queue WHERE email = ?');

foreach ($users as $user) {
    $selectStmt->execute([$user['email']]);
    $row = $selectStmt->fetch(PDO::FETCH_ASSOC);
    if (!empty($row)) {
        continue;
    }

    // Валидировать будем через очередь, так как один запрос занимает до 60 секунд
    $pdo->prepare("INSERT INTO email_validity_check_queue (user_id, email, created_at) VALUES (:user_id, :email, NOW())")
        ->execute(['user_id' => $user['id'], 'email' => $user['email']]);
}
