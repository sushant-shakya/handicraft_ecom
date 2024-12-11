<?php
session_start(); // Start the session
require 'dbConnectionWithPDO.php'; 

try {
    // Ensure the connection is established
    if (!$pdo) {
        throw new Exception("Database connection is not established.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Initialize variables
        $PostalCode = $Phone = "";
        $errors = [];

        // Collect and sanitize form inputs
        $full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
        $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
        $postal_code = filter_input(INPUT_POST, 'postal_code', FILTER_SANITIZE_STRING);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
        $payment_method = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING);

        // Validate the fields
        if (!$full_name) $errors[] = "Full name is required.";
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
        if (!$country) $errors[] = "Country is required.";
        if (!$city) $errors[] = "City is required.";
        if (!$postal_code || !preg_match("/^[0-9]{5}$/", $postal_code)) {
            $errors[] = "Postal code must be exactly 5 digits.";
        }
        if (!$address) $errors[] = "Address is required.";
        if (!$phone || !preg_match("/^(98|97|96)[0-9]{8}$/", $phone)) {
            $errors[] = "Phone number must be in the format: (98|97|96)XXXXXXXX.";
        }
        if (!$payment_method) $errors[] = "Payment method is required.";

        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);//    implode => Converts the $errors array into a string
            header("Location: form.php");
            exit;
        }

        // Check if the user already exists
        $stmt = $pdo->prepare("SELECT * FROM `User` WHERE Email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $existing_user = $stmt->fetch();

        if ($existing_user) {
            $_SESSION['error'] = "User already exists.";
            header("Location: form.php");
            exit;
        }

        // Insert new user into database
        if ($payment_method === 'Cash on Delivery') {
            try {
                $stmt = $pdo->prepare("INSERT INTO `User` (FullName, Email, Country, City, PostalCode, Address, Phone) 
                       VALUES (:full_name, :email, :country, :city, :postal_code, :address, :phone)");
                $stmt->bindParam(':full_name', $full_name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':country', $country);
                $stmt->bindParam(':city', $city);
                $stmt->bindParam(':postal_code', $postal_code);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':phone', $phone);


                if ($stmt->execute()) {
                    $_SESSION['success'] = "Form submitted successfully!";
                    header("Location: form.php");
                    exit;
                } else {
                    $_SESSION['error'] = "Failed to submit the form. Please try again.";
                    header("Location: form.php");
                    exit;
                }
            } catch (PDOException $e) {
                $_SESSION['error'] = "Error inserting data: " . $e->getMessage();
                header("Location: form.php");
                exit;
            }
        }
    } else {
        $_SESSION['error'] = "Invalid request. Please submit the form correctly.";
        header("Location: form.php");
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Error processing request: " . $e->getMessage();
    header("Location: form.php");
    exit;
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: form.php");
    exit;
} finally {
    $pdo = null;
}
?>
