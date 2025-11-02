<?php
session_start();
require_once 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['id']) || $_SESSION['role'] == 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id'];

// ✅ Get product from cart
if (!isset($_GET['cart_id'])) {
    header("Location: view_cart.php");
    exit;
}
$cart_id = $_GET['cart_id'];

$stmt = $pdo->prepare("SELECT c.*, p.title, p.offer, p.image 
                       FROM cart c 
                       JOIN products p ON c.product_id = p.id 
                       WHERE c.id = ? AND c.user_id = ?");
$stmt->execute([$cart_id, $id]);
$item = $stmt->fetch();

if (!$item) {
    header("Location: view_cart.php");
    exit;
}

// ✅ Handle order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $payment = $_POST['payment'];

    $total = $item['offer'] * $item['quantity'];

    // Insert order into database
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, product_id, quantity, total, name, address, contact, payment, status, created_at) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())");
    $stmt->execute([$id, $item['product_id'], $item['quantity'], $total, $name, $address, $contact, $payment]);

    // Remove from cart after order
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$cart_id, $id]);

    header("Location: orders.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Now - Mero-Pasal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Checkout</h2>
    <div class="card p-4 shadow-sm">
        <div class="d-flex align-items-center mb-3">
            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" width="80" class="me-3 rounded">
            <div>
                <h5><?= htmlspecialchars($item['title']) ?></h5>
                <p>Price: ₹<?= $item['offer'] ?> × <?= $item['quantity'] ?></p>
                <h6>Total: ₹<?= $item['offer'] * $item['quantity'] ?></h6>
            </div>
        </div>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Payment Method</label>
                <select name="payment" class="form-select" required>
                    <option value="COD">Cash on Delivery</option>
                    <option value="Khalti">Khalti</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Confirm Order</button>
            <a href="view_cart.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>
