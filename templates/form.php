<?php
session_start();

// If the product name is in GET, store it in SESSION and redirect
if (isset($_GET['product_name'])) {
    $_SESSION['product_name'] = htmlspecialchars($_GET['product_name']);
    header("Location: form.php");
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['redirect_url'] = '../templates/form.php';  
    header('Location: ../templates/login.php');  
    exit();
}

// Get the product name from SESSION
$product_name = isset($_SESSION['product_name']) ? $_SESSION['product_name'] : "No product selected";

// Retrieve previously submitted form data if exists
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

// Function to safely retrieve form value
function getFormValue($key, $form_data, $default = '') {
    return htmlspecialchars($form_data[$key] ?? $default);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="icon" href="../assets/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/form.css">
    <style>
        .message {
            text-align: center;
            margin: 20px auto;
            width: fit-content;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .message-success {
            color: green;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }
        .message-error {
            color: red;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
        .nav-buttons {
            text-align: right;
            margin-bottom: 15px;
        }
        .shop-button {
            display: inline-block;
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Success and Error Messages -->
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
        <div class="nav-buttons">
            <a href="../templates/shop.php" class="shop-button">Back To shop</a>
        </div>
        <div class="form-container">
            <h1>Checkout</h1>

            <!-- Display Selected Product -->
            <p><strong>Selected Product:</strong> <?php echo $product_name; ?></p>

            <form action="../src/order-form.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>">

                <!-- Contact Information -->
                <div class="form-group">
                    <label for="full_name">Full Name*</label>
                    <input type="text" id="full_name" name="full_name" required 
                           value="<?php echo getFormValue('full_name', $form_data); ?>">
                </div>

                <div class="form-group">
                    <label for="country">Country*</label>
                    <input type="text" id="country" name="country" value="Nepal" readonly 
                           value="<?php echo getFormValue('country', $form_data, 'Nepal'); ?>">
                </div>

                <div class="form-group">
                    <label for="city">City*</label>
                    <input type="text" id="city" name="city" required 
                           value="<?php echo getFormValue('city', $form_data); ?>">
                </div>

                <div class="form-group">
                    <label for="postal_code">Postal Code*</label>
                    <input type="number" id="postal_code" name="postal_code" required pattern="\d{5}" 
                           title="Enter a 5-digit postal code." 
                           value="<?php echo getFormValue('postal_code', $form_data); ?>">
                </div>

                <div class="form-group">
                    <label for="address">Address*</label>
                    <input type="text" id="address" name="address" required 
                           value="<?php echo getFormValue('address', $form_data); ?>">
                </div>

                <div class="form-group">
                    <label for="phone">Phone No*</label>
                    <input type="number" id="phone" name="phone" required 
                           placeholder="e.g. 9812345678" maxlength="10" 
                           pattern="^(98|97|96)[0-9]{8}$" 
                           title="Phone number must start with 98, 97, or 96 and must be exactly 10 digits long." 
                           value="<?php echo getFormValue('phone', $form_data); ?>">
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity*</label>
                    <input type="number" id="quantity" name="quantity" required 
                           value="<?php echo getFormValue('quantity', $form_data); ?>">
                </div>

                <!-- Payment Method -->
                <div class="form-group">
                    <label>Payment Method</label>
                    <input type="radio" id="cash" name="payment_method" value="Cash on Delivery" required
                           <?php echo (getFormValue('payment_method', $form_data) == 'Cash on Delivery') ? 'checked' : ''; ?>>
                    <label for="cash">Cash on Delivery</label>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit">Continue</button>
                </div>
            </form>
        </div>
    </div>

    <?php 
    // Clear form data after displaying to prevent persisting across multiple page loads
    unset($_SESSION['form_data']); 
    ?>
</body>
</html>