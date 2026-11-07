-- Paste all of this into phpMyAdmin > SQL tab and click Go (run once)

-- ===== Lab 5A Exercise 2: ShoutBox =====
CREATE DATABASE IF NOT EXISTS shoutbox;
USE shoutbox;
CREATE TABLE IF NOT EXISTS shouts (
  shout_id   INT NOT NULL AUTO_INCREMENT,
  shout_text VARCHAR(300) NOT NULL,
  shout_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (shout_id)
);

-- ===== Lab 5A Exercise 3: mydb =====
CREATE DATABASE IF NOT EXISTS mydb;
USE mydb;
CREATE TABLE IF NOT EXISTS persons (
  ID        INTEGER PRIMARY KEY AUTO_INCREMENT,
  LastName  VARCHAR(255) NOT NULL,
  FirstName VARCHAR(255),
  Adress    VARCHAR(255),
  City      VARCHAR(255)
);
INSERT INTO persons (LastName, FirstName, Adress, City) VALUES
('Ahmad', 'Ali', 'No 1, Jalan Mawar', 'Batu Pahat'),
('Tan', 'Mei Ling', 'No 22, Taman Universiti', 'Johor Bahru'),
('Kumar', 'Ravi', 'No 5, Jalan Kenanga', 'Muar');

-- ===== Lab 5A Exercises 4 & 5: register =====
CREATE DATABASE IF NOT EXISTS register;
USE register;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `trn_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `new_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trn_date` datetime NOT NULL,
  `name` varchar(50) NOT NULL,
  `age` int(11) NOT NULL,
  `submittedby` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
);
