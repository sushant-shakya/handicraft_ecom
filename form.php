
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
        <h1>Checkout</h1>
        <form action="insert-data-into-table.php" method="POST">
            <!-- Contact Information -->
            <div class="form-group">
                <label for="full_name">Full Name*</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email*</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="country">Country*</label>
                <input type="text" id="country" name="country"  value="Nepal" readonly>
            </div>
            <div class="form-group">
                <label for="city">City*</label>
                <input type="text" id="city" name="city" required>
            </div>
            <div class="form-group">
                <label for="postal_code">Postal Code*</label>
                <input type="number" id="postal_code" name="postal_code" required>
            </div>
            <div class="form-group">
                <label for="address">Address*</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone No*</label>
                <input type="number" id="phone" name="phone" required  placeholder="e.g 9812345678, 9760123456,    9641098765" maxlength="10" 
                pattern="^(98[0-9]{8})$" title="Phone number should start with 98|97|96 and be followed by 8 digits">
            </div>

            <!-- Payment Method -->
            <div class="form-group">
                <label>Payment Method</label>
                <input type="radio" id="cash" name="payment_method" value="Cash on Delivery" >
                <label for="cash">Cash on Delivery</label>
                <input type="radio" id="esewa" name="payment_method" value="Pay with eSewa">
                <label for="esewa">Pay with eSewa</label>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit">Continue</button>
            </div>
        </form>
    </div>
</body>
</html>

