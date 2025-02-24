<?php
session_start();
require __DIR__ . '/../database/dbConnectionWithPDO.php';


try {
    // Verify CSRF token first
    if (!isset($_POST['csrf_token'])){
        throw new Exception('CSRF token missing');
    }
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        throw new Exception('CSRF token validation failed');
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate email format
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }

        $password = trim($_POST['password']);
        $email = strtolower($email); // Case-insensitive email handling

        // Fetch user data with role
        $query = "SELECT UserID, UserName, Password, Role FROM `User` WHERE LOWER(Email) = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Generic error message for security
        $errorMessage = "Invalid email or password";
        $loginDelay = 2; // Seconds to delay on failed attempt

        if ($user) {
            if (password_verify($password, $user['Password'])) {
                // Successful login
                session_regenerate_id(true); // Prevent session fixation
                
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['UserID'];
                $_SESSION['username'] = $user['UserName'];
                $_SESSION['user_role'] = $user['Role'] ?? 'user'; // Default to user if not set

                // Validate redirect URL
                $redirect_url = '../templates/landingpg.php';
                if (!empty($_SESSION['redirect_url'])) {
                    $parsed = parse_url($_SESSION['redirect_url']);
                    if ($parsed !== false && !isset($parsed['scheme'])) { // Allow only relative URLs
                        $redirect_url = $_SESSION['redirect_url'];
                    }
                    unset($_SESSION['redirect_url']);
                }

                header("Location: " . $redirect_url);
                exit();
            } else {
                sleep($loginDelay); // Slow down brute-force attempts
                throw new Exception($errorMessage);
            }
        } else {
            sleep($loginDelay); // Same delay whether user exists or not
            throw new Exception($errorMessage);
        }
    }
} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage()); // Server-side logging
    $_SESSION['error'] = $e->getMessage();
    header("Location: ../templates/login.php");
    exit();
}
?>