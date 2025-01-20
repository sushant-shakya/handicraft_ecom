<?php
session_start();
require 'dbConnectionWithPDO.php';  // Assuming you have this file for DB connection

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
            header("Location: registerForm.php");
            exit;
        }

        // Validate password complexity
        if (!preg_match('/^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{6,15}$)/', $password)) {
            $_SESSION['error'] = "Password must be 6-15 characters long, include at least one number and one special character.";
            header("Location: registerForm.php");
            exit;
        }

        // Check if passwords match
        if ($password !== $confirm_password) {
            $_SESSION['error'] = "Passwords do not match.";
            header("Location: registerForm.php");
            exit;
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL to insert data into users table
        $stmt = $pdo->prepare("INSERT INTO `User` (username, email, password, created_at) 
                               VALUES (:username, :email, :password, NOW())");

        // Bind the parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        // Execute the query and check if it's successful
        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful! Please log in.";
            header("Location: login.html");  // Redirect to login page
            exit;
        } else {
            $_SESSION['error'] = "Registration failed. Please try again.";
            header("Location: registerForm.php");
            exit;
        }
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: registerForm.php");
    exit;
} finally {
    $pdo = null;
}
