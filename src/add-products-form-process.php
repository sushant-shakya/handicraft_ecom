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
        // Store form data in session for persistence after validation errors
        $_SESSION['form_data'] = [
            'name' => $_POST['name'] ?? '',
            'subtitle' => $_POST['subtitle'] ?? '',
            'price' => $_POST['price'] ?? '',
            'dimension' => $_POST['dimension'] ?? '',
            'type' => $_POST['type'] ?? '',
            'materials' => $_POST['materials'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];

        // Collect other data - using htmlspecialchars instead of deprecated FILTER_SANITIZE_STRING
        $name = htmlspecialchars(trim($_POST['name'] ?? ''));
        $subtitle = htmlspecialchars(trim($_POST['subtitle'] ?? ''));
        $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $dimension = htmlspecialchars(trim($_POST['dimension'] ?? ''));
        $type = htmlspecialchars(trim($_POST['type'] ?? ''));
        $materials = htmlspecialchars(trim($_POST['materials'] ?? ''));
        $description = htmlspecialchars(trim($_POST['description'] ?? ''));

        // Enhanced validation  
        $errors = []; 
        
        // Validate name (required, characters only)
        if (empty($name)) {
            $errors[] = "Product name is required.";
        } elseif (!preg_match("/^[a-zA-Z\s\-_.']+$/", $name)) {
            $errors[] = "Product name should contain only letters,  spaces, and basic punctuation.";
        }
        
        // Validate subtitle (characters only, if provided)
        if (!empty($subtitle) && !preg_match("/^[a-zA-Z\s\-_.']+$/", $subtitle)) {
            $errors[] = "Subtitle should contain only letters,  spaces, and basic punctuation.";
        }
        
        // Validate price (required, must be positive)
        if (empty($price)) {
            $errors[] = "Price is required.";
        } elseif (!is_numeric($price) || $price <= 0) {
            $errors[] = "Price must be a positive number.";
        }

        // If validation errors exist, redirect back with errors
        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
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
            (ProductName, Subtitle, Price, dimension, type, materials, Description, Image_path)
            VALUES (:name, :subtitle, :price, :dimension, :type, :materials, :description, :image_path)
        ");
        
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':subtitle', $subtitle);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':dimension', $dimension);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':materials', $materials);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image_path', $db_file_path);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Product inserted successfully!";
            // Clear form data on success
            unset($_SESSION['form_data']);
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