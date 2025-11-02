<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] == 'admin') {
    header("Location: login.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// ✅ define before using
$selected_category = isset($_GET['category_id']) ? $_GET['category_id'] : null;

$products_query = $selected_category ? 
    "SELECT p.*, IFNULL(AVG(r.rating), 0) as avg_rating 
     FROM products p
     LEFT JOIN product_ratings r ON p.id = r.product_id
     WHERE p.category_id = ?
     GROUP BY p.id
     ORDER BY avg_rating DESC" 
    : 
    "SELECT p.*, IFNULL(AVG(r.rating), 0) as avg_rating 
     FROM products p
     LEFT JOIN product_ratings r ON p.id = r.product_id
     GROUP BY p.id
     ORDER BY avg_rating DESC";

$stmt = $pdo->prepare($products_query);

if ($selected_category) {
    $stmt->execute([$selected_category]);
} else {
    $stmt->execute();
}
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Mero-Pasal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
        }
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-weight: 600;
            color: #fff !important;
        }
        .nav-link {
            color: #fff !important;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #007bff !important;
        }
        .product-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .product-image {
    height: 250px;                /* you can adjust the height */
    width: 100%;
    object-fit: contain;          /* ✅ shows full image without cropping */
    background: #fff;             /* optional: adds white background if image doesn’t fill space */
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    padding: 10px;                /* gives some space around image */
}
        .btn-primary, .btn-success {
            border-radius: 10px;
            padding: 8px 15px;
            transition: background 0.3s ease;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .btn-success:hover {
            background: #218838;
        }
        .form-select {
            border-radius: 10px;
            border: 1px solid #ced4da;
        }
        footer {
            background: #343a40;
            color: #fff;
            padding: 20px 0;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Mero-Pasal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="view_cart.php"><i class="fas fa-shopping-cart me-2"></i>View Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">

        <!-- ✅ Success Message -->
        <?php if (isset($_SESSION['message'])): ?>
    <div id="flash-message" class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['message']); ?>
    
    <script>
        // Auto dismiss after 3 seconds (3000ms)
        setTimeout(function() {
            let alertBox = document.getElementById('flash-message');
            if (alertBox) {
                alertBox.classList.remove('show'); // fades out
                alertBox.classList.add('fade');
            }
        }, 3000);
    </script>
<?php endif; ?>

        <h2 class="mb-4">Our Products</h2>
        <div class="mb-4">
            <label for="category" class="form-label"><i class="fas fa-filter me-2"></i>Filter by Category</label>
            <select onchange="location = this.value;" class="form-select w-25" id="category">
                <option value="products.php">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="products.php?category_id=<?= $category['id'] ?>" <?= $selected_category == $category['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card product-card">
                        <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top product-image" alt="<?= htmlspecialchars($product['title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title title-mero-pasal"><?= htmlspecialchars($product['title']) ?></h5>
                            <p class="card-text text-muted">Price: Rs <?= $product['price'] ?> <br>Offer: Rs <?= $product['offer'] ?></p>
                            <div class="d-flex justify-content-between">
                                <a href="product_details.php?id=<?= $product['id'] ?>" class="btn btn-primary"><i class="fas fa-eye me-2"></i>View Details</a>
                                <a href="cart.php?action=add&product_id=<?= $product['id'] ?>" class="btn btn-success"><i class="fas fa-cart-plus me-2"></i>Add to Cart</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <footer class="text-center">
        <div class="container">
            <p>&copy; 2025 Mero-Pasal. All Rights Reserved.</p>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="./assets/matchHeight.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  $(window).on('load', function() {
   $('h5.card-title.title-mero-pasal').matchHeight();
//    $('.product-card').matchHeight();
});
</script>
</body>
</html>
