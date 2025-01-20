<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration-Artisan Heritage</title>
        <link rel =" icon" href="logo.png" type="image/x-icon">
        <link rel="stylesheet" href="regis1.css">
        <style>
            .message {
                text-align: center;
                margin: 20px auto;
                width: fit-content;
                font-size: 16px;
                padding: 10px 20px;
                border-radius: 5px;
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
     <!-- Success and Error Messages -->
     <?php
     if (isset($_SESSION['success'])) {
         echo '<div class="message message-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
         unset($_SESSION['success']);
     }
     if (isset($_SESSION['error'])) {
         echo '<div class="message message-error">' . htmlspecialchars($_SESSION['error']) . '</div>';
         unset($_SESSION['error']);
     }
     ?>
 
    <div class="container">
        <div class="image-section">
            <img src="regispic.png" alt="Artisan Heritage Image">
        </div>
        <div class="form-section">
            <h1>Registration</h1>
            <form action="registerdata.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="username" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Email address" required>
                </div>
                <div class="form-group password-toggle">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required >
                    <span onclick="togglePassword('password')">👁️</span>
                </div>
                <div class="form-group password-toggle">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm Password" required>
                    <span onclick="togglePassword('confirm-password')">👁️</span>
                </div>
                <button type="submit">Register</button>
            </form>
            <div class="back-link">
                Already have an account? <a href="login.html">Log in</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const fieldType = field.getAttribute('type') === 'password' ? 'text' : 'password';
            field.setAttribute('type', fieldType);
        }
    </script>
</body>
</html>
