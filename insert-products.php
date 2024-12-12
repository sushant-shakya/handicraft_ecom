<?php
session_start(); // Start the session
require 'dbConnectionWithPDO.php'; 

try {
    // Ensure the connection is established
    if (!$pdo) {
        throw new Exception("Database connection is not established.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Collect and sanitize product inputs
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $subtitle = filter_input(INPUT_POST, 'subtitle', FILTER_SANITIZE_STRING);
        $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $dimension = filter_input(INPUT_POST, 'dimension', FILTER_SANITIZE_STRING);
        $materials = filter_input(INPUT_POST, 'materials', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $image_path = filter_input(INPUT_POST, 'image_path', FILTER_SANITIZE_STRING);

        // Validate required fields
        if (empty($name) || empty($price) || empty($materials) || empty($description) || empty($image_path)) {
            $_SESSION['error'] = "All required fields must be filled.";
            header("Location: add-product-form.php");
            exit;
        }

        try {
            // Prepare the SQL statement for the Product table
            $stmt = $pdo->prepare("INSERT INTO Product (ProductName, Subtitle, Price, dimension, materials, Description, Image_path) 
                                   VALUES (:name, :subtitle, :price, :dimension, :materials, :description, :image_path)");
            
            // Bind parameters
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':subtitle', $subtitle, PDO::PARAM_STR);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $stmt->bindParam(':dimension', $dimension, PDO::PARAM_STR);
            $stmt->bindParam(':materials', $materials, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':image_path', $image_path, PDO::PARAM_STR);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success'] = "Product inserted successfully!";
                header("Location: add-product-form.php");
                exit;
            } else {
                $_SESSION['error'] = "Failed to insert product. Please try again.";
                header("Location: add-product-form.php");
                exit;
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error inserting product: " . $e->getMessage();
            header("Location: add-product-form.php");
            exit;
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
