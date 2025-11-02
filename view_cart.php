<?php
session_start();
require_once 'config.php';

// Redirect if not logged in or if user is admin
if (!isset($_SESSION['id']) || $_SESSION['role'] == 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id'];

// Handle remove item request
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $cart_id = $_GET['cart_id'];
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$cart_id, $id]);
    header("Location: view_cart.php");
    exit;
}

// Fetch cart items
$stmt = $pdo->prepare("SELECT c.*, p.title, p.price, p.offer, p.image 
                       FROM cart c 
                       JOIN products p ON c.product_id = p.id 
                       WHERE c.user_id = ?");
$stmt->execute([$id]);
$cart_items = $stmt->fetchAll();

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['offer'] * $item['quantity'];
}
$stmt = $pdo->prepare("SELECT o.*, p.title, p.image 
                       FROM orders o 
                       JOIN products p ON o.product_id = p.id 
                       WHERE o.user_id = ? 
                       ORDER BY o.created_at DESC");
$stmt->execute([$id]);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Cart - Mero-Pasal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
body { font-family: 'Poppins', sans-serif; background: #f8f9fa; }
.navbar { box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.navbar-brand, .nav-link { color: #fff !important; }
.nav-link:hover { color: #007bff !important; }
.cart-image { max-width: 50px; max-height: 50px; object-fit: cover; border-radius: 5px; }
.table { background: #fff; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
.table thead { background: #007bff; color: #fff; }
.btn-danger, .btn-success, .btn-primary { border-radius: 10px; padding: 8px 15px; transition: background 0.3s ease; }
.btn-danger:hover { background: #c82333; }
.btn-success:hover { background: #218838; }
footer { background: #343a40; color: #fff; padding: 20px 0; margin-top: 470px; }

</style>
</head>
<body>
<script src="https://khalti.com/static/khalti-checkout.js"></script>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
<a class="navbar-brand" href="index.php">Mero-Pasal</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNav">
<ul class="navbar-nav ms-auto">
<li class="nav-item"><a class="nav-link" href="products.php"><i class="fas fa-arrow-left me-2"></i>Back to Products</a></li>
<li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
</ul>
</div>
</div>
</nav>

<div class="container mt-5">
<h2 class="mb-4">Your Cart</h2>
<?php if(empty($cart_items)): ?>
<p class="text-muted">Your cart is empty.</p>
<?php else: ?>
<table class="table table-bordered table-hover">
<thead>
<tr>
<th>Image</th>
<th>Product</th>
<th>Price</th>
<th>Offer</th>
<th>Quantity</th>
<th>Total</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php foreach($cart_items as $item): ?>
<tr>
<td><img src="<?= htmlspecialchars($item['image']) ?>" class="cart-image"></td>
<td><?= htmlspecialchars($item['title']) ?></td>
<td>Rs <?= $item['price'] ?></td>
<td>Rs <?= $item['offer'] ?></td>
<td>
    <form action="update_cart.php" method="POST" class="d-flex align-items-center">
        <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
        <button type="submit" name="decrease" class="btn btn-sm btn-outline-secondary me-2">-</button>
        <input type="text" name="quantity" value="<?= $item['quantity'] ?>" class="form-control text-center" style="width:60px;" readonly>
        <button type="submit" name="increase" class="btn btn-sm btn-outline-secondary ms-2">+</button>
    </form>
</td>
<td>Rs <?= $item['offer'] * $item['quantity'] ?></td>
<td>
<?php
$_SESSION['cart'] = $item;
?>

<a href="view_cart.php?action=remove&cart_id=<?= $item['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remove this item?')"><i class="fas fa-trash-alt me-2"></i>Remove</a>
<a href="process_order.php" class="btn btn-primary btn-sm" ><i class="fas fa-shopping-cart me-2"></i>Buy</a>
</td>


</tr>
<?php endforeach; ?>
</tbody>
</table>
<div class="d-flex justify-content-between align-items-center mt-4">
<h4>Total: RS <?= $total ?></h4>
</div>
<?php endif; ?>
</div>
<hr class="my-5">
<div class="container mt-5">
<h2 class="mb-4">Your Orders</h2>
<?php if(empty($orders)): ?>
    <p class="text-muted">You have not placed any orders yet.</p>
<?php else: ?>
<table class="table table-bordered table-hover orders-table">

    <thead>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Payment</th>
            <th>Status</th>
            <th>Ordered At</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($orders as $order): ?>
        <tr>
            <td><img src="<?= htmlspecialchars($order['image']) ?>" class="cart-image"></td>
            <td><?= htmlspecialchars($order['title']) ?></td>
            <td><?= $order['quantity'] ?></td>
            <td>Rs <?= $order['total'] ?></td>
            <td><?= htmlspecialchars($order['payment']) ?></td>
            <td>
                <?php if($order['status'] == 'Placed'): ?>
                    <span class="badge bg-success">Placed</span>
                <?php else: ?>
                    <span class="badge bg-secondary"><?= htmlspecialchars($order['status']) ?></span>
                <?php endif; ?>
            </td>
            <td><?= $order['created_at'] ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
                </div>

<footer class="text-center">
<div class="container">
<p>&copy; 2025 Mero-Pasal. All Rights Reserved.</p>
</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Confirm Order JS
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.confirmOrderBtn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get the form
            let form = document.getElementById('buyForm' + this.dataset.cartid);
            console.log(form);
            // Get all form values
            let payment = form.querySelector('#payement').value; // Fixed typo
            let cartId = form.querySelector('input[name="cart_id"]').value;
            let productId = form.querySelector('input[name="product_id"]').value;
            let productName = form.querySelector('input[name="product_name"]').value;
            let quantity = form.querySelector('input[name="quantity"]').value;
            let total = form.querySelector('input[name="total"]').value;
            let name = form.querySelector('input[name="name"]').value;
            let address = form.querySelector('textarea[name="address"]').value;
            let contact = form.querySelector('input[name="contact"]').value;
            
            console.log({
                payment, cartId, productId, productName, 
                quantity, total, name, address, contact
            });

            if(payment === 'COD') {
                // ... rest of your COD logic
            } else if(payment === 'Khalti') {
                // ... rest of your Khalti logic
            }
        });
    });
});
</script>
</body>
</html>
