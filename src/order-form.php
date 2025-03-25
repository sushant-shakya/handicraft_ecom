<?php
session_start();
require __DIR__ . '/../database/dbConnectionWithPDO.php';

try {
     // Ensure the connection is established
     if (!$pdo) {
        throw new Exception("Database connection is not established.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Store submitted data in session for form repopulation
        $_SESSION['form_data'] = $_POST;

        // Collect and sanitize user input
        $full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
        $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
        $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
        $postal_code = filter_input(INPUT_POST, 'postal_code', FILTER_VALIDATE_INT);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_VALIDATE_INT);
        $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
        $payment_method = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING);
        $product_name = filter_input(INPUT_POST, 'product_name', FILTER_SANITIZE_STRING);

        // Validate name (required, characters only)
        $name_validation_error = false;
        if (empty($full_name)) {
            $_SESSION['error'] = "Full name is required.";
            $name_validation_error = true;
        } elseif (!preg_match("/^[a-zA-Z\s\-']+$/u", $full_name)) {
            $_SESSION['error'] = "Full name should contain only letters, spaces, and hyphens.";
            $name_validation_error = true;
        }

        // Validate phone number with regex
        $phone_regex = '/^(98|97|96)\d{8}$/';
        if (!preg_match($phone_regex, $phone)) {
            $_SESSION['error'] = "Invalid phone number. Must be 10 digits starting with 98, 97, or 96.";
            header("Location: ../templates/form.php?product_name=" . urlencode($product_name));
            exit;
        }
        
        // Additional validation to prevent negative values
        if ( $postal_code <= 0) {
            $_SESSION['error'] = " postal code must be positive values.";
            header("Location: ../templates/form.php?product_name=" . urlencode($product_name));
            exit;
        }
        if ($phone <= 0 ) {
            $_SESSION['error'] = "Phone number must be positive values.";
            header("Location: ../templates/form.php?product_name=" . urlencode($product_name));
            exit;
        }
        if ($quantity <= 0 ) {
            $_SESSION['error'] = "Quantity must be positive values.";
            header("Location: ../templates/form.php?product_name=" . urlencode($product_name));
            exit;
        }

        // Validate required fields
        if (empty($full_name) || empty($city) || empty($postal_code) || empty($address) || empty($phone) || empty($quantity) || empty($payment_method)) {
            $_SESSION['error'] = "All required fields must be filled.";
            header("Location: ../templates/form.php?product_name=" . urlencode($product_name));
            exit;
        }

        // If there's a name validation error, redirect back to the form
        if ($name_validation_error) {
            header("Location: ../templates/form.php?product_name=" . urlencode($product_name));
            exit;
        }

        // Check if user is logged in and has a valid UserID
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "User not logged in.";
            header("Location: ../templates/login.php");
            exit;
        }

        // Insert into orders table
        $stmt = $pdo->prepare("INSERT INTO `Order` (UserID, FullName, Country, City, PostalCode, Address, Phone, Quantity, PaymentMethod, ProductName) 
         VALUES (:user_id, :full_name, :country, :city, :postal_code, :address, :phone, :quantity, :payment_method, :product_name)");
 
        $stmt->bindParam(':user_id', $_SESSION['user_id']);    
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':postal_code', $postal_code);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':payment_method', $payment_method);
        $stmt->bindParam(':product_name', $product_name);

        if ($stmt->execute()) {
           
            $_SESSION['success'] = "Order placed successfully!";
             // Clear form data on successful submission
             unset($_SESSION['form_data']);
            header("Location: ../templates/form.php?product_name=" . urlencode($product_name));
            exit;
        } else {
            $_SESSION['error'] = "Failed to place order. Please try again.";
            header("Location: ../templates/form.php?product_name=" . urlencode($product_name));
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
} finally {
    $pdo = null;
}
?>