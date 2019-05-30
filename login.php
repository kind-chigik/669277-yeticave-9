<?php
require_once('helpers.php');
require_once('init.php');

session_start();

$config = require 'config.php';
$connection = db_connect($config['db']);

$categories = get_categories($connection);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_enter = $_POST;
    $error = [];

    if (empty($user_enter['email'])) {  //если email не заполнен, выводим ошибку
        $error['email'] = 'Введите email';
    }
    else {                              //если заполнен, ищем такой email в БД
        $email = mysqli_real_escape_string($connection, $user_enter['email']);
        $sql = "SELECT * FROM user WHERE email = '$email'";
        $res = mysqli_query($connection, $sql);
        $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;  //получаем данные юзера с нужным email

        if (!$user) {                   //если юзера с искомым email нет, записываем ошибку
            $error['email'] = 'Такой пользователь не найден';
        }
    }

    if (empty($user_enter['password'])) {  //если пароль не заполнен, выводим ошибку
        $error['password'] = 'Введите пароль';
    }
    elseif (!password_verify($user_enter['password'], $user['password'])) {   //если пароль не совпадает, выводим ошибку
        $error['password'] = 'Вы ввели неверный пароль';;
    }

    if (!empty($error)) {    //если есть ошибки, выводим их в шаблон
        $content_enter = include_template('login.php', [
            'error' => $error,
            'user_enter' => $user_enter
        ]);
    }
    else {  //если нет ошибок, открываем для юзера сессию и перенаправляем на главную
        $_SESSION['user'] = $user;
        header("Location: index.php");
        exit();
    }

}
else {  //если форма не отправлена, подключаем шаблон формы
    $content_enter = include_template('login.php', []);
}

$layout_content = include_template('layout.php', [
    'content' => $content_enter,
    'categories' => $categories,
    'title' => 'Вход на сайт',
    'is_auth' => $is_auth,
    'user' => $user_name
]);

print($layout_content);