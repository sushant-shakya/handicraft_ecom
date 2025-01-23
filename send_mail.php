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
        $otp = rand(100000, 999999); // Generate a 6-digit OTP
        $expiry = date("Y-m-d H:i:s", strtotime("+1 minute")); // OTP valid for 1 minute

        // Store OTP in database
        $stmt = $pdo->prepare("UPDATE `User` SET OTP = :otp, OTPExpiry = :expiry WHERE Email = :email");
        $stmt->bindParam(':otp', $otp, PDO::PARAM_INT);
        $stmt->bindParam(':expiry', $expiry, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        if (!$stmt->execute()) {
            $_SESSION['error'] = "Failed to store OTP in the database. Try again later.";
            header("Location: forgot_password.php");
            exit();
        }

       
        try {
            // Enable debugging
            $mail->SMTPDebug = 2; // Set to 2 for detailed debugging, 1 for basic
            $mail->Debugoutput = 'html'; // Output in HTML format
            // Send OTP via PHPMailer
            $mail = new PHPMailer(true);
            // SMTP Configuration for Mailtrap
$phpmailer->isSMTP();
$phpmailer->Host = 'sandbox.smtp.mailtrap.io';
$phpmailer->SMTPAuth = true;
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use STARTTLS encryption
$phpmailer->Port = 587;
$phpmailer->Username = 'cacf847240fcbe';
$phpmailer->Password = '49eaa486b25eab';

            // Email Settings
            $mail->setFrom('artisianheritage@gmail.com', 'Artisan Heritage');
            $mail->addAddress($email); // User email
            $mail->Subject = "Your OTP for Password Reset - Artisan Heritage";
            $mail->Body = "Your OTP for password reset is: <b>$otp</b>. This OTP is valid for 1 minute. Please do not share it with anyone.";

            try {
                // Send the email
                $mail->send();
                $_SESSION['success'] = "An OTP has been sent to your email.";
                header("Location: verify_otp.php");
                exit();
            } catch (Exception $e) {
                $_SESSION['error'] = "Mailer Error: " . $mail->ErrorInfo;
                header("Location: forgot_password.php");
                exit();
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            header("Location: forgot_password.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "No account found with this email.";
        header("Location: forgot_password.php");
        exit();
    }
} else {
    echo "This script should be accessed via a POST request from a web form.";
}

ob_end_flush(); // Flush output buffer
?>
