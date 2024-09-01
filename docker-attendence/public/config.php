<?php
// config.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('DB_SERVER', 'mysql');
define('DB_USERNAME', 'user1');
define('DB_PASSWORD', 'passwd');
define('DB_NAME', 'attendance_management');

// Connect without selecting a database first
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Create the database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if (!mysqli_query($link, $sql)) {
    die("Error creating database: " . mysqli_error($link));
}

// Select the database
if (!mysqli_select_db($link, DB_NAME)) {
    die("Error selecting database: " . mysqli_error($link));
}

// Create the admins table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS admins (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";

if (!mysqli_query($link, $sql)) {
    die("Error creating admins table: " . mysqli_error($link));
}

// Create the students table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS students (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    roll_number VARCHAR(50) NOT NULL UNIQUE,
    class VARCHAR(100) NOT NULL
)";

if (!mysqli_query($link, $sql)) {
    die("Error creating students table: " . mysqli_error($link));
}

// Create the attendance table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS attendance (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    date DATE NOT NULL,
    status ENUM('Present', 'Absent') NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id)
)";

if (!mysqli_query($link, $sql)) {
    die("Error creating attendance table: " . mysqli_error($link));
}

// Check if the admin user exists, if not, create it
$check_admin = "SELECT * FROM admins WHERE username = 'admin'";
$result = mysqli_query($link, $check_admin);

if (mysqli_num_rows($result) == 0) {
    $default_username = 'admin';
    $default_password = password_hash('password', PASSWORD_DEFAULT);

    $insert_admin = "INSERT INTO admins (username, password) VALUES (?, ?)";
    $stmt = mysqli_prepare($link, $insert_admin);
    mysqli_stmt_bind_param($stmt, "ss", $default_username, $default_password);

    if (!mysqli_stmt_execute($stmt)) {
        die("Error creating default admin user: " . mysqli_error($link));
    }
    mysqli_stmt_close($stmt);
}
?>