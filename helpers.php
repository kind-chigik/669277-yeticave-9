<?php
const RUB = '<b class="rub">р</b>';
const HOUR = 3600;
const MINUTE = 60;

/**
 * Округляет число в большую сторону и добавляет пробел после тысячных
 * @param float $amount число, которое нужно отформатировать
 * @return float оформатированное число
 */
function formatting_amount($amount)
{
    $amount = ceil($amount);
    if ($amount >= 1000) {
        $amount = number_format($amount, null, null, ' ');
    }
    return $amount . RUB;
}

;

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

;

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else {
                if (is_string($value)) {
                    $type = 's';
                } else {
                    if (is_double($value)) {
                        $type = 'd';
                    }
                }
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

;

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

;

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

;

/**
 * Определяет разницу между текущим временем и определенной датой, передает туда дату и возвращает оформитированное время
 * @param string $end_time дата, для которой нужно посчитать разницу во времени
 * @return string Итоговая отформатированная дата
 */
function time_before_end(string $end_time)
{
    $end_time = strtotime($end_time);
    $time_diff = $end_time - time();
    if ($time_diff < 0) {
        return '00:00';
    }
    $formatting_time = gmdate("d:H:i", $time_diff);
    return $formatting_time;
}

;

/**
 * Определяет разницу между текущим временем и определенной датой, передает туда дату и возвращает true или false
 * @param string $end_time дата, для которой нужно посчитать разницу во времени
 * @return bool логическое значение
 */
function less_hour_left($end_time)
{
    $end_time = strtotime($end_time);
    $time_diff = $end_time - time();
    if ($time_diff > HOUR) {
        return false;
    }
    return true;
}

;

/**
 * Устанавливает подлючение к БД, передает туда данные для подключения, возвращает результат подключения
 * @param array $db_config массив, в котором хранятся настройки подлючения
 * @return mysqli результат подключения
 */
function db_connect(array $db_config): mysqli
{
    $connection = mysqli_connect($db_config['host'], $db_config['user'], $db_config['password'],
        $db_config['database']);
    mysqli_set_charset($connection, 'utf8');
    if (!$connection) {
        die('Ошибка подключения: ' . mysqli_connect_error());
    }
    return $connection;
}

;

/**
 * Получает все категории из БД
 * @param mysqli $connection ресурс соединения с БД
 * @return array массив с категориями
 */
function get_categories($connection)
{
    $sql_category = 'SELECT * FROM category';
    $result_category = mysqli_query($connection, $sql_category);
    $categories = mysqli_fetch_all($result_category, MYSQLI_ASSOC);
    return $categories;
}

;

/**
 * Получает все лоты из БД
 * @param mysqli $connection ресурс соединения с БД
 * @return array массив с лотами
 */
function get_lots($connection)
{
    $sql_lot = 'SELECT l.id, l.name, start_price, image, category_id, MAX(r.amount), l.end_time '
        . 'FROM lot l '
        . 'LEFT JOIN category c ON category_id = c.id '
        . 'LEFT JOIN rate r ON r.lot_id = l.id '
        . 'GROUP BY l.id ORDER BY l.creation_time DESC ';
    $result_lot = mysqli_query($connection, $sql_lot);
    $lots = mysqli_fetch_all($result_lot, MYSQLI_ASSOC);
    return $lots;
}

;

/**
 * Получает одну запись из результата запроса в БД в виде массива
 * @param mysqli $connection ресурс соединения с БД
 * @return array массив с данными, либо ошибка, если запрос не выполнился успешно
 */
function get_row_from_mysql($connection, $sql)
{
    $result = mysqli_query($connection, $sql);
    return ($result) ? mysqli_fetch_assoc($result) : die("Ошибка " . mysqli_error($connection));
}

;

/**
 * Получает двумерные массив из результата запроса в БД
 * @param mysqli $connection ресурс соединения с БД
 * @return array двумерный массив с данными, либо ошибка, если запрос не выполнился успешно
 */
function get_rows_from_mysql($connection, $sql)
{
    $result = mysqli_query($connection, $sql);
    return ($result) ? mysqli_fetch_all($result, MYSQLI_ASSOC) : die("Ошибка " . mysqli_error($connection));
}

;

/**
 * Определяет разницу между текущим временем и определенной датой, передает туда дату и возвращает метку времени в формате Unixtime
 * @param string $date дата, для которой нужно посчитать разницу во времени и метку времени
 * @return string метка времени Unixtime
 */
function count_time($date)
{
    return strtotime($date) - time();
}
