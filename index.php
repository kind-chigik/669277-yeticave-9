<?php
require_once('helpers.php');

$bd = mysqli_connect('localhost', 'root', '', 'yeticave');
mysqli_set_charset($bd, 'utf8');

if (!$bd) {
    print('Ошибка подключения: ' . mysqli_connect_error());
};

$sql_category = 'SELECT *'
    . 'FROM category';
$result_category = mysqli_query($bd, $sql_category);
$categories = mysqli_fetch_all($result_category, MYSQLI_ASSOC);

$sql_lot = 'SELECT l.id, l.name, start_price, image, category_id, MAX(r.amount) '
    . 'FROM lot l '
    . 'LEFT JOIN category c ON category_id = c.id '
    . 'LEFT JOIN rate r ON r.lot_id = l.id '
    . 'WHERE end_time > CURRENT_TIMESTAMP '
    . 'GROUP BY l.id ORDER BY l.creation_time DESC ';
$result_lot = mysqli_query($bd, $sql_lot);
$lots = mysqli_fetch_all($result_lot, MYSQLI_ASSOC);

$is_auth = rand(0, 1);
$user_name = 'Inna'; // укажите здесь ваше имя

$page_content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => 'Главная'
]);

print($layout_content);
