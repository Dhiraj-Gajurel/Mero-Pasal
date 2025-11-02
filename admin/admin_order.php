<?php
session_start();
require_once '../config.php';

// ✅ Only admin can access this page
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// ✅ Handle status update
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);

    $_SESSION['message'] = "Order status updated successfully!";
    header("Location: admin_orders.php");
    exit;
}

// ✅ Fetch all orders with user + product info
$stmt = $pdo->prepare("SELECT o.*, p.title, p.image, u.username, u.address, u.contact
                       FROM orders o
                       JOIN products p ON o.product_id = p.id
                       JOIN users u ON o.user_id = u.id
                       ORDER BY o.created_at DESC");
$stmt->execute();
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Manage Orders | Mero-Pasal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; background: #f8f9fa; }
 
        
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            padding-top: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        .sidebar a {
            color: #fff;
            padding: 15px 20px;
            display: block;
            transition: background 0.3s ease;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .sidebar .nav-link.active {
            background: #007bff;
        }
        .content {
            padding: 30px;
        }
.navbar { box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.table { background: #fff; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
.table thead { background: #343a40; color: #fff; }
.table img { max-width: 50px; border-radius: 5px; }
.btn { border-radius: 8px; }
.badge { font-size: 13px; padding: 6px 10px; }
 footer {
            background: #343a40;
            color: #fff;
            padding: 20px 0;
            margin-top: 40px;
        }
</style>
</head>
<body>
    

 <div class="d-flex">
        <div class="sidebar">
            <h4 class="text-white text-center mb-4">Admin Panel</h4>
            <a href="index.php" class="nav-link"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
            <a href="add_product.php" class="nav-link active"><i class="fas fa-plus-circle me-2"></i>Add Product</a>
            <a href="manage_products.php" class="nav-link"><i class="fas fa-boxes me-2"></i>Manage Products</a>
            <a href="add_category.php" class="nav-link"><i class="fas fa-tags me-2"></i>Add/Delete Categories</a>
                        <a href="admin_order.php" class="nav-link">
    <i class="fas fa-box me-2"></i>Order Management
</a>
            <a href="../logout.php" class="nav-link"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
        </div>

<div class="container mt-5">
  <h2 class="mb-4">Manage Orders</h2>

  <?php if(isset($_SESSION['message'])): ?>
    <div class="alert alert-success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
  <?php endif; ?>

  <?php if(empty($orders)): ?>
    <p class="text-muted">No orders found.</p>
  <?php else: ?>
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        
        <th>Product</th>
        <th>Customer</th>
        <th>address</th>
        <th>Quantity</th>
        <th>Total</th>
        <th>Payment</th>
        <th>Status</th>
        <th>Ordered At</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($orders as $order): ?>
      <tr>
        
        <td><?= htmlspecialchars($order['title']) ?></td>
        <td><?= htmlspecialchars($order['username']) ?></td>
        <td><?= htmlspecialchars($order['address']) ?></td>
        <td><?= $order['quantity'] ?></td>
        <td>₹<?= $order['total'] ?></td>
        <td><?= htmlspecialchars($order['payment']) ?></td>
        <td>
          <?php if($order['status'] == 'Placed'): ?>
            <span class="badge bg-warning">Placed</span>
          <?php elseif($order['status'] == 'Ordered'): ?>
            <span class="badge bg-primary">Ordered</span>
          <?php elseif($order['status'] == 'Delivered'): ?>
            <span class="badge bg-success">Delivered</span>
          <?php else: ?>
            <span class="badge bg-secondary"><?= htmlspecialchars($order['status']) ?></span>
          <?php endif; ?>
        </td>
        <td><?= $order['created_at'] ?></td>
        <td>
          <form method="POST" class="d-flex">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <select name="status" class="form-select form-select-sm me-2">
              <option value="Placed" <?= $order['status']=='Placed'?'selected':'' ?>>Placed</option>
              <option value="Ordered" <?= $order['status']=='Ordered'?'selected':'' ?>>Ordered</option>
              <option value="Delivered" <?= $order['status']=='Delivered'?'selected':'' ?>>Delivered</option>
            </select>
            <button type="submit" name="update_status" class="btn btn-sm btn-success">
              <i class="fas fa-sync"></i>
            </button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

 

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
