# ЗАДАНИЕ 1

Следует учитывать что пользователей с подпиской и подтверждённым адресом электронной почты, согласно условиям задачи, около 150 000
Если выделить по 30 секунд (средний случай) на проверку каждого адреса, валидация займет 75 000 минут или примерно 52 дня
В связи с этим имеет смысл в первую очередь проверять электронные адреса тех пользователей, которым нужно отправить письмо в ближайшее время.

# Описание

Всего 4 скрипта, которые запускаются по Крону:
1. **email_validity_enqueue.php** - Отправляет письма в очередь на валидацию
1. **email_check.php** - Проверяет электронный адреса из очереди на валидацию
1. **email_enqueue.php** - Сохраняет письма в очередь на отправку
1. **send_emails.php** - Отправляет письма из очереди на отправку

# Запуск приложения
Приложение работает в докере. Для первичного запуска можно использовать комманды:

1. `cd docker && docker-compose up -d --build`
1. `docker exec test_task_workspace php /var/www/src/migration.php`
1. `docker exec test_task_workspace php /var/www/src/fixtures.php`
1. Соединиться с базой и посмотреть что внутри (необязательно)
1. Запускаем крон `docker exec  test_task_workspace  /usr/sbin/crond` и наблюдем результат в БД.
1. Ждём 15 минут, когда отработает валидация. В файле **mycrontab** предполагается запуск скрипта на отправку писем в очередь раз в день.  
   Чтобы не ждать слишком долго, предлагаю отправить письма в очередь вручную вызвав `docker exec test_task_workspace php /var/www/src/email_enqueue.php`

# Соединение с БД
`docker exec -itu1000 test_task_db mysql -uroot -proot --database=project`

# ЗАДАНИЕ 2

```
WITH OrderStats AS (
    SELECT
        o.user_id,
        SUM(CASE WHEN o.payed = true THEN 1 ELSE 0 END) AS paid_orders,
        SUM(CASE WHEN o.payed = false THEN 1 ELSE 0 END) AS unpaid_orders
    FROM orders o
    GROUP BY o.user_id
),
PaymentStats AS (
    SELECT
        o.user_id,
        COUNT(p.id) AS total_payments,
        SUM(CASE WHEN p.status = 'failed' THEN 1 ELSE 0 END) AS failed_payments
    FROM orders o
    JOIN payments p ON o.id = p.order_id
    GROUP BY o.user_id
)
SELECT u.*
FROM users u
JOIN OrderStats os ON u.id = os.user_id
JOIN PaymentStats ps ON u.id = ps.user_id
WHERE 
    os.paid_orders > 2 * os.unpaid_orders
    AND (ps.failed_payments * 1.0 / NULLIF(ps.total_payments, 0)) < 0.15;
```

# ЗАДАНИЕ 3
1. Frontend (UI) – Интерфейс для пользователя (веб-приложение), где можно добавлять торрент-ссылки и отслеживать прогресс загрузки.
1. API Gateway – Принимает запросы от клиента и направляет их в нужные сервисы.
1. User Service – Обрабатывает данные о пользователях (регистрация, авторизация, тарифы).
1. Torrent Service – Скачивает торренты и сохраняет их в облачное хранилище.
1. Cloud Storage – Облачное хранилище для сохранения скачанных фильмов.
1. Video Streaming – Сервис для потоковой передачи видео из облачного хранилища.