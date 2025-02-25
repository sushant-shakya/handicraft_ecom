<?php
session_start();
require __DIR__ . '/../database/dbConnectionWithPDO.php';

try {
     // Ensure the connection is established
     if (!$pdo) {
        throw new Exception("Database connection is not established.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Collect and sanitize user input
        $full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
        $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
        $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
        $postal_code = filter_input(INPUT_POST, 'postal_code', FILTER_SANITIZE_NUMBER_INT);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_NUMBER_INT);
        $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
        $payment_method = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING);
        $product_name = filter_input(INPUT_POST, 'product_name', FILTER_SANITIZE_STRING);

        // Validate required fields
        if (empty($full_name) || empty($city) || empty($postal_code) || empty($address) || empty($phone) || empty($quantity) || empty($payment_method) || empty($product_name)) {
            $_SESSION['error'] = "All required fields must be filled.";
            header("Location: ../templates/form.hp?product_name=" . urlencode($product_name));
            exit;
        }

        // Check if user is logged in and has a valid UserID
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "User not logged in.";
            header("Location: ../templates/login.php");
            exit;
        }

        // Insert into orders table
        $stmt = $pdo->prepare("INSERT INTO `Order` (UserID, FullName, Country, City, PostalCode, Address, Phone,Quantity, PaymentMethod, ProductName) 
         VALUES (:user_id,:full_name, :country, :city, :postal_code, :address, :phone, :quantity, :payment_method, :product_name)");
 
        $stmt->bindParam(':user_id', $_SESSION['user_id']); // Add this line    
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':postal_code', $postal_code);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':quantity',$quantity);
        $stmt->bindParam(':payment_method', $payment_method);
        $stmt->bindParam(':product_name', $product_name);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Order placed successfully!";
            header("Location: ../templates/form.php?product_name=" . urlencode($product_name));
          
            exit;
        } else {
            $_SESSION['error'] = "Failed to place order. Please try again.";
            header("Location:../templates/form.php?product_name=" . urlencode($product_name));
            exit;
        }
    } else {
        $_SESSION['error'] = "Invalid request method.";
        header("Location: ../templates/form.php");
        exit;
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: ../templates/form.php");
    exit;
}finally{
    $pdo = null;
}
?>
