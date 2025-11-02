<?php
require_once 'config.php'; // make sure this file contains the PDO connection ($pdo)

$username = "dhiraj";
$password = password_hash("dhiraj123", PASSWORD_DEFAULT);
$role = "user";

$stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->execute([$username, $password, $role]);

echo "✅ user created!";
?>
