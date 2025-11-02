<?php
session_start();
require_once 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];

if (isset($_POST['cart_id'])) {
    $cart_id = $_POST['cart_id'];

    // Fetch current quantity
    $stmt = $pdo->prepare("SELECT quantity FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$cart_id, $user_id]);
    $cart_item = $stmt->fetch();

    if ($cart_item) {
        $quantity = $cart_item['quantity'];

        if (isset($_POST['increase'])) {
            $quantity++;
        } elseif (isset($_POST['decrease']) && $quantity > 1) {
            $quantity--;
        }

        // Update cart quantity
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$quantity, $cart_id, $user_id]);
    }
}

// Redirect back to cart page
header("Location: view_cart.php");
exit;
