<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] == 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'add') {
    $user_id = $_SESSION['id'];
    $product_id = $_GET['product_id'];

    // Check if product already exists in the cart
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $existing = $stmt->fetch();

    if ($existing) {
        // Update quantity if already in cart
        $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id = ?");
        $stmt->execute([$existing['id']]);
    } else {
        // Insert new product in cart
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $stmt->execute([$user_id, $product_id]);
    }

    // ✅ Store success message in session
    $_SESSION['message'] = "✅ Product added to cart successfully!";
    header("Location: products.php");
    exit;
}
