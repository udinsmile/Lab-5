-- Mini Library System - database
-- Run in phpMyAdmin > SQL tab (or import this file)

CREATE DATABASE IF NOT EXISTS library;
USE library;

-- Staff who can log in
CREATE TABLE IF NOT EXISTS users (
  id         INT NOT NULL AUTO_INCREMENT,
  username   VARCHAR(50)  NOT NULL,
  password   VARCHAR(255) NOT NULL,          -- password_hash() output (about 60 chars, room to grow)
  created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_username (username)          -- no two staff with the same username
);

-- Books owned by the resource room
CREATE TABLE IF NOT EXISTS books (
  book_id      INT NOT NULL AUTO_INCREMENT,
  title        VARCHAR(255) NOT NULL,
  author       VARCHAR(150) NOT NULL,
  publish_year SMALLINT UNSIGNED NOT NULL,   -- 4-digit year (YEAR type only allows 1901-2155)
  category     VARCHAR(50)  NOT NULL,
  added_by     VARCHAR(50)  NOT NULL,        -- username of the staff who added the book
  date_added   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (book_id),
  CONSTRAINT fk_books_user FOREIGN KEY (added_by) REFERENCES users (username)
    ON UPDATE CASCADE
);
