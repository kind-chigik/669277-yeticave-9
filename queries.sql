USE yeticave;

/* заполняем таблицу категорий */
INSERT INTO category (name, alias)
VALUES
  ('Доски и лыжи', 'boards'),
  ('Крепления', 'attachment'),
  ('Ботинки', 'boots'),
  ('Одежда', 'clothing'),
  ('Инструменты', 'tools'),
  ('Разное', 'other');
  ('Другое', 'different');

/* заполняем таблицу пользователей */
INSERT INTO user (creation_time, email, name, password, avatar, contact)
VALUES
  ('2019.05.01', 'elenak@mail.ru', 'elena', 'fhd8mk', 'img/avatar.jpg', '89857773322'),
  ('2019.04.01', 'alex56@mail.ru', 'alex', 'fcdd8mk', 'img/avatar.jpg', '89857773321');

/* заполняем таблицу лотов */
INSERT INTO lot (creation_time, name, description, image, start_price, end_time, step, user_id, category_id)
VALUES
  ('2019.05.01', '2014 Rossignol District Snowboard', 'Описание 2014 Rossignol District Snowboard', 'img/lot-1.jpg', 10999, '2019.06.01', 500, 1, 1),
  ('2019.05.02', 'DC Ply Mens 2016/2017 Snowboard', 'Описание DC Ply Mens 2016/2017 Snowboard', 'img/lot-2.jpg', 159999, '2019.06.02', 500, 2, 1),
  ('2019.05.03', 'Крепления Union Contact Pro 2015 года размер L/XL', 'Описание Крепления Union Contact Pro 2015 года размер L/XL', 'img/lot-3.jpg', 8000, '2019.06.03', 500, 1, 2),
  ('2019.05.04', 'Ботинки для сноуборда DC Mutiny Charocal', 'Описание Ботинки для сноуборда DC Mutiny Charocal', 'img/lot-4.jpg', 10999, '2019.06.04', 500, 1, 3),
  ('2019.05.05', 'Куртка для сноуборда DC Mutiny Charocal', 'Описание Куртка для сноуборда DC Mutiny Charocal', 'img/lot-5.jpg', 7500, '2019.06.05', 500, 1, 4),
  ('2019.05.06', 'Маска Oakley Canopy', 'Описание Маска Oakley Canopy', 'img/lot-6.jpg', 5400, '2019.06.06', 500, 2, 6);
  ('2019.05.14', 'Маска Oakley Canopy s2', 'Описание Маска Oakley Canopy s2', 'img/lot-6.jpg', 5600, '2019.06.14', 500, 2, 6);

/* заполняем таблицу ставок */
INSERT INTO rate (creation_time, amount, user_id, lot_id)
VALUES
  ('2019.05.02', '11900', 2, 1),
  ('2019.05.03', '12900', 2, 1),
  ('2019.05.03', '13900', 2, 1),
  ('2019.05.07', '6400', 1, 6);

/* показываем список всех категорий */
SELECT * FROM category;

/* показываем самые новые открытые лоты */
SELECT l.id, l.name, start_price, image, category_id, MAX(r.amount)
FROM lot l
LEFT JOIN category c ON category_id = c.id
LEFT JOIN rate r ON r.lot_id = l.id
WHERE end_time > CURRENT_TIMESTAMP
GROUP BY l.id
ORDER BY l.creation_time DESC;

/* показываем лот по его id */
SELECT l.*, c.name FROM lot l
LEFT JOIN category c ON category_id = c.id
WHERE l.id = 1;

/* меняем название лота по его id */
UPDATE lot SET NAME = '2014 Rossignol District Snowboard 45'
WHERE id = 1;

/* выводим список ставок для лота по его id */
SELECT amount FROM rate
WHERE lot_id = 1
ORDER BY creation_time DESC;

/* добавим индексы для поиска */
CREATE FULLTEXT INDEX i_search ON lot(name, description);
