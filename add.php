<?php
require_once('helpers.php');
$config = require 'config.php';
$connection = db_connect($config['db']);

$is_auth = rand(0, 1);
$user_name = 'Inna';

$categories = get_categories($connection);
$form_invalid = 'form--invalid';
$field_invalid = 'form__item--invalid';

$content_form = include_template('add.php', [
    'categories' => $categories
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {  //если форма отправлена, начинаем проверки
    $lot = $_POST;
    $required = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];

    $dict = [
        'lot-name' => 'Название лота',
        'category' => 'Категория лота',
        'message' => 'Описание лота',
        'lot-img' => 'Изображение лота',
        'lot-rate' => 'Начальная цена лота',
        'lot-step' => 'Шаг ставки лота',
        'lot-date' => 'Дата окончания торгов по лоту',
    ];
    $error = [];

    foreach ($required as $key) {
        if(empty($_POST[$key])) {
            $error['key'] = 'Все поля должны быть заполнены';
        }
    }

    foreach ($lot as $key => $value) {
        if ($key == 'lot-rate') {
            if (!is_numeric($value) || $value < 1) {
                $error['lot-rate'] = 'Начальная ставка должна быть числом больше 0';
            }
        }
        if ($key == 'lot-step') {
            if (!filter_var($value, FILTER_VALIDATE_INT) || $value < 1) {
                $error['lot-step'] = 'Шаг ставки должен быть целым числом больше 0';
            }
        }
        if ($key == 'lot-date') {
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
        }
        else {      //если формат прошел проверку, перемещаем его в постоянную папку
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
            'dict' => $dict,
            'lot' => $lot,
            'form_invalid' => $form_invalid,
            'field_invalid' => $field_invalid
        ]);
    } else {             //если нет ошибок, добавляем новый лот и переадресовываем на новую страницу лота
        $sql = 'INSERT INTO lot (name, description, image, start_price, end_time, step, user_id, category_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $lot['user_id'] = '2';
        $stmt = db_get_prepare_stmt($connection, $sql, [$lot['lot-name'], $lot['message'], $lot['lot-img'], $lot['lot-rate'], $lot['lot-date'], $lot['lot-step'], $lot['user_id'], $lot['category']]);
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
        'categories' => $categories
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $content_form,
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'flatpickr_css' => '../css/flatpickr.min.css'
]);

print($layout_content);