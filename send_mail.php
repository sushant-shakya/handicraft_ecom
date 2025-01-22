<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer

session_start(); // Start session
require 'dbConnectionWithPDO.php'; // Include your database connection

ob_start(); // Prevent "Headers already sent" error

// Ensure script runs only in a web request, not CLI
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['email']) || empty($_POST['email'])) {
        $_SESSION['error'] = "Email is required.";
        header("Location: forgot_password.php");
        exit();
    }

    $email = trim($_POST['email']); // Get email from POST request

    // Check if email exists in the database
    $stmt = $pdo->prepare("SELECT UserID FROM `User` WHERE Email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $token = bin2hex(random_bytes(50)); // Generate secure token
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token valid for 1 hour

        // Store token in database
        $stmt = $pdo->prepare("UPDATE `User` SET ResetToken = :token, TokenExpiry = :expiry WHERE Email = :email");
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->bindParam(':expiry', $expiry, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        // Send reset link using PHPMailer
        $mail = new PHPMailer(true);
        try {
             // Enable debugging
    $mail->SMTPDebug = 2; // Set to 2 for detailed debugging, 1 for basic
    $mail->Debugoutput = 'html'; // Output in HTML format
            // SMTP Configuration for Mailtrap
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io'; // Mailtrap SMTP
            $mail->SMTPAuth = true;
            $mail->Username = '2723bbc767c220'; // Replace with Mailtrap username
            $mail->Password = 'f59395cb25734c'; // Replace with Mailtrap password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 2525;

            // Email Settings
            $mail->setFrom('artisanheritage@gmail.com', 'Artisan Heritage');
            $mail->addAddress($email); // User email

            // Password Reset Link
            $reset_link = "http://localhost/php/final-project/reset_password.php?token=" . $token;
            $mail->Subject = "Password Reset Request - Artisan Heritage";
            $mail->Body = "Click the following link to reset your password: " . $reset_link;

            // Send Email
            if ($mail->send()) {
                $_SESSION['success'] = "A password reset link has been sent to your email.";
            } else {
                $_SESSION['error'] = "Failed to send email. Try again later.";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $_SESSION['error'] = "No account found with this email.";
    }
    
    // Redirect to forgot password page
    header("Location: forgot_password.php");
    exit();
} else {
    echo "This script should be accessed via a POST request from a web form.";
}

ob_end_flush(); // Flush output buffer

?>
