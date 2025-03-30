<?php
session_start();

require __DIR__ . '/../database/dbConnectionWithPDO.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Store form data in session for persistence after validation errors
        $_SESSION['form_data'] = [
            'product_name' => $_POST['product_name'] ?? '',
            'subtitle' => $_POST['subtitle'] ?? '',
            'price' => $_POST['price'] ?? '',
            'dimension' => $_POST['dimension'] ?? '',
            'type' => $_POST['type'] ?? '',
            'materials' => $_POST['materials'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];

        // Collect and sanitize form data
        $name = htmlspecialchars(trim($_POST['product_name'] ?? ''));
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
            $errors[] = "Product name should contain only letters, spaces, and basic punctuation.";
        }
        
        // Validate subtitle (characters only, if provided)
        if (!empty($subtitle) && !preg_match("/^[a-zA-Z\s\-_.']+$/", $subtitle)) {
            $errors[] = "Subtitle should contain only letters, spaces, and basic punctuation.";
        }
        
        // Validate price (required, must be positive)
        if (empty($price)) {
            $errors[] = "Price is required.";
        } elseif (!is_numeric($price) || $price <= 0) {
            $errors[] = "Price must be a positive number.";
        }

        // Validate product type (characters only, if provided)
        if (!empty($type) && !preg_match("/^[a-zA-Z\s\-_.']+$/", $type)) {
            $errors[] = "Product type should contain only letters, spaces, and basic punctuation.";
        }
        
        // Validate materials (characters only, if provided)
        if (!empty($materials) && !preg_match("/^[a-zA-Z\s\-_.']+$/", $materials)) {
            $errors[] = "Materials should contain only letters, spaces, and basic punctuation.";
        }

        // If validation errors exist, redirect back with errors
        if (!empty($errors)) {
            // Fix: Correctly implode the array of errors
            $_SESSION['message'] = implode(". ", $errors);
            $_SESSION['message_type'] = "error";
            header("Location: edit-product.php?id=" . $product_id);
            exit;
        }

        // Process file upload if a new file is provided
        $image = $_FILES['image'] ?? null;
        $update_image = false;
        $db_file_path = null;
        
        if ($image && $image['name'] && $image['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
            $max_size = 5 * 1024 * 1024; // 5MB
            
            if (!in_array($image['type'], $allowed_types)) {
                $_SESSION['message'] = "Invalid file type. Only JPG, PNG, and WEBP allowed.";
                $_SESSION['message_type'] = "error";
                header("Location: edit-product.php?id=" . $product_id);
                exit;
            }
            
            if ($image['size'] > $max_size) {
                $_SESSION['message'] = "File size exceeds 5MB limit.";
                $_SESSION['message_type'] = "error";
                header("Location: edit-product.php?id=" . $product_id);
                exit;
            }
            
            // Create uploads directory if it doesn't exist
            $target_dir = __DIR__ . "/uploads/";
            if (!is_dir($target_dir)) {
                if (!mkdir($target_dir, 0755, true)) {
                    $_SESSION['message'] = "Failed to create uploads directory.";
                    $_SESSION['message_type'] = "error";
                    header("Location: edit-product.php?id=" . $product_id);
                    exit;
                }
            }

            // Generate unique filename
            $file_ext = pathinfo($image['name'], PATHINFO_EXTENSION);
            $unique_name = uniqid('product_', true) . '.' . $file_ext;
            $target_file = $target_dir . $unique_name;
            $db_file_path = "uploads/" . $unique_name; // Store relative path in DB
            
            if (!move_uploaded_file($image['tmp_name'], $target_file)) {
                $_SESSION['message'] = "Failed to upload image.";
                $_SESSION['message_type'] = "error";
                header("Location: edit-product.php?id=" . $product_id);
                exit;
            }
            
            $update_image = true;
        }

        // Build update query
        $sql = "UPDATE product SET 
                ProductName = :name, 
                Subtitle = :subtitle, 
                Price = :price, 
                dimension = :dimension, 
                type = :type, 
                materials = :materials, 
                Description = :description";
        
        $params = [
            ':name' => $name,
            ':subtitle' => $subtitle,
            ':price' => $price,
            ':dimension' => $dimension,
            ':type' => $type,
            ':materials' => $materials,
            ':description' => $description
        ];

        // Add image to query if it was uploaded
        if ($update_image) {
            $sql .= ", Image_path = :image_path";
            $params[':image_path'] = $db_file_path;
        }

        $sql .= " WHERE ProductID = :product_id";
        $params[':product_id'] = $product_id;

        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute($params)) {
            $_SESSION['message'] = "Product updated successfully!";
            $_SESSION['message_type'] = "success";
            // Clear form data on success
            unset($_SESSION['form_data']);
            header("Location: manage-products.php");
            exit();
        } else {
            $_SESSION['message'] = "Failed to update product.";
            $_SESSION['message_type'] = "error";
            header("Location: edit-product.php?id=" . $product_id);
            exit;
        }

    } catch(PDOException $e) {
        $_SESSION['message'] = "Database error: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
        header("Location: edit-product.php?id=" . $product_id);
        exit;
    }
}

// Fetch existing product data
try {
    $stmt = $pdo->prepare("SELECT * FROM product WHERE ProductID = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        $_SESSION['message'] = "Product not found";
        $_SESSION['message_type'] = "error";
        header("Location: manage-products.php");
        exit;
    }
    
    // Pre-populate form with session data if available (for form persistence after validation errors)
    if (isset($_SESSION['form_data'])) {
        $form_data = $_SESSION['form_data'];
        foreach ($form_data as $key => $value) {
            $product[$key === 'product_name' ? 'ProductName' : $key] = $value;
        }
    }
    
} catch(PDOException $e) {
    $_SESSION['message'] = "Error fetching product: " . $e->getMessage();
    $_SESSION['message_type'] = "error";
    header("Location: manage-products.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/logo.png" type="image/x-icon">
    <title>Edit Product</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        .current-image {
            margin: 10px 0;
        }
        .current-image img {
            max-width: 200px;
            height: auto;
        }
        .button-group {
            margin-top: 20px;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        .save-button {
            background-color: #ff9900;
            color: white;
        }
        .cancel-button {
            background-color: #666;
            color: white;
        }
        .save-button:hover {
            background-color: #e68a00;
        }
        .cancel-button:hover {
            background-color: #555;
        }
        .alert {
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Product</h1>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type'] ?>">
                <?= $_SESSION['message'] ?>
            </div>
            <?php 
                // Clear the message after displaying
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            ?>
        <?php endif; ?>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="product_name">Product Name</label>
                <input type="text" id="product_name" name="product_name" 
                       value="<?= htmlspecialchars($product['ProductName']) ?>" required>
            </div>

            <div class="form-group">
                <label for="subtitle">Subtitle</label>
                <input type="text" id="subtitle" name="subtitle" 
                       value="<?= htmlspecialchars($product['Subtitle']) ?>">
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" step="0.01" min="0.01"
                       value="<?= htmlspecialchars($product['Price']) ?>" required>
            </div>

            <div class="form-group">
                <label for="dimension">Dimensions</label>
                <input type="text" id="dimension" name="dimension" 
                       value="<?= htmlspecialchars($product['dimension']) ?>">
            </div>
            
            <div class="form-group">
                <label for="type">Product Type</label>
                <input type="text" id="type" name="type" 
                       value="<?= htmlspecialchars($product['type']) ?>">
            </div>

            <div class="form-group">
                <label for="materials">Materials</label>
                <input type="text" id="materials" name="materials" 
                       value="<?= htmlspecialchars($product['materials']) ?>">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"><?= htmlspecialchars($product['Description']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="image">Product Image</label>
                <?php if (!empty($product['Image_path'])): ?>
                    <div class="current-image">
                        <p>Current image:</p>
                        <img src="<?= htmlspecialchars($product['Image_path']) ?>" alt="Current product image">
                    </div>
                <?php endif; ?>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
                <small>Leave empty to keep current image. Only JPG, PNG, and WEBP files under 5MB are allowed.</small>
            </div>

            <div class="button-group">
                <button type="submit" class="save-button">Save Changes</button>
                <a href="manage-products.php">
                    <button type="button" class="cancel-button">Cancel</button>
                </a>
            </div>
        </form>
    </div>

    <script>
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const productName = document.getElementById('product_name').value.trim();
            const subtitle = document.getElementById('subtitle').value.trim();
            const price = document.getElementById('price').value;
            const type = document.getElementById('type').value.trim();
            const materials = document.getElementById('materials').value.trim();
            const image = document.getElementById('image').files[0];
            
            let errors = [];
            
            // Validate product name
            if (!productName) {
                errors.push('Product name is required');
            } else if (!/^[a-zA-Z\s\-_.']+$/.test(productName)) {
                errors.push('Product name should contain only letters, spaces, and basic punctuation');
            }
            
            // Validate subtitle if provided
            if (subtitle && !/^[a-zA-Z\s\-_.']+$/.test(subtitle)) {
                errors.push('Subtitle should contain only letters, spaces, and basic punctuation');
            }
            
            // Validate price
            if (!price) {
                errors.push('Price is required');
            } else if (isNaN(price) || parseFloat(price) <= 0) {
                errors.push('Price must be a positive number');
            }
            
            // Validate type if provided
            if (type && !/^[a-zA-Z\s\-_.']+$/.test(type)) {
                errors.push('Product type should contain only letters, spaces, and basic punctuation');
            }
            
            // Validate materials if provided
            if (materials && !/^[a-zA-Z\s\-_.']+$/.test(materials)) {
                errors.push('Materials should contain only letters, spaces, and basic punctuation');
            }
            
            // Validate image if provided
            if (image) {
                const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
                const maxSize = 5 * 1024 * 1024; // 5MB
                
                if (!allowedTypes.includes(image.type)) {
                    errors.push('Invalid file type. Only JPG, PNG, and WEBP allowed');
                }
                
                if (image.size > maxSize) {
                    errors.push('File size exceeds 5MB limit');
                }
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                alert(errors.join('\n'));
            }
        });

        // Image preview
        document.getElementById('image').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const currentImage = document.querySelector('.current-image img');
                    if (currentImage) {
                        currentImage.src = e.target.result;
                    } else {
                        const newImage = document.createElement('div');
                        newImage.className = 'current-image';
                        newImage.innerHTML = `
                            <p>New image preview:</p>
                            <img src="${e.target.result}" alt="New product image">
                        `;
                        document.getElementById('image').parentNode.appendChild(newImage);
                    }
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    </script>
</body>
</html>