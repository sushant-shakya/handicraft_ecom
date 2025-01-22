<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Artisan Heritage</title>
    <link rel ="icon" href="logo.png" type="image/x-icon">
    <link rel="stylesheet" href="login1.css">
    <style>
        .message {
            text-align: center;
            font-size: 16px;
            padding: 10px;
            border-radius: 5px;
            width: 100%;
            margin-bottom: 15px;
        }
        .message-error {
            color: red;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="image-section">
        <img src="loginpic.png" alt="Artisan Heritage Image">
    </div>
    <div class="form-section">

        <!-- Display error message if login fails -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="message message-error">
                <?= htmlspecialchars($_SESSION['error']); ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <h1>Hello! Welcome back</h1>
        <p>Please login to your account</p>
        
        <form action="login-process.php" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Email address" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <a href="forgot.php">Forgot password?</a>
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
