CREATE DATABASE IF NOT EXISTS attendance_management;
USE attendance_management;

CREATE TABLE IF NOT EXISTS admins (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS students (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    roll_number VARCHAR(50) NOT NULL UNIQUE,
    class VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS attendance (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    date DATE NOT NULL,
    status ENUM('Present', 'Absent') NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id)
);

-- Insert a default admin user
INSERT INTO admins (username, password) VALUES ('admin', 'password');
