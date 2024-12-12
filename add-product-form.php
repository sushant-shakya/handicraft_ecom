
<?php
session_start(); // Start the session

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout</title>
        <link rel =" icon" href="logo.png" type="image/x-icon">
        <link rel="stylesheet" href="form.css">
      <style>

.message {
            text-align: center; /* Center the text */
            margin: 20px auto; /* Add spacing around the message */
            width: fit-content; /* Adjust the width to the content */
            font-size: 16px; /* Adjust font size */
            padding: 10px 20px; /* Add padding for a better appearance */
            border-radius: 5px; /* Rounded corners */
        }
.message-success {
            color: green;
            background-color: #d4edda; /* Light green background */
            border: 1px solid #c3e6cb; /* Green border */
        }
.message-error {
            color: red;
            background-color: #f8d7da; /* Light red background */
            border: 1px solid #f5c6cb; /* Red border */
        }

      </style>
        
</head>
<body>

  <!-- Success and Error Messages -->
  <?php
    // Display success message if set
    if (isset($_SESSION['success'])) {
        echo '<div class="message message-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
        unset($_SESSION['success']); // Clear the message
    }

    // Display error message if set
    if (isset($_SESSION['error'])) {
        echo '<div class="message message-error">' . htmlspecialchars($_SESSION['error']) . '</div>';
        unset($_SESSION['error']); // Clear the message
    }
    ?>

      
    <div class="form-container">
        <h1>Add products</h1>
        <form action="insert-products.php" method="POST">
    <div class="form-group">
        <label for="name">Product Name:</label>
        <input type="text" name="name" id="name" required>
    </div>
    <div class="form-group">
        <label for="subtitle">Subtitle:</label>
        <input type="text" name="subtitle" id="subtitle">
    </div>
    <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" name="price" id="price" step="0.01" required>
    </div>
    <div class="form-group">
        <label for="dimension">Dimension:</label>
        <textarea name="dimension" id="dimension"></textarea>
    </div>
    <div class="form-group">
        <label for="materials">Materials:</label>
        <textarea name="materials" id="materials"></textarea>
    </div>
    <div class="form-group">
        <label for="description">Description:</label>
        <textarea name="description" id="description"></textarea>
    </div>
    <div class="form-group">
        <label for="image_path">Image Path:</label>
        <input type="text" name="image_path" id="image_path" required>
    </div>
  
    <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit">add product</button>
            </div>
        </form>
    </div>
</body>
</html>