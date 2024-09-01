<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $roll_number = $_POST['roll_number'];
    $class = $_POST['class'];

    $sql = "INSERT INTO students (name, roll_number, class) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $error = 'Error: Could not prepare SQL statement. ' . mysqli_error($link);
    } else {
        mysqli_stmt_bind_param($stmt, "sss", $name, $roll_number, $class);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Student added successfully!";
        } else {
            $error = "Error: Could not add student. " . mysqli_error($link);
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Attendance Management System</title>
    <link href="../css/styles.css" rel="stylesheet">
    <style>
        body {
            background-color: #eaf2f8;
            font-family: 'Arial', sans-serif;
        }
        header {
            text-align: center;
            padding: 20px;
            background-color: #2c3e50;
            color: white;
        }
        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: calc(100vh - 100px);
        }
        .message {
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        .error-message {
            color: red;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        form {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        input[type="text"]:focus {
            border-color: #2980b9;
            box-shadow: 0 0 5px rgba(41, 128, 185, 0.5);
            outline: none;
        }
        .btn {
            background-color: #2980b9;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease-in-out;
            margin-bottom: 15px;
        }
        .btn:hover {
            background-color: #1f618d;
        }
        .back-btn {
            background-color: #95a5a6;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease-in-out;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .back-btn:hover {
            background-color: #7f8c8d;
        }
    </style>
</head>
<body>
    <header>
        <h1>Add Student</h1>
    </header>
    <main>
        <?php if (!empty($success)): ?>
            <div class="message"><?php echo $success; ?></div>
        <?php elseif (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="add_student.php">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="roll_number">Roll Number:</label>
                <input type="text" id="roll_number" name="roll_number" required>
            </div>
            <div>
                <label for="class">Class:</label>
                <input type="text" id="class" name="class" required>
            </div>
            <button type="submit" class="btn">Add Student</button>
        </form>
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </main>
</body>
</html>
