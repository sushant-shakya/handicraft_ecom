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
        // Collect other data - using htmlspecialchars instead of deprecated FILTER_SANITIZE_STRING
        $name = htmlspecialchars(trim($_POST['name'] ?? ''));
        $subtitle = htmlspecialchars(trim($_POST['subtitle'] ?? ''));
        $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $dimension = htmlspecialchars(trim($_POST['dimension'] ?? ''));
        $materials = htmlspecialchars(trim($_POST['materials'] ?? ''));
        $description = htmlspecialchars(trim($_POST['description'] ?? ''));

        // Validate required fields (only check fields marked as required in the HTML)
        if (empty($name) || empty($price)) {
            $_SESSION['error'] = "All required fields must be filled.";
            header("Location: add-product-form.php");
            exit;
        }

        // Process file upload
        $image = $_FILES['product_image'] ?? null;
        
        if (!$image || $image['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = "Product image is required.";
            header("Location: add-product-form.php");
            exit;
        }
        
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
        
        // Create uploads directory if it doesn't exist
        $target_dir = __DIR__ . "/uploads/";
        if (!is_dir($target_dir)) {
            if (!mkdir($target_dir, 0755, true)) {
                $_SESSION['error'] = "Failed to create uploads directory.";
                header("Location: add-product-form.php");
                exit;
            }
        }

        // Generate unique filename
        $file_ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $unique_name = uniqid('product_', true) . '.' . $file_ext;
        $target_file = $target_dir . $unique_name;
        $db_file_path = "uploads/" . $unique_name; // Store relative path in DB
        
        if (!move_uploaded_file($image['tmp_name'], $target_file)) {
            $_SESSION['error'] = "Failed to upload image.";
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
        $stmt->bindParam(':image_path', $db_file_path);
        
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