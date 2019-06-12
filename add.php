<?php
require_once('helpers.php');
require_once('init.php');

if (!isset($_SESSION['user'])) {
    http_response_code(403);
    die('Добавить новый лот могут только зарегистрированные пользователи.' . '<br>' . 'Чтобы добавить лот <a href="login.php">войдите на сайт</a>' . ' или <a href="sign-up.php">зарегистрируйтесь</a>.' . '<br>' . '<a href="index.php">Вернуться на главную</a>');
}

$config = require 'config.php';
$connection = db_connect($config['db']);

$categories = get_categories($connection);
$nav_content = include_template('nav.php', [
    'categories' => $categories
]);

$content_form = include_template('add.php', [
    'categories' => $categories
]);

$lot = $_POST ?? '';
$error = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {  //если форма отправлена, начинаем проверки
    $required = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
    $dict = [
        'lot-name' => 'Наименование',
        'category' => 'Категория',
        'message' => 'Описание',
        'lot-img' => 'Изображение',
        'lot-rate' => 'Начальная цена',
        'lot-step' => 'Шаг ставки',
        'lot-date' => 'Дата окончания торгов'
    ];

    foreach ($required as $key) {
        if (empty($lot[$key])) {
            $error[$key] = 'Заполните поле -' . ' ' . $dict[$key];
        }
    }

    foreach ($lot as $key => $value) {
        if ($key === 'lot-rate') {
            if (!is_numeric($value) || $value < 1) {
                $error['lot-rate'] = 'Начальная ставка должна быть числом больше 0';
            }
        }
        if ($key === 'lot-step') {
            if (!filter_var($value, FILTER_VALIDATE_INT) || $value < 1) {
                $error['lot-step'] = 'Шаг ставки должен быть целым числом больше 0';
            }
        }
        if ($key === 'lot-date') {
            if (!is_date_valid($value)) {
                $error['lot-date'] = 'Дата окончания торгов должна быть введена в формате «год-месяц-день»';
            }
            if ((count_time($value)) < 86400) {
                $error['lot-date'] = 'Дата окончания торгов должна быть больше текущей даты, хотя бы на один день';
            }
        }
    }

    if (!empty($_FILES['lot-img']['name'])) {   //если изображение загружено, проверяем его на формат
        $tmp_name = $_FILES['lot-img']['tmp_name'];
        $filename = uniqid();
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);

        //если файл не соответсвует формату, выводим ошибку
        if ($file_type !== "image/png" && $file_type !== "image/jpeg" && $file_type !== "image/jpg") {
            $error['lot-img'] = 'Загрузите изображение в формате PNG или JPG';
        } else {      //если формат прошел проверку, перемещаем его в постоянную папку
            move_uploaded_file($_FILES['lot-img']['tmp_name'], 'uploads/' . $filename);
            $lot['lot-img'] = 'uploads/' . $filename;
        }

    } else {   //если изображение не загружено, выводим ошибку
        $error['lot-img'] = 'Загрузите изображение лота';
    }

    if (!empty($error)) {  //если есть ошибки, передаем их в шаблон формы
        $content_form = include_template('add.php', [
            'categories' => $categories,
            'error' => $error,
            'lot' => $lot
        ]);
    } else {             //если нет ошибок, добавляем новый лот и переадресовываем на новую страницу лота
        $sql = 'INSERT INTO lot (name, description, image, start_price, end_time, step, user_id, category_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $lot['user_id'] = $user_id;
        $stmt = db_get_prepare_stmt($connection, $sql, [
            $lot['lot-name'],
            $lot['message'],
            $lot['lot-img'],
            $lot['lot-rate'],
            $lot['lot-date'],
            $lot['lot-step'],
            $lot['user_id'],
            $lot['category']
        ]);
        $result = mysqli_stmt_execute($stmt);
        if ($result) {
            $lot_id = mysqli_insert_id($connection);
            header("Location: lot.php?id=" . (int)$lot_id);
        } else {
            print "Не удалось добавить лот:" . mysqli_error($connection);
        }
    }

} else {                                   //если форма не отправлена, выводим пустую форму
    $content_form = include_template('add.php', [
        'categories' => $categories,
        'error' => $error
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $content_form,
    'categories' => $categories,
    'nav_content' => $nav_content,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'flatpickr_css' => '../css/flatpickr.min.css',
    'title' => 'Добавление лота'
]);

print($layout_content);