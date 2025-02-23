<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="icon" href="logo.png" type="image/x-icon">
    <link rel="stylesheet" href="form.css">
    <style>
        .form-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .image-preview-container {
            margin-top: 10px;
            text-align: center;
        }

        .image-preview {
            max-width: 300px;
            max-height: 300px;
            margin-top: 10px;
            border: 2px dashed #ddd;
            border-radius: 4px;
            display: none;
        }

        .file-input-container {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        .file-input-button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .file-input-button:hover {
            background-color: #0056b3;
        }

        input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }

        .message {
            text-align: center;
            margin: 20px auto;
            width: fit-content;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .message-success {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .message-error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }

        .form-actions {
            margin-top: 30px;
            text-align: center;
        }

        .form-actions button {
            background-color: #28a745;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-actions button:hover {
            background-color: #218838;
        }

        .required::after {
            content: "*";
            color: red;
            margin-left: 4px;
        }
    </style>
</head>
<body>
    <?php
    if (isset($_SESSION['success'])) {
        echo '<div class="message message-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
        unset($_SESSION['success']);
    }

    if (isset($_SESSION['error'])) {
        echo '<div class="message message-error">' . htmlspecialchars($_SESSION['error']) . '</div>';
        unset($_SESSION['error']);
    }
    ?>

    <div class="form-container">
        <h1>Add Product</h1>
        
        <form action="add-products-form-process.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name" class="required">Product Name</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    required
                    placeholder="Enter product name"
                >
            </div>

            <div class="form-group">
                <label for="subtitle">Subtitle</label>
                <input 
                    type="text" 
                    name="subtitle" 
                    id="subtitle"
                    placeholder="Enter product subtitle"
                >
            </div>

            <div class="form-group">
                <label for="price" class="required">Price</label>
                <input 
                    type="number" 
                    name="price" 
                    id="price" 
                    step="0.01" 
                    required
                    placeholder="0.00"
                    min="0"
                >
            </div>

            <div class="form-group">
                <label for="dimension">Dimensions</label>
                <textarea 
                    name="dimension" 
                    id="dimension"
                    placeholder="Enter product dimensions (e.g., height, width, depth)"
                ></textarea>
            </div>

            <div class="form-group">
                <label for="materials">Materials</label>
                <textarea 
                    name="materials" 
                    id="materials"
                    placeholder="Enter product materials (e.g., gold-plated, turquoise inlays)"
                ></textarea>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea 
                    name="description" 
                    id="description"
                    placeholder="Enter detailed product description"
                ></textarea>
            </div>

            <div class="form-group">
                <label for="product_image" class="required">Product Image</label>
                <div class="file-input-container">
                    <div class="file-input-button">Choose Image</div>
                    <input 
                        type="file" 
                        name="product_image" 
                        id="product_image"
                        accept="image/*"
                        required
                        onchange="previewImage(this);"
                    >
                </div>
                <div class="image-preview-container">
                    <img id="preview" class="image-preview">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit">Add Product</button>
            </div>
        </form>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            const file = input.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        }
    </script>
</body>
</html>