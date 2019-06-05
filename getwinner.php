<?php
require_once('helpers.php');
require_once('init.php');
require_once('vendor/autoload.php');

$config = require 'config.php';
$connection = db_connect($config['db']);

$transport = new Swift_SmtpTransport("phpdemo.ru", 25); //Подключаемся к почтовому серверу
$transport->setUsername("keks@phpdemo.ru");
$transport->setPassword("htmlacademy");

$sql = "SELECT id, name, end_time, winner_id from lot WHERE NOW() >= end_time AND winner_id IS NULL";
$closed_lots = get_rows_from_mysql($connection, $sql);

if (!empty($closed_lots)) {
    foreach ($closed_lots as $lot) {
        $lot_id = $lot['id'];
        $lot_name = $lot['name'];
        $sql_last_rate = "SELECT r.id, r.amount, r.user_id, r.lot_id, u.email, u.name FROM rate r
                          JOIN user u ON r.user_id = u.id
                          WHERE r.lot_id = $lot_id
                          ORDER BY r.amount DESC LIMIT 1";
        $max_rate = get_row_from_mysql($connection, $sql_last_rate);
        $user = $max_rate['name'];
        $email = $max_rate['email'];

        if (!empty($max_rate)) {
            $sql_update = "UPDATE lot SET winner_id = (?) WHERE id = (?)";
            $stmt = db_get_prepare_stmt($connection, $sql_update, [$max_rate['user_id'], $lot['id']]);
            $res = mysqli_stmt_execute($stmt);

            $mailer = new Swift_Mailer($transport);   //Создаем объект, ответственный за отправку сообщений

            $logger = new Swift_Plugins_Loggers_ArrayLogger();    //Записываем все происходящее в процессе отправки
            $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

            $message = new Swift_Message();
            $message->setSubject("Ваша ставка победила");
            $message->setFrom(['keks@phpdemo.ru' => 'YetiCave']);
            $message->setTo([$email]);

            $email_content = include_template('mail.php', [
                'closed_lots' => $closed_lots,
                'lot_id' => $lot_id,
                'lot_name' => $lot_name,
                'user' => $user
            ]);

            $message->setBody($email_content, 'text/html');

            $result = $mailer->send($message);
            $result ? print("Письмо успешно отправлено") : print("Не удалось отправить письмо: " . $logger->dump());
        }
    }
}