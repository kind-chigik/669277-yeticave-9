<?php
require_once('helpers.php');
require_once('init.php');

$config = require 'config.php';

$connection = db_connect($config['db']);

$categories = get_categories($connection);

$lots = get_lots($connection);


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
