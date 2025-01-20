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
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $confirm_password = filter_input(INPUT_POST, 'confirm-password', FILTER_SANITIZE_STRING);

    // Validate required fields
    if (empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: register.php");
        exit;
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: register.php");
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    

        // Prepare SQL to insert data into users table
        $stmt = $pdo->prepare("INSERT INTO users (email, password, created_at) 
                               VALUES (:email, :password, NOW())");

        // Bind the parameters
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        // Execute the query and check if it's successful
        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful! Please log in.";
            header("Location: login.html");  // Redirect to login page
            exit;
        } else {
            $_SESSION['error'] = "Registration failed. Please try again.";
            header("Location: register.php");
            exit;
        }
      }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: register.php");
        exit;
    } finally {
        $pdo = null;
    }

?>
