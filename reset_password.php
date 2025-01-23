<?php
session_start();
require 'dbConnectionWithPDO.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate the token
    $stmt = $pdo->prepare("SELECT UserID, OTPExpiry FROM `User` WHERE OTP = :token");
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || strtotime($user['TokenExpiry']) < time()) {
        $_SESSION['error'] = "Invalid or expired token.";
        header("Location: forgot_password.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: forgot_password.php");
    exit();
}

// Process password reset
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update password in database
        $stmt = $pdo->prepare("UPDATE `User` SET Password = :password, ResetToken = NULL, TokenExpiry = NULL WHERE ResetToken = :token");
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        $_SESSION['success'] = "Your password has been reset. You can now log in.";
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Artisan Heritage</title>
    <link rel="stylesheet" href="forgot1.css">
    <style>
        .message-error {
            color: red;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-section">
        <?php if (isset($_SESSION['error'])): ?>
          <div class=" message-error">
                <?= htmlspecialchars($_SESSION['error']); ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
            <h1>Reset Password</h1>
            
            <form action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter new password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
                </div>
                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
