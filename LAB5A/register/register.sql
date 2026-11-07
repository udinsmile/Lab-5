-- Run this in phpMyAdmin > SQL tab (Exercise 4 + Exercise 5)
CREATE DATABASE IF NOT EXISTS register;
USE register;

-- Exercise 4
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `trn_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
);

-- Exercise 5
CREATE TABLE IF NOT EXISTS `new_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trn_date` datetime NOT NULL,
  `name` varchar(50) NOT NULL,
  `age` int(11) NOT NULL,
  `submittedby` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
);
