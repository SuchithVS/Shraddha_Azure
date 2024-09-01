<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $medicine_id = $_POST['medicine_id'];
    $quantity = $_POST['quantity'];
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];

    // Start transaction
    mysqli_begin_transaction($link);

    try {
        // Get medicine details
        $sql = "SELECT name, price, quantity FROM medicines WHERE id = ? FOR UPDATE";
        $stmt = mysqli_prepare($link, $sql);
        if ($stmt === false) {
            throw new Exception("Error preparing statement: " . mysqli_error($link));
        }
        mysqli_stmt_bind_param($stmt, "i", $medicine_id);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error executing statement: " . mysqli_stmt_error($stmt));
        }
        $result = mysqli_stmt_get_result($stmt);
        $medicine = mysqli_fetch_assoc($result);

        if ($medicine['quantity'] < $quantity) {
            throw new Exception("Not enough stock available.");
        }

        $total_amount = $medicine['price'] * $quantity;

        // Create order
        $sql = "INSERT INTO orders (customer_name, customer_email, total_amount) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($link, $sql);
        if ($stmt === false) {
            throw new Exception("Error preparing statement: " . mysqli_error($link));
        }
        mysqli_stmt_bind_param($stmt, "ssd", $customer_name, $customer_email, $total_amount);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error executing statement: " . mysqli_stmt_error($stmt));
        }
        $order_id = mysqli_insert_id($link);

        // Create order item
        $sql = "INSERT INTO order_items (order_id, medicine_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($link, $sql);
        if ($stmt === false) {
            throw new Exception("Error preparing statement: " . mysqli_error($link));
        }
        mysqli_stmt_bind_param($stmt, "iiid", $order_id, $medicine_id, $quantity, $medicine['price']);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error executing statement: " . mysqli_stmt_error($stmt));
        }

        // Update medicine quantity
        $new_quantity = $medicine['quantity'] - $quantity;
        $sql = "UPDATE medicines SET quantity = ? WHERE id = ?";
        $stmt = mysqli_prepare($link, $sql);
        if ($stmt === false) {
            throw new Exception("Error preparing statement: " . mysqli_error($link));
        }
        mysqli_stmt_bind_param($stmt, "ii", $new_quantity, $medicine_id);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error executing statement: " . mysqli_stmt_error($stmt));
        }

        // Commit transaction
        mysqli_commit($link);

        // Redirect to confirmation page
        header("Location: order_confirmation.php?order_id=" . $order_id);
        exit();
    } catch (Exception $e) {
        mysqli_rollback($link);
        $error_message = $e->getMessage();
    }
}

// If it's a GET request or there was an error, display the form
$medicine_id = $_GET['medicine_id'] ?? '';
$sql = "SELECT * FROM medicines WHERE id = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $medicine_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$medicine = mysqli_fetch_assoc($result);

if (!$medicine) {
    die("Medicine not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Medicine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Order Medicine</h1>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="hidden" name="medicine_id" value="<?php echo $medicine['id']; ?>">
            <div class="mb-3">
                <label for="medicine_name" class="form-label">Medicine Name</label>
                <input type="text" class="form-control" id="medicine_name" value="<?php echo htmlspecialchars($medicine['name']); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="text" class="form-control" id="price" value="$<?php echo htmlspecialchars(number_format($medicine['price'], 2)); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" max="<?php echo $medicine['quantity']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="customer_name" class="form-label">Your Name</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
            </div>
            <div class="mb-3">
                <label for="customer_email" class="form-label">Your Email</label>
                <input type="email" class="form-control" id="customer_email" name="customer_email" required>
            </div>
            <button type="submit" class="btn btn-primary">Place Order</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>