<?php
session_start();
require __DIR__ . '/../database/dbConnectionWithPDO.php';

if (!$pdo) {
    $_SESSION['error'] = "Database connection failed.";
    header("Location: add-product-form.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Process file upload
        $image = $_FILES['product_image'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($image['type'], $allowed_types)) {
            $_SESSION['error'] = "Invalid file type. Only JPG, PNG, and WEBP allowed.";
            header("Location: add-product-form.php");
            exit;
        }

        if ($image['size'] > $max_size) {
            $_SESSION['error'] = "File size exceeds 5MB limit.";
            header("Location: add-product-form.php");
            exit;
        }

        $file_ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $unique_name = uniqid('product_', true) . '.' . $file_ext;
        $target_dir = "uploads/";
        $target_file = $target_dir . $unique_name;

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        if (!move_uploaded_file($image['tmp_name'], $target_file)) {
            $_SESSION['error'] = "Failed to upload image.";
            header("Location: add-product-form.php");
            exit;
        }

        // Collect other data
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $subtitle = filter_input(INPUT_POST, 'subtitle', FILTER_SANITIZE_STRING);
        $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $dimension = filter_input(INPUT_POST, 'dimension', FILTER_SANITIZE_STRING);
        $materials = filter_input(INPUT_POST, 'materials', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

        // Validate required fields (remove $image_path from check)
        if (empty($name) || empty($price) || empty($materials) || empty($description)) {
            $_SESSION['error'] = "All required fields must be filled.";
            header("Location: add-product-form.php");
            exit;
        }

        // Insert into database
        $stmt = $pdo->prepare("
            INSERT INTO Product 
            (ProductName, Subtitle, Price, dimension, materials, Description, Image_path) 
            VALUES (:name, :subtitle, :price, :dimension, :materials, :description, :image_path)
        ");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':subtitle', $subtitle);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':dimension', $dimension);
        $stmt->bindParam(':materials', $materials);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image_path', $target_file);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Product inserted successfully!";
        } else {
            $_SESSION['error'] = "Failed to insert product.";
        }

        header("Location: add-product-form.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: add-product-form.php");
        exit;
    }
}