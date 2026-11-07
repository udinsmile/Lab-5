-- Run this in phpMyAdmin > SQL tab
CREATE DATABASE IF NOT EXISTS mydb;
USE mydb;

CREATE TABLE IF NOT EXISTS persons (
  ID        INTEGER PRIMARY KEY AUTO_INCREMENT,
  LastName  VARCHAR(255) NOT NULL,
  FirstName VARCHAR(255),
  Adress    VARCHAR(255),
  City      VARCHAR(255)
);

-- Sample data (change to anything you like)
INSERT INTO persons (LastName, FirstName, Adress, City) VALUES
('Ahmad', 'Ali', 'No 1, Jalan Mawar', 'Batu Pahat'),
('Tan', 'Mei Ling', 'No 22, Taman Universiti', 'Johor Bahru'),
('Kumar', 'Ravi', 'No 5, Jalan Kenanga', 'Muar');
