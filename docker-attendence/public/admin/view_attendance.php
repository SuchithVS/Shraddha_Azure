<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Fetch attendance records
$sql = "SELECT students.roll_number, students.name, attendance.date, attendance.status 
        FROM attendance 
        JOIN students ON attendance.student_id = students.id 
        ORDER BY attendance.date DESC, students.roll_number ASC";
$result = mysqli_query($link, $sql);

if ($result === false) {
    die('Error: Could not fetch attendance records. ' . mysqli_error($link));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance - Attendance Management System</title>
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
        table {
            width: 100%;
            max-width: 800px;
            margin-bottom: 20px;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
        .search-filter {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 800px;
        }
        .search-box {
            display: flex;
            align-items: center;
            width: 70%;
        }
        .search-box input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            margin-right: 10px;
        }
        .search-box button {
            padding: 10px 20px;
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }
        .search-box button:hover {
            background-color: #1f618d;
        }
        .sort-box {
            width: 30%;
            text-align: right;
        }
        .sort-box button {
            padding: 10px 20px;
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }
        .sort-box button:hover {
            background-color: #1f618d;
        }
        .btn {
            background-color: #2980b9;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
        <h1>View Attendance</h1>
    </header>
    <main>
        <div class="search-filter">
            <div class="search-box">
                <input type="text" placeholder="Search by roll number or name" id="searchInput">
                <button onclick="searchTable()">Search</button>
            </div>
            <!-- <div class="sort-box">
                <button onclick="sortTable()">Sort by Date</button>
            </div> -->
        </div>
        <table id="attendanceTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Roll Number</th>
                    <th>Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($record = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['date']); ?></td>
                    <td><?php echo htmlspecialchars($record['roll_number']); ?></td>
                    <td><?php echo htmlspecialchars($record['name']); ?></td>
                    <td><?php echo htmlspecialchars($record['status']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </main>
    <script>
        function searchTable() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("attendanceTable");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
                tr[i].style.display = "none";
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        }
                    }
                }
            }
        }

        function sortTable() {
    var table, rows, switching, i, x, y, shouldSwitch;
    table = document.getElementById("attendanceTable");
    switching = true;

    while (switching) {
        switching = false;
        rows = table.rows;

        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[0];
            y = rows[i + 1].getElementsByTagName("TD")[0];

            // Compare dates by converting strings to Date objects
            if (new Date(x.innerHTML) < new Date(y.innerHTML)) {
                shouldSwitch = true;
                break;
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }
}

    </script>
</body>
</html>
