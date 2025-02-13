<?php
session_start();
require 'dbConnectionWithPDO.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Check if email exists in database
    $stmt = $pdo->prepare("SELECT UserID FROM `User` WHERE Email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $otp = rand(100000, 999999); // Generate a 6-digit OTP
        $expiry = date("Y-m-d H:i:s", strtotime("+1 minute")); // OTP valid for 1 minute

        // Store OTP and expiry in the database
        $stmt = $pdo->prepare("UPDATE `User` SET OTP = :otp, OTPExpiry = :expiry WHERE Email = :email");
        $stmt->bindParam(':otp', $otp, PDO::PARAM_STR);
        $stmt->bindParam(':expiry', $expiry, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        // Send OTP via email
        $subject = "Your OTP for Password Reset - Artisan Heritage";
        $message = "Your OTP to reset your password is: <strong>$otp</strong>. This OTP is valid for 1 minute.";
        $headers = "From: artisianheritage@gmail.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        if (mail($email, $subject, $message, $headers)) {
            $_SESSION['success'] = "An OTP has been sent to your email. It is valid for 1 minute.";
            $_SESSION['email'] = $email; // Store email for OTP verification
            header("Location: verify_otp.php"); // Redirect to OTP verification page
            exit();
        } else {
            $_SESSION['error'] = "Failed to send OTP. Try again later. ";
        }
    } else {
        $_SESSION['error'] = "No account found with this email.";
    }

    header("Location: forgot_password.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Artisan Heritage</title>
    <link rel="stylesheet" href="forgot1.css">
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
            <img src="forgotpic.png" alt="Artisan Heritage Image">
        </div>
        <div class="form-section">
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

            <h1>Forgot Password?</h1>
            <p>Don’t worry, it happens. Please enter the email address associated with your account.</p>
            <form action="forgot_password.php" method="POST">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <button type="submit">Send OTP</button>
            </form>
            <div class="back-link">
                Back to <a href="login.php">Login</a>
            </div>
        </div>
    </div>
</body>
</html>
