<?php
session_start();
require 'dbConnectionWithPDO.php';

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);
    
        // Fetch user data
        $query = "SELECT * FROM `User` WHERE Email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user) {
            if (password_verify($password, $user['Password'])) { 
                session_regenerate_id(true); // Prevent session fixation attacks
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['UserID']; // Store UserID in session
                $_SESSION['username'] = $user['UserName']; // Match database column case

                // Check if there's a redirect URL stored, else default to landing page
                $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'landingpg.php';
                unset($_SESSION['redirect_url']); // Clear session variable after use

                // Redirect to the intended page or default landing page
                header("Location: $redirect_url");
                exit();
            } else {
                $_SESSION['error'] = "Incorrect password";
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Email not found";
            header("Location: login.php");
            exit();
        }
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    header("Location: login.php");
    exit();
}
