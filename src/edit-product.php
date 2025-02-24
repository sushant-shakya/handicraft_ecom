<?php
session_start();

// Database configuration
$db_host = "localhost:3306";
$db_name = "handicraftdb";
$db_user = "root";
$db_pass = "11111111";

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "UPDATE product SET 
                ProductName = ?, 
                Subtitle = ?, 
                Price = ?, 
                dimension = ?, 
                materials = ?, 
                Description = ?";
        
        $params = [
            $_POST['product_name'],
            $_POST['subtitle'],
            $_POST['price'],
            $_POST['dimension'],
            $_POST['materials'],
            $_POST['description']
        ];

        // Handle image upload if a new file is selected
        if (!empty($_FILES['image']['name'])) {
            $image_path = 'uploads/' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                $sql .= ", Image_path = ?";
                $params[] = $image_path;
            }
        }

        $sql .= " WHERE ProductID = ?";
        $params[] = $product_id;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $_SESSION['message'] = "Product updated successfully!";
        $_SESSION['message_type'] = "success";
        header("Location: manage-products.php");
        exit();

    } catch(PDOException $e) {
        $_SESSION['message'] = "Error updating product: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
    }
}

// Fetch existing product data
try {
    $stmt = $pdo->prepare("SELECT * FROM product WHERE ProductID = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        die("Product not found");
    }
} catch(PDOException $e) {
    die("Error fetching product: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Product</h1>
        
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
                <input type="number" id="price" name="price" 
                       value="<?= htmlspecialchars($product['Price']) ?>" required>
            </div>

            <div class="form-group">
                <label for="dimension">Dimensions</label>
                <input type="text" id="dimension" name="dimension" 
                       value="<?= htmlspecialchars($product['dimension']) ?>">
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
                <input type="file" id="image" name="image" accept="image/*">
                <small>Leave empty to keep current image</small>
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
            const price = document.getElementById('price').value;
            if (price < 0) {
                e.preventDefault();
                alert('Price cannot be negative');
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