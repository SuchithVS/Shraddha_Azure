<?php
session_start();
require_once '../config.php';

if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_logged_in'] = true;
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid username or password";
            }
        } else {
            $error = "Invalid username or password";
        }
        mysqli_stmt_close($stmt);
    } else {
        die("ERROR: Could not prepare SQL statement. " . mysqli_error($link));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Attendance Management System</title>
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
        .welcome-message {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
            font-size: 18px;
            font-weight: bold;
        }
        form {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
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
        }
        .btn:hover {
            background-color: #1f618d;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #2980b9;
            box-shadow: 0 0 5px rgba(41, 128, 185, 0.5);
            outline: none;
        }
        form {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Login</h1>
    </header>
    <main>
        <div class="welcome-message">
            Welcome! Please enter your credentials to access the admin dashboard.
        </div>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </main>
</body>
</html>
