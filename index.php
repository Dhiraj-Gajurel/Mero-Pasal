<?php
session_start();
if (isset($_SESSION['id'])) {
    header("Location: products.php");
} else {
    header("Location: dashboard.php");
}
exit;
?>