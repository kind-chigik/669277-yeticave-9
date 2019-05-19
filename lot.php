<?php
require_once('helpers.php');

$config = require 'config.php';
$connection = db_connect($config['db']);

$is_auth = rand(0, 1);
$user_name = 'Inna';

$categories = get_categories($connection);

$id = intval($_GET['id']);

$sql = "SELECT l.*, c.name as cat_name, r.amount, r.user_id FROM lot l
        LEFT JOIN category c ON category_id = c.id
        LEFT JOIN rate r ON r.lot_id = '$id'
        WHERE l.id = '$id'
        ORDER BY r.amount DESC";

$lot = get_row_from_mysql($connection, $sql);

if (empty($lot)) {
    http_response_code(404);
} else {
    $page_content = include_template('lot.php', [
        'lot' => $lot,
        'is_auth' => $is_auth
    ]);
};

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);

print($layout_content);