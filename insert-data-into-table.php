<?php
session_start();
require 'dbConnectionWithPDO.php';

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
        $payment_method = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING);
        $product_name = filter_input(INPUT_POST, 'product_name', FILTER_SANITIZE_STRING);

        // Validate required fields
        if (empty($full_name) || empty($city) || empty($postal_code) || empty($address) || empty($phone) || empty($payment_method) || empty($product_name)) {
            $_SESSION['error'] = "All required fields must be filled.";
            header("Location: formp.hp?product_name=" . urlencode($product_name));
            exit;
        }

        // Insert into orders table
        $stmt = $pdo->prepare("INSERT INTO `Order` (FullName, Country, City, PostalCode, Address, Phone, PaymentMethod, ProductName) 
                               VALUES (:full_name, :country, :city, :postal_code, :address, :phone, :payment_method, :product_name)");

        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':postal_code', $postal_code);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':payment_method', $payment_method);
        $stmt->bindParam(':product_name', $product_name);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Order placed successfully!";
            header("Location: form.php?product_name=" . urlencode($product_name));
            exit;
        } else {
            $_SESSION['error'] = "Failed to place order. Please try again.";
            header("Location:form.php?product_name=" . urlencode($product_name));
            exit;
        }
    } else {
        $_SESSION['error'] = "Invalid request method.";
        header("Location: form.php");
        exit;
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: form.php");
    exit;
}finally{
    $pdo = null;
}
?>
