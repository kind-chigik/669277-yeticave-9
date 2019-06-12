<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
require_once('helpers.php');
require_once('init.php');
require_once('getwinner.php');

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
    'title' => 'Интерент-аукцион YetiCave'
]);

print($layout_content);
