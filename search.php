<?php
require_once('helpers.php');
require_once('init.php');

$config = require 'config.php';
$connection = db_connect($config['db']);

$categories = get_categories($connection);
$nav_content = include_template('nav.php', [
    'categories' => $categories
]);

$search = $_GET['search'] ?? '';

if ($search) {
    $sql = "SELECT COUNT(*) as cnt FROM lot l
            WHERE NOW() < l.end_time and MATCH(l.name, l.description) AGAINST(?)";
    $stmt = db_get_prepare_stmt($connection, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items_count = mysqli_fetch_assoc($result)['cnt'];
    $pages_count = ceil($items_count / $limit);
    $offset = ($current_page - 1) * $limit;
    $pages = range(1, $pages_count);

    $sql_lot = "SELECT l.id, l.name, l.image, l.start_price, l.end_time, c.name AS cat_lot FROM lot l
                 JOIN category c ON l.category_id = c.id
                 WHERE NOW() < l.end_time and MATCH(l.name, l.description) AGAINST(?) ORDER BY l.creation_time DESC
                 LIMIT $limit OFFSET $offset";
    $stmt_lot = db_get_prepare_stmt($connection, $sql_lot, [$search]);
    mysqli_stmt_execute($stmt_lot);
    $result_lot = mysqli_stmt_get_result($stmt_lot);

    $lots = mysqli_fetch_all($result_lot, MYSQLI_ASSOC); // получаем массив с данными на основе поискового запроса
    $search_title = empty($lots) ? 'Ничего не найдено по вашему запросу' : 'Результаты поиска по запросу ' . '«' . $search . '»';

    $page_content = include_template('search.php', [
        'lots' => $lots,
        'search' => $search,
        'search_title' => $search_title,
        'current_page' => $current_page,
        'pages' => $pages
    ]);
} else {
    $page_content = include_template('search.php', [
        'search_title' => 'Введите текст запроса'
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'nav_content' => $nav_content,
    'title' => 'Поиск',
    'search' => $search,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_id' => $user_id
]);

print($layout_content);