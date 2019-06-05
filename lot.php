<?php
require_once('helpers.php');
require_once('init.php');

$config = require 'config.php';
$connection = db_connect($config['db']);

$categories = get_categories($connection);
$nav_content = include_template('nav.php', [
    'categories' => $categories
]);

$id = intval($_GET['id']);

$sql = "SELECT l.*, c.name as cat_name, r.amount, r.user_id FROM lot l
        LEFT JOIN category c ON category_id = c.id
        LEFT JOIN rate r ON r.lot_id = '$id'
        WHERE l.id = '$id'
        ORDER BY r.amount DESC";
$sql_rate = "SELECT r.id, r.creation_time, r.amount, r.lot_id, u.id, u.name FROM rate r
            LEFT JOIN user u ON u.id = r.user_id
            WHERE r.lot_id = '$id'
            ORDER BY r.amount
            DESC LIMIT 10";

$lot = get_row_from_mysql($connection, $sql);
$lot_rate = get_rows_from_mysql($connection, $sql_rate);
$current_price = $lot_rate[0]['amount'] ?? $lot['start_price'];
$min_rate = $current_price + $lot['step'];

if (empty($lot)) {     //если лота по переданным параметрам не существует, устанавливаем 404 ошибку
    http_response_code(404);
} else {               //если лот существует, подключаем шаблон лота
    $page_content = include_template('lot.php', [
        'lot' => $lot,
        'lot_rate' => $lot_rate,
        'nav_content' => $nav_content,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'user_id' => $user_id,
        'current_price' => $current_price,
        'min_rate' => $min_rate
    ]);
};

//Проверяем форму
if ($_SERVER['REQUEST_METHOD'] == 'POST') {  //если форма отправлена, начинаем проверки полей
    $rate = $_POST['cost'];
    $error = [];

    if (empty($rate)) {    //если ставка не введена, записываем ошибку
        $error['cost'] = 'Введите ставку';
    } elseif (!is_numeric($rate) || $rate < 1) {  //если ставка не число или если она < 1, записываем ошибку
        $error['cost'] = 'Ставка должна быть числом';
    } elseif ($rate < $min_rate) {           //если ставка меньше минимальной, записываем ошибку
        $error['cost'] = 'Ставка должна быть больше ' . $min_rate;
    }
}

if (!empty($error)) {                       //если есть ошибки, передаем их в шаблон лота
    $page_content = include_template('lot.php', [
        'error' => $error,
        'lot' => $lot,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'user_id' => $user_id,
        'lot_rate' => $lot_rate,
        'current_price' => $current_price,
        'min_rate' => $min_rate
    ]);
} else {                                      //если ошибок нет, записываем ставку в БД
    $sql = "INSERT INTO rate (amount, user_id, lot_id) VALUES (?, ?, ?)";
    $stmt = db_get_prepare_stmt($connection, $sql, [$rate, $user_id, $id]);
    $res = mysqli_stmt_execute($stmt);

    if ($res) {
        $sql_rate = "SELECT r.id, r.creation_time, r.amount, r.lot_id, u.id, u.name FROM rate r
            LEFT JOIN user u ON u.id = r.user_id
            WHERE r.lot_id = '$id'
            ORDER BY r.amount
            DESC LIMIT 10";
        $lot_rate = get_rows_from_mysql($connection, $sql_rate);
        $current_price = $lot_rate[0]['amount'];
        $min_rate = $current_price + $lot['step'];
        $page_content = include_template('lot.php', [
            'error' => $error,
            'lot' => $lot,
            'is_auth' => $is_auth,
            'user_name' => $user_name,
            'user_id' => $user_id,
            'lot_rate' => $lot_rate,
            'current_price' => $current_price,
            'min_rate' => $min_rate
        ]);
    }

}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'nav_content' => $nav_content,
    'title' => $lot['name'],
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);

print($layout_content);