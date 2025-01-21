 <?php
session_start();
require 'dbConnectionWithPDO.php';

try {
    if (!$pdo) {
        throw new Exception("Database connection is not established.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $stmt = $pdo->prepare("SELECT UserID, UserName, Password FROM `User` WHERE Email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Debugging output (remove after testing)
            error_log("Entered Password: " . $password);
            error_log("Stored Hashed Password: " . $user['Password']);

            if (password_verify($password, $user['Password'])) {
                $_SESSION['username'] = $user['UserName']; // Fix key case
                $_SESSION['logged_in'] = true;
                header("Location: landingpg.php");
                exit();
            } else {
                $_SESSION['error'] = "Invalid password. Please try again.";
            }
        } else {
            $_SESSION['error'] = "No account found with this email.";
        }

        header("Location: login.php");
        exit();
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: login.php");
    exit();
} finally {
    $pdo = null;
}
?> 