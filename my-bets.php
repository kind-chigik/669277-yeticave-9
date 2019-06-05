<?php
require_once('helpers.php');
require_once('init.php');

$config = require 'config.php';
$connection = db_connect($config['db']);

$categories = get_categories($connection);
$nav_content = include_template('nav.php', [
    'categories' => $categories
]);

if (!isset($_SESSION['user'])) {
    http_response_code(403);
    die('Просматривать эту страницу могут только зарегистрированные пользователи.' . '<br>' . '<a href="login.php">Войдите на сайт</a>' . ' или <a href="sign-up.php">зарегистрируйтесь</a>.' . '<br>' . '<a href="index.php">Вернуться на главную</a>');
}

$sql_bets = "SELECT l.id, l.image, l.winner_id, l.name, l.end_time, c.name as cat_name, r.amount, r.user_id, r.creation_time, u.contact  FROM rate r
             JOIN lot l ON r.lot_id = l.id
             JOIN user u ON l.user_id = u.id
             JOIN category c ON l.category_id = c.id
             WHERE r.user_id = '$user_id' GROUP BY r.id ORDER BY r.creation_time DESC";

$bets = get_rows_from_mysql($connection, $sql_bets);

$content = include_template('my-bets.php', [
    'bets' => $bets,
    'user_id' => $user_id
]);
$layout_content = include_template('layout.php', [
    'content' => $content,
    'categories' => $categories,
    'nav_content' => $nav_content,
    'title' => 'Moи ставки',
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);
print($layout_content);