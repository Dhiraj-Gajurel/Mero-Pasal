<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id'];
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];

    // Check if already rated
    $stmt = $pdo->prepare("SELECT id FROM product_ratings WHERE product_id = ? AND user_id = ?");
    $stmt->execute([$product_id, $user_id]);

    if ($stmt->rowCount() > 0) {
        // Update rating
        $stmt = $pdo->prepare("UPDATE product_ratings SET rating = ? WHERE product_id = ? AND user_id = ?");
        $stmt->execute([$rating, $product_id, $user_id]);
    } else {
        // Insert new rating
        $stmt = $pdo->prepare("INSERT INTO product_ratings (product_id, user_id, rating) VALUES (?, ?, ?)");
        $stmt->execute([$product_id, $user_id, $rating]);
    }

    header("Location: product_details.php?id=" . $product_id);
    exit;
}
