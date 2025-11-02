<?php
session_start();
require_once 'config.php';
require_once 'khalti.php';

if(!isset($_SESSION['id'])){
    echo "Unauthorized"; exit;
}
// print_r($_SESSION);die;
$id = $_SESSION['id'];

$query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$query->execute([$id]);

$user = $query->fetch(PDO::FETCH_ASSOC);
// print_r($user);die;
$cart = $_SESSION['cart'];
// print_r($user);die;
$khalti= khalti_checkout($cart, $user);
// var_dump($khalti);die;


// header('Location:view_cart.php');
// echo "success";
?>
