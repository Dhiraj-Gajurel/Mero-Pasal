<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $address = trim($_POST['address']);
    $contact = trim($_POST['contact']);

    if (empty($username) || empty($password) || empty($address) || empty($contact)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (!preg_match("/^(97|98)\d{8}$/", $contact)) {
        $error = "Contact must be 10 digits and start with 97 or 98!";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = "Username already taken!";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, address, contact, role) VALUES (?, ?, ?, ?, 'user')");
            if ($stmt->execute([$username, $hashedPassword, $address, $contact])) {
                header("Location: login.php?signup=success");
                exit;
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mero-Pasal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body.moropasal-body {
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }
        .moropasal-card {
            max-width: 450px;
            width: 100%;
            border: none;
            border-radius: 15px;
            background: #fff;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .moropasal-title {
            color: #28a745;
            font-weight: 600;
        }
        .moropasal-input {
            border-radius: 10px;
        }
        .moropasal-btn {
            border-radius: 10px;
            padding: 10px;
        }
        .moropasal-btn:hover {
            background-color: #218838;
        }
        .moropasal-error {
            font-size: 0.95rem;
        }
    </style>
</head>
<body class="moropasal-body">
<div class="moropasal-card">
    <h2 class="text-center mb-4 moropasal-title"><i class="fas fa-user-plus me-2"></i>Sign Up</h2>
    <?php if (isset($error)) echo "<p class='text-danger text-center'>$error</p>"; ?>
    <form method="POST">
    <div class="mb-3">
        <label for="username" class="form-label"><i class="fas fa-user me-2"></i>Username</label>
        <input type="text" class="form-control moropasal-input" id="username" name="username" required 
               >
    </div>
    <div class="mb-3">
        <label for="password" class="form-label"><i class="fas fa-lock me-2"></i>Password</label>
        <input type="password" class="form-control moropasal-input" id="password" name="password" required>
    </div>
    <div class="mb-3">
        <label for="confirm_password" class="form-label"><i class="fas fa-lock me-2"></i>Confirm Password</label>
        <input type="password" class="form-control moropasal-input" id="confirm_password" name="confirm_password" required>
    </div>
    <div class="mb-3">
        <label for="address" class="form-label"><i class="fas fa-home me-2"></i>Address</label>
        <input type="text" class="form-control moropasal-input" id="address" name="address" required 
               value="<?= isset($address) ? htmlspecialchars($address) : '' ?>">
    </div>
    <div class="mb-3">
        <label for="contact" class="form-label"><i class="fas fa-phone me-2"></i>Contact</label>
        <input type="text" class="form-control moropasal-input" id="contact" name="contact" required 
               pattern="^(97|98)\d{8}$" title="Must start with 97 or 98 and be 10 digits" 
               value="<?= isset($contact) ? htmlspecialchars($contact) : '' ?>">
    </div>
    <button type="submit" class="btn btn-success w-100 moropasal-btn">Register</button>
</form>

    <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a></p>
</div>
</body>
</html>
