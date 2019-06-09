<?php
require_once('helpers.php');
require_once('init.php');

$config = require 'config.php';
$connection = db_connect($config['db']);

$categories = get_categories($connection);
$nav_content = include_template('nav.php', [
    'categories' => $categories
]);

$cat_id = intval($_GET['id']) ?? '';

$sql_cat = "SELECT * FROM category WHERE id = '$cat_id'";
$category = get_row_from_mysql($connection, $sql_cat);
$cat_name = $category['name'];

if ($category) {                  //если категория по переданному id существует, ищем лоты с такой категорией
    $sql_count_lots = "SELECT COUNT(*) as cnt FROM lot WHERE category_id = $cat_id";
    $stmt = db_get_prepare_stmt($connection, $sql_count_lots);
    mysqli_stmt_execute($stmt);
    $result_count_lots = mysqli_stmt_get_result($stmt);
    $items_count = mysqli_fetch_assoc($result_count_lots)['cnt'];
    $pages_count = ceil($items_count / $limit);
    $offset = ($current_page - 1) * $limit;
    $pages = range(1, $pages_count); //получили массив с номерами всех страниц

    $sql_lot = "SELECT l.name, image, l.id, l.start_price, l.end_time, c.name AS cat_name, c.id AS cat_id FROM lot l 
        JOIN category c ON l.category_id = c.id
        WHERE l.category_id = '$cat_id'
        LIMIT $limit OFFSET $offset";
    $all_lots = get_rows_from_mysql($connection, $sql_lot);  //получили все лоты для категории

    $page_content = include_template('all-lots.php', [
        'cat_name' => $cat_name,
        'all_lots' => $all_lots,
        'cat_id' => $cat_id,
        'pages' => $pages,
        'current_page' => $current_page
    ]);

} else {
    http_response_code(404);
    die("Такой категории не существует" . mysqli_error($connection));
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'cat_name' => $cat_name,
    'categories' => $categories,
    'nav_content' => $nav_content,
    'title' => "Все лоты в категории $cat_name",
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);

print($layout_content);