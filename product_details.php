<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] == 'admin') {
    header("Location: login.php");
    exit;
}

$product_id = $_GET['id'] ?? 0;

// ✅ Fetch product
$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name 
                       FROM products p 
                       JOIN categories c ON p.category_id = c.id 
                       WHERE p.id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: products.php");
    exit;
}

// ✅ Fetch related products
$related_stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND id != ? LIMIT 3");
$related_stmt->execute([$product['category_id'], $product_id]);
$related_products = $related_stmt->fetchAll();

// ✅ Fetch ratings
$rating_stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews 
                              FROM product_ratings WHERE product_id = ?");
$rating_stmt->execute([$product_id]);
$rating_data = $rating_stmt->fetch();

// ✅ Fetch current user rating if exists
$user_rating = null;
if (isset($_SESSION['id'])) {
    $user_stmt = $pdo->prepare("SELECT rating FROM product_ratings WHERE product_id = ? AND user_id = ?");
    $user_stmt->execute([$product_id, $_SESSION['id']]);
    $user_rating = $user_stmt->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - Mero-Pasal</title>
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
        .navbar-brand, .nav-link {
            color: #fff !important;
        }
        .nav-link:hover {
            color: #007bff !important;
        }
        .product-image {
            max-height: 400px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .product-details {
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .btn-success {
            border-radius: 10px;
            padding: 10px 20px;
            transition: background 0.3s ease;
        }
        .btn-success:hover {
            background: #218838;
        }
        .related-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .related-card:hover {
            transform: translateY(-3px);
        }
        .related-image {
                 
    height: 250px;                /* you can adjust the height */
    width: 100%;
    object-fit: contain;          /* ✅ shows full image without cropping */
    background: #fff;             /* optional: adds white background if image doesn’t fill space */
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    padding: 10px;                /* gives some space around image */
}
        
        footer {
            background: #343a40;
            color: #fff;
            padding: 20px 0;
            margin-top: 40px;
        }
        /* ⭐ Star Rating */
        .star-rating {
          direction: rtl;
          display: inline-flex;
        }
        .star-rating input {
          display: none;
        }
        .star-rating label {
          font-size: 24px;
          cursor: pointer;
          color: #ccc;
          transition: color 0.3s;
        }
        .star-rating input:checked ~ label {
          color: #ffc107;
        }
        .star-rating label:hover,
        .star-rating label:hover ~ label {
          color: #ffdb58;
        }
        .product-rating {
          background: #fdfdfd;
          padding: 15px;
          margin-top: 20px;
          border-radius: 10px;
          box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- ✅ Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Mero-Pasal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="products.php"><i class="fas fa-arrow-left me-2"></i>Back</a></li>
                    <li class="nav-item"><a class="nav-link" href="view_cart.php"><i class="fas fa-shopping-cart me-2"></i>Cart</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ✅ Product Details -->
    <div class="container mt-5">
        <h2 class="mb-4">Product Details</h2>
        <div class="row">
            <div class="col-md-6 mb-4">
                <img src="<?= htmlspecialchars($product['image']) ?>" class="img-fluid product-image" alt="<?= htmlspecialchars($product['title']) ?>">
            </div>
            <div class="col-md-6">
                <div class="product-details">
                    <h3><?= htmlspecialchars($product['title']) ?></h3>
                    <p class="text-muted"><i class="fas fa-tag me-2"></i><strong>Category:</strong> <?= htmlspecialchars($product['category_name']) ?></p>
                    <p><i class="fas fa-info-circle me-2"></i><strong>Description:</strong> <?= htmlspecialchars($product['description']) ?></p>
                    <p><strong>Price:</strong> Rs <?= $product['price'] ?></p>
                    <p><i class="fas fa-tags me-2"></i><strong>Offer:</strong> RS <?= $product['offer'] ?></p>
                    <a href="cart.php?action=add&product_id=<?= $product['id'] ?>" class="btn btn-success"><i class="fas fa-cart-plus me-2"></i>Add to Cart</a>

                    <!-- ⭐ Rating Section -->
                    <div class="product-rating">
                        <h5>Product Rating</h5>
                        <?php if ($rating_data['total_reviews'] > 0): ?>
                            <p>
                                Average: 
                                <?php
                                $avg = round($rating_data['avg_rating']);
                                for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $avg ? "⭐" : "☆";
                                }
                                ?>
                                (<?= $rating_data['total_reviews'] ?> ratings)
                            </p>
                        <?php else: ?>
                            <p>No ratings yet.</p>
                        <?php endif; ?>

                        <!-- ⭐ Rating Form -->
                        <form action="rate_product.php" method="POST">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <div class="star-rating">
                                <input type="radio" id="star5" name="rating" value="5" <?= $user_rating == 5 ? 'checked' : '' ?>><label for="star5">⭐</label>
                                <input type="radio" id="star4" name="rating" value="4" <?= $user_rating == 4 ? 'checked' : '' ?>><label for="star4">⭐</label>
                                <input type="radio" id="star3" name="rating" value="3" <?= $user_rating == 3 ? 'checked' : '' ?>><label for="star3">⭐</label>
                                <input type="radio" id="star2" name="rating" value="2" <?= $user_rating == 2 ? 'checked' : '' ?>><label for="star2">⭐</label>
                                <input type="radio" id="star1" name="rating" value="1" <?= $user_rating == 1 ? 'checked' : '' ?>><label for="star1">⭐</label>
                            </div>
                            <button type="submit" class="btn btn-warning btn-sm mt-2">Submit Rating</button>
                        </form>
                        <?php if ($user_rating): ?>
                            <p class="mt-2 text-success">You rated: <?= str_repeat("⭐", $user_rating) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ✅ Related Products -->
        <?php if (!empty($related_products)): ?>
            <h3 class="mt-5 mb-4">Related Products</h3>
            <div class="row">
                <?php foreach ($related_products as $related): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card related-card">
                            <img src="<?= htmlspecialchars($related['image']) ?>" class="card-img-top related-image" alt="<?= htmlspecialchars($related['title']) ?>">
                            <div class="card-body">
                                <h6 class="card-title"><?= htmlspecialchars($related['title']) ?></h6>
                                <p class="card-text text-muted">Offer: Rs <?= $related['offer'] ?></p>
                                <a href="product_details.php?id=<?= $related['id'] ?>" class="btn btn-primary btn-sm"><i class="fas fa-eye me-2"></i>View</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- ✅ Footer -->
    <footer class="text-center">
        <div class="container">
            <p>&copy; 2025 Mero-Pasal. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
