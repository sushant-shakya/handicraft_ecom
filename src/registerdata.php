<?php
session_start();
require __DIR__ . '/../database/dbConnectionWithPDO.php';  // Assuming you have this file for DB connection

try {
    // Ensure the connection is established
    if (!$pdo) {
        throw new Exception("Database connection is not established.");
    }

    // Validate form data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);

        // Validate required fields
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            $_SESSION['error'] = "All fields are required.";
            header("Location: ../templates/registerForm.php");
            exit;
        }

        // Validate password complexity
        if (!preg_match('/^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{6,15}$)/', $password)) {
            $_SESSION['error'] = "Password must be 6-15 characters long, include at least one number and one special character.";
            header("Location: ../templates/registerForm.php");
            exit;
        }

        // Check if passwords match
        if ($password !== $confirm_password) {
            $_SESSION['error'] = "Passwords do not match.";
            header("Location: ../templates/registerForm.php");
            exit;
        }

        // Check if user or admin already exists
        $stmt = $pdo->prepare("SELECT * FROM `User` WHERE UserName = :username OR Email = :email");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            $_SESSION['error'] = "Email already exists. Please log in or use a different email/username.";
            header("Location: ../templates/registerForm.php");
            exit;
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("INSERT INTO `User` (UserName, Email, Password,  Created_at) 
        VALUES (:username, :email, :password,  NOW())");

$otp = null; // Or generate an OTP if required

$stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
$stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
$stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
// $stmt->bindParam(':otp', $otp, PDO::PARAM_STR); // Bind OTP value

if ($stmt->execute()) {
$_SESSION['success'] = "Registration successful! Please log in.";
} else {
$_SESSION['error'] = "Registration failed. Please try again.";
}


        header("Location: ../templates/registerForm.php");
        exit;
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: ../templates/registerForm.php");
    exit;
} finally {
    $pdo = null;
}
