<?php
require_once('helpers.php');
$config = require 'config.php';

$connection = db_connect($config['db']);

$categories = get_categories($connection);

$lots = get_lots($connection);

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
