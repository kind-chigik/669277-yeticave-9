CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE  utf8_general_ci;
USE yeticave;

CREATE TABLE category (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(128) NOT NULL UNIQUE,
  alias VARCHAR(64) NOT NULL UNIQUE
);

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  creation_time DATETIME DEFAULT CURRENT_TIMESTAMP,
  email VARCHAR(128) NOT NULL UNIQUE,
  name VARCHAR(64) NOT NULL,
  password VARCHAR(64) NOT NULL,
  avatar VARCHAR(128),
  contact TEXT NOT NULL,
  INDEX (creation_time)
);

CREATE TABLE lot (
  id INT AUTO_INCREMENT PRIMARY KEY,
  creation_time DATETIME DEFAULT CURRENT_TIMESTAMP,
  name VARCHAR(128) NOT NULL,
  description TEXT NOT NULL,
  image VARCHAR(128) NOT NULL,
  start_price INT NOT NULL,
  end_time DATETIME NOT NULL,
  step INT NOT NULL,
  user_id INT NOT NULL,
  winner_id INT,
  category_id INT NOT NULL,
  INDEX (creation_time),
  FOREIGN KEY (user_id) REFERENCES user (id),
  FOREIGN KEY (winner_id) REFERENCES user (id),
  FOREIGN KEY (category_id) REFERENCES category (id)
);

CREATE TABLE rate (
  id INT AUTO_INCREMENT PRIMARY KEY,
  creation_time DATETIME DEFAULT CURRENT_TIMESTAMP,
  amount INT NOT NULL,
  user_id INT NOT NULL,
  lot_id INT NOT NULL,
  INDEX (creation_time),
  FOREIGN KEY (user_id) REFERENCES user (id),
  FOREIGN KEY (lot_id) REFERENCES lot (id)
);


