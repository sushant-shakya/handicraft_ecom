<?php
session_start();
// At the top of login.php (before any output)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


// Store redirect URL safely
if (isset($_GET['redirect'])) {
    $_SESSION['redirect_url'] = filter_var($_GET['redirect'], FILTER_SANITIZE_URL);
}

// Display error messages if they exist
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']); // Clear error after displaying
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Artisan Heritage</title>
    <link rel="icon" href="../assets/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/login1.css">
    <style>
        .message-error {
            color: red;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="image-section">
            <img src="../assets/loginpic.png" alt="Artisan Heritage Image">
        </div>
        <div class="form-section">
            <?php if (!empty($error)): ?>
                <div class="message-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="../src/login-process.php" method="POST">
                <input type="hidden" name="redirect_url" 
                       value="<?= isset($_SESSION['redirect_url']) ? htmlspecialchars($_SESSION['redirect_url']) : '' ?>">
                       <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" 
                           placeholder="Email address" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" 
                           placeholder="Password" required>
                    <!-- <a href="forgot_password.php">Forgot password?</a> -->
                </div>
                <button type="submit">Sign In</button>
            </form>

            <div class="register">
                Don’t have an account? <a href="registerForm.php">Register here</a>
            </div>
        </div>
    </div>
</body>
</html>