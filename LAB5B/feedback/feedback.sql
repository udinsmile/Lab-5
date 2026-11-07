-- Course Feedback - database
-- Run in phpMyAdmin > SQL tab (or import this file)

CREATE DATABASE IF NOT EXISTS course_feedback;
USE course_feedback;

CREATE TABLE IF NOT EXISTS feedback (
  id                INT NOT NULL AUTO_INCREMENT,
  student_name      VARCHAR(100) NOT NULL,
  matric_no         CHAR(8)      NOT NULL,          -- 2 letters + 6 digits, e.g. AI230123
  course_code       VARCHAR(10)  NOT NULL,
  rating_content    TINYINT UNSIGNED NOT NULL,      -- 1 to 5
  rating_teaching   TINYINT UNSIGNED NOT NULL,      -- 1 to 5
  rating_assessment TINYINT UNSIGNED NOT NULL,      -- 1 to 5
  comment           TEXT NULL,                      -- optional
  submitted_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_course (course_code),
  CHECK (rating_content BETWEEN 1 AND 5),
  CHECK (rating_teaching BETWEEN 1 AND 5),
  CHECK (rating_assessment BETWEEN 1 AND 5)
);
