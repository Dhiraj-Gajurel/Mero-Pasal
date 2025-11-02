<!-- <?php
require_once 'config.php';

// Fetch a few sample products to show on landing page
$stmt = $pdo->prepare("SELECT * FROM products LIMIT 3");
$stmt->execute();
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Mero-Pasal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #123a63ff;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
        }
        .hero111 {
            background: url('assets/hero-bg.jpg') center center/cover no-repeat;
            color: #fff;
            padding: 40px 0;
            text-align: center;
        }
        .hero111 h1 {
            font-size: 3rem;
            font-weight: bold;
            color:black;
        }
        .hero111 p {
            font-size: 1.2rem;
            color:black;
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
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .btn-primary, .btn-outline-light {
            border-radius: 10px;
            padding: 10px 20px;
        }
        footer {
            background: #343a40;
            color: #fff;
            padding: 20px 0;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">Mero-Pasal</a>
        <div class="ms-auto">
            <a href="login.php" class="btn btn-outline-light me-2">Login</a>
            <a href="signup.php" class="btn btn-primary">Sign Up</a>
        </div>
    </div>
</nav>

<section class="hero111">
    <div class="container">
        <h1>Welcome to Mero-Pasal</h1>
        <p>Your one-stop online shop for the best deals in Nepal</p>
        <a href="signup.php" class="btn btn-outline-light mt-3">Start Shopping</a>
    </div>
</section>

<div class="container mt-5">
    <h2 class="text-center mb-4">Featured Products</h2>
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card product-card">
                    <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top product-image" alt="<?= htmlspecialchars($product['title']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['title']) ?></h5>
                        <p class="card-text text-muted">Price: Rs<?= $product['price'] ?><br>Offer: Rs<?= $product['offer'] ?></p>
                        <a href="login.php" class="btn btn-success w-100"><i class="fas fa-sign-in-alt me-2"></i>Login to Buy</a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> -->
<?php
require_once 'config.php';

// Fetch featured products (you can modify this to add WHERE conditions like is_featured=1 if needed)
$stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC LIMIT 6");
$stmt->execute();
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Mero-Pasal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
    .mero-pasal-top-banner {
      background-color: #e63946;
      color: white;
      text-align: center;
      padding: 20px 0;
      font-weight: 500;
      font-size: 14px;
    }
    
    .mero-pasal-navbar-brand {
      font-weight: bold;
      font-size: 1.5rem;
    }
    .mero-pasal-hero {
      background: url('assets/hero-bg.jpg') no-repeat center center/cover;
      
      padding: 150px 0;
      text-align: center;
      position: relative;
    }
    .mero-pasal-hero h1 {
      font-size: 3rem;
      font-weight: bold;
      text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
    }
    .mero-pasal-hero p {
      font-size: 1.2rem;
    }
    .mero-pasal-product-card {
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
      overflow: hidden;
      transition: transform 0.3s ease;
    }
    .mero-pasal-product-card:hover {
      transform: translateY(-5px);
    }
    .mero-pasal-product-image {
           
    height: 250px;                /* you can adjust the height */
    width: 100%;
    object-fit: contain;          /* ✅ shows full image without cropping */
    background: #fff;             /* optional: adds white background if image doesn’t fill space */
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    padding: 10px;                /* gives some space around image */
}
    .mero-pasal-btn-login {
      background-color: #2a9d8f;
      color: white;
      border: none;
    }
    .mero-pasal-btn-login:hover {
      background-color: #21867a;
    }
    .mero-pasal-footer {
      background-color: #f8f9fa;
      padding: 20px 0;
      text-align: center;
      font-size: 14px;
      color: #555;
    }
  </style>
</head>
<body>

<!-- Top Banner -->
<!-- <div class="mero-pasal-top-banner">
  Save 20% extra at checkout on marked-down items + Free shipping on orders over Rs7500
</div> -->

<!-- Navbar -->
<nav class="navbar navbar-expand-lg" style="background-color: #343a40;">
  <div class="container">
    <a class="navbar-brand text-white" href="#">Mero-Pasal</a>
    <div class="ms-auto">
      <a href="login.php" class="btn btn-outline-light me-2">Login</a>
      <a href="signup.php" class="btn btn-primary">Sign Up</a>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<div class="mero-pasal-hero">
  <div class="container">
    <h1>SUMMER SALE</h1>
    <p>Now Through 8/4 Enjoy an extra 20% off on any sale items, automatically applied at checkout!</p>
    <a href="products.php" class="btn btn-danger">SHOP NOW</a>
  </div>
</div>

<!-- Featured Products -->
<div class="container my-5">
  <h2 class="text-center mb-4">Featured Products</h2>
  <div class="row">
    <?php foreach ($products as $product): ?>
      <div class="col-md-4 mb-4">
        <div class="mero-pasal-product-card">
          <img src="<?= htmlspecialchars($product['image']) ?>" class="mero-pasal-product-image" alt="<?= htmlspecialchars($product['title']) ?>">
          <div class="p-3 text-center">
            <h5 class="title-mero-pasal"><?= htmlspecialchars($product['title']) ?></h5>
            <p>Price: Rs<?= $product['price'] ?><br><strong>Offer: Rs<?= $product['offer'] ?></strong></p>
            <a href="login.php" class="btn mero-pasal-btn-login w-100">
              <i class="fas fa-sign-in-alt me-2"></i>Login to Buy
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Footer -->
<footer class="mero-pasal-footer">
  &copy; 2025 Mero-Pasal. All Rights Reserved.
</footer>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="./assets/matchHeight.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  $(window).on('load', function() {
   $('h5.title-mero-pasal').matchHeight();
});

</script>
</body>
</html>
