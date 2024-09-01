<?php
require_once 'config.php';

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if (mysqli_query($link, $sql)) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . mysqli_error($link) . "<br>";
}

// Select the database
mysqli_select_db($link, DB_NAME);

// Create admins table
$sql = "CREATE TABLE IF NOT EXISTS admins (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";

if (mysqli_query($link, $sql)) {
    echo "Table 'admins' created successfully<br>";
} else {
    echo "Error creating table: " . mysqli_error($link) . "<br>";
}

// Insert default admin user
$default_username = 'admin';
$default_password = password_hash('password', PASSWORD_DEFAULT);

$sql = "INSERT IGNORE INTO admins (username, password) VALUES (?, ?)";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "ss", $default_username, $default_password);

if (mysqli_stmt_execute($stmt)) {
    echo "Default admin user created successfully<br>";
} else {
    echo "Error creating default admin user: " . mysqli_error($link) . "<br>";
}

mysqli_close($link);
?>