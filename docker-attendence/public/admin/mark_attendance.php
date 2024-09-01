<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Fetch students
$sql = "SELECT * FROM students ORDER BY roll_number";
$result = mysqli_query($link, $sql);

if ($result === false) {
    die('Error: Could not fetch students. ' . mysqli_error($link));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];

    if (isset($_POST['attendance'])) {
        foreach ($_POST['attendance'] as $student_id => $status) {
            $sql = "INSERT INTO attendance (student_id, date, status) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($link, $sql);

            if ($stmt === false) {
                die('Error: Could not prepare SQL statement. ' . mysqli_error($link));
            }

            mysqli_stmt_bind_param($stmt, "iss", $student_id, $date, $status);
            mysqli_stmt_execute($stmt);
        }
        $success = "Attendance marked successfully!";
    } else {
        $error = "Error: No attendance data provided.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance - Attendance Management System</title>
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
        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        form {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }
        label {
            font-weight: bold;
            margin-bottom: 8px;
        }
        input[type="date"] {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            width: 100%;
            max-width: 300px;
            margin-bottom: 20px;
        }
        input[type="date"]:focus {
            border-color: #2980b9;
            box-shadow: 0 0 5px rgba(41, 128, 185, 0.5);
            outline: none;
        }
        table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #2980b9;
            color: white;
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
        <h1>Mark Attendance</h1>
    </header>
    <main>
        <?php if (isset($success)): ?>
            <p class="message"><?php echo htmlspecialchars($success); ?></p>
        <?php elseif (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST">
            <div>
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Roll Number</th>
                        <th>Name</th>
                        <th>Present</th>
                        <th>Absent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($student = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['roll_number']); ?></td>
                        <td><?php echo htmlspecialchars($student['name']); ?></td>
                        <td>
                            <input type="radio" name="attendance[<?php echo $student['id']; ?>]" value="Present" required>
                        </td>
                        <td>
                            <input type="radio" name="attendance[<?php echo $student['id']; ?>]" value="Absent" required>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <button type="submit" class="btn">Mark Attendance</button>
        </form>
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </main>
</body>
</html>
