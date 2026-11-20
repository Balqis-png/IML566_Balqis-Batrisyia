CREATE DATABASE IF NOT EXISTS studenthub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE studenthub;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  username VARCHAR(60) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS students (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_no VARCHAR(30) NOT NULL UNIQUE,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  programme VARCHAR(150) NOT NULL,
  year_of_study TINYINT UNSIGNED NOT NULL DEFAULT 1,
  status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS courses (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  course_code VARCHAR(30) NOT NULL UNIQUE,
  course_name VARCHAR(150) NOT NULL,
  lecturer VARCHAR(120) DEFAULT '',
  enrolled INT UNSIGNED NOT NULL DEFAULT 0,
  capacity INT UNSIGNED NOT NULL DEFAULT 30,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Sample data for dashboard testing. You may edit or delete these records.
INSERT IGNORE INTO students (student_no, full_name, email, programme, year_of_study, status) VALUES
('20260001','Aina Sofea','aina@example.com','Information Management',2,'Active'),
('20260002','Daniel Hakim','daniel@example.com','Computer Science',3,'Active'),
('20260003','Nur Izzati','izzati@example.com','Information Management',1,'Active'),
('20260004','Amirul Naim','amirul@example.com','Business Management',2,'Inactive'),
('20260005','Siti Aisyah','aisyah@example.com','Computer Science',4,'Active');

INSERT IGNORE INTO courses (course_code, course_name, lecturer, enrolled, capacity) VALUES
('IMS566','Advanced Web Design Development','Dr. Farah',32,40),
('IMS555','Records Management','Mr. Hakim',25,35),
('CSC574','Dynamic Web Application','Ms. Nadia',28,30),
('MGT420','Principles of Management','Dr. Amir',19,35);
