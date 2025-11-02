<?php
session_start();
require_once 'config.php';
var_dump($_SESSION);die();
if(!isset($_SESSION['id'])){
    echo json_encode(['success'=>false]); exit;
}

$id = $_SESSION['id'];
$input = json_decode(file_get_contents('php://input'), true);

$payload = $input['payload'] ?? null;
$cart_id = $input['cart_id'] ?? '';
$product_id = $input['product_id'] ?? '';
$quantity = $input['quantity'] ?? '';
$total = $input['total'] ?? '';
$name = $input['name'] ?? '';
$address = $input['address'] ?? '';
$contact = $input['contact'] ?? '';

if(!$payload || !$cart_id || !$product_id){
    echo json_encode(['success'=>false]); exit;
}

// Verify payment
$token = $payload['token'];
$amount = $payload['amount']; // in paisa

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://khalti.com/api/v2/payment/verify/");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['token'=>$token,'amount'=>$amount]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Key test_secret_key_dc74...", // replace with your secret key
    "Content-Type: application/json"
]);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if(isset($data['idx'])){
    $stmt = $pdo->prepare("INSERT INTO orders 
(user_id, product_id, quantity, total, name, address, contact, payment, payment_status, status, created_at)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

$stmt->execute([
    $id,
    $product_id,
    $quantity,
    $total,
    $name,
    $address,
    $contact,
    'Khalti',         // payment method
    'Paid',           // payment_status
    'Completed'       // order status
]);


    $stmt = $pdo->prepare("DELETE FROM cart WHERE id=? AND user_id=?");
    $stmt->execute([$cart_id, $id]);

    echo json_encode(['success'=>true]);
}else{
    echo json_encode(['success'=>false]);
}
?>
