<?php
session_start();

// If the product name is in GET, store it in SESSION and redirect
if (isset($_GET['product_name'])) {
    $_SESSION['product_name'] = htmlspecialchars($_GET['product_name']);
    header("Location: ./form.php");
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="icon" href="../logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../form.css">
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
.home-button {
    display: inline-block;
    padding: 8px 15px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-weight: bold;
}
.home-button:hover {
    background-color: #45a049;
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
        <a href="../templates/landingpg.php" class="home-button">Back To Home</a>
    </div>
    <div class="form-container">
        <h1>Checkout</h1>

        <!-- Display Selected Product -->
        <p><strong>Selected Product:</strong> <?php echo $product_name; ?></p>

        <form action="../src/order-form.php" method="POST">
            <input type="hidden" name="product_name" value="<?php echo $product_name; ?>">

            <!-- Contact Information -->
            <div class="form-group">
                <label for="full_name">Full Name*</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>

            <div class="form-group">
                <label for="country">Country*</label>
                <input type="text" id="country" name="country" value="Nepal" readonly>
            </div>

            <div class="form-group">
                <label for="city">City*</label>
                <input type="text" id="city" name="city" required>
            </div>

            <div class="form-group">
                <label for="postal_code">Postal Code*</label>
                <input type="number" id="postal_code" name="postal_code" required pattern="\d{5}" title="Enter a 5-digit postal code.">
            </div>

            <div class="form-group">
                <label for="address">Address*</label>
                <input type="text" id="address" name="address" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone No*</label>
                <input type="number" id="phone" name="phone" required placeholder="e.g. 9812345678" maxlength="10" pattern="^(98|97|96)[0-9]{8}$" title="Phone number must start with 98, 97, or 96 and be exactly 10 digits long.">
            </div>

            <div class="form-group">
                <label for="phone">Quantity*</label>
                <input type="number" id="quantity" name="quantity" required>
            </div>

            <!-- Payment Method -->
            <div class="form-group">
                <label>Payment Method</label>
                <input type="radio" id="cash" name="payment_method" value="Cash on Delivery" required>
                <label for="cash">Cash on Delivery</label>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit">Continue</button>
            </div>
        </form>
    </div>
</body>
</html>
