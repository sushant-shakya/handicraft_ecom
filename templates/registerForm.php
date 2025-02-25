<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration - Artisan Heritage</title>
    <link rel="icon" href="../logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../regis1.css">
    <style>
        .message {
            font-size: 16px;
            padding: 10px;
            margin-bottom: 15px; /* Ensure spacing below message */
            border-radius: 5px;
            text-align: center;
            width: 100%;
        }
        .message-success {
            color: green;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
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
            <img src="../regispic.png" alt="Artisan Heritage Image">
        </div>

        <div class="form-section">
            <!-- Display Messages Just Above the Form -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="message message-success">
                    <?= htmlspecialchars($_SESSION['success']); ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="message message-error">
                    <?= htmlspecialchars($_SESSION['error']); ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <h1>Registration</h1>
            
            <form action="../src/registerdata.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Email address" required>
                </div>
                
                <div class="form-group password-toggle">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group password-toggle">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                </div>
                <button type="submit">Register</button>
            </form>
            <div class="back-link">
                Already have an account? <a href="./login.php">Log in</a>
            </div>
        </div>
    </div>

</body>
</html>
