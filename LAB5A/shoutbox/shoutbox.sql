-- Run this in phpMyAdmin > SQL tab
CREATE DATABASE IF NOT EXISTS shoutbox;
USE shoutbox;

CREATE TABLE IF NOT EXISTS shouts (
  shout_id   INT NOT NULL AUTO_INCREMENT,
  shout_text VARCHAR(300) NOT NULL,
  shout_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (shout_id)
);
