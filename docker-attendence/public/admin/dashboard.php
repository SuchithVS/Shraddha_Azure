<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Attendance Management System</title>
    <link href="../css/styles.css" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>
    <main>
        <div class="welcome">
            Welcome, Admin! Manage your students' attendance with ease.
        </div>
        <div class="dashboard-cards">
            <div class="card" onclick="location.href='add_student.php';">
                Add Student
            </div>
            <div class="card" onclick="location.href='mark_attendance.php';">
                Mark Attendance
            </div>
            <div class="card" onclick="location.href='view_attendance.php';">
                View Attendance
            </div>
            <div class="card" onclick="location.href='logout.php';">
                Logout
            </div>
        </div>
    </main>
</body>
</html>
