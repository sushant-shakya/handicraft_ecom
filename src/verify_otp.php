<?php
session_start();
require __DIR__ . '/../database/dbConnectionWithPDO.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['email']; // Get email from session
    $entered_otp = trim($_POST['otp']);

    // Check if OTP is valid
    $stmt = $pdo->prepare("SELECT OTP, OTPExpiry FROM `User` WHERE Email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['OTP'] == $entered_otp) {
        if (strtotime($user['OTPExpiry']) >= time()) {
            $_SESSION['success'] = "OTP verified successfully. You can reset your password.";
            header("Location: reset_password.php"); // Redirect to password reset page
            exit();
        } else {
            $_SESSION['error'] = "OTP expired. Please request a new one.";
        }
    } else {
        $_SESSION['error'] = "Invalid OTP.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Artisan Heritage</title>
    <link rel="stylesheet" href="../assets/forgot1.css">
</head>
<body>
    <div class="container">
        <div class="form-section">
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="message message-error">' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
        ?>

            <h1>Enter OTP</h1>
            <p>We have sent a 6-digit OTP to your email. Please enter it below.</p>
            <form action="verify_otp.php" method="POST">
                <div class="form-group">
                    <label for="otp">Enter OTP</label>
                    <input type="text" id="otp" name="otp" placeholder="Enter OTP" required>
                </div>
                <button type="submit">Verify OTP</button>
            </form>
            <div class="back-link">
                Back to <a href="../templates/login.php">Login</a>
            </div>
        </div>
    </div>
</body>
</html>
