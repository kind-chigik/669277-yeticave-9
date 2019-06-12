<?php
require_once('helpers.php');
require_once('init.php');

$config = require 'config.php';
$connection = db_connect($config['db']);

$categories = get_categories($connection);
$nav_content = include_template('nav.php', [
    'categories' => $categories
]);

$new_user = $_POST ?? '';
$error = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {        //если форма отправлена, начинаем проверки
    $required = ['email', 'password', 'name', 'message'];
    $dict = [
        'email' => 'email',
        'password' => 'Пароль',
        'name' => 'Имя',
        'message' => 'Контактные данные'
    ];

    foreach ($required as $key) {                  //Проверяем поля на заполненность
        if (empty($new_user[$key])) {
            $error[$key] = 'Заполните поле -' . ' ' . $dict[$key];
        }
    }

    if (!filter_var($new_user['email'], FILTER_VALIDATE_EMAIL)) {   //Проверяем email
        $error['email'] = 'Введите корректный email';
    } else {
        $email = mysqli_real_escape_string($connection, $new_user['email']);
        $sql = "SELECT id FROM user WHERE email = '$email'";
        $res = mysqli_query($connection, $sql);

        if (mysqli_num_rows($res) > 0) {
            $error['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    }

    if (mb_strlen($new_user['name']) > 64) {     //Проверяем поле имени на превышение ошибки
        $error['name'] = 'Вы превысили допустимое количество символов';
    }

    if (!empty($error)) {                        //Если есть ошибки показываем их в шаблоне
        $content_sign_up = include_template('sign-up.php', [
            'new_user' => $new_user,
            'error' => $error
        ]);
    } else {                                     //Если нет ошибок, записываем нового пользователя в БД
        $password_user = password_hash($new_user['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (email, name, password, contact) VALUES (?, ?, ?, ?)";
        $stmt = db_get_prepare_stmt($connection, $sql,
            [$new_user['email'], $new_user['name'], $password_user, $new_user['message']]);
        $res = mysqli_stmt_execute($stmt);

        header("Location: login.php");    //А затем перенаправляем на страницу входа на сайт
        exit();
    }


} else {                                             //если форма не отправлена, выводим форму регистрации
    $content_sign_up = include_template('sign-up.php', [
        'new_user' => $new_user,
        'error' => $error
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $content_sign_up,
    'categories' => $categories,
    'nav_content' => $nav_content,
    'title' => 'Регистрация',
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);

print($layout_content);