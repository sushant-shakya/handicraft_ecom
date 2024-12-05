<?php
require 'dbConnectionWithPDO.php';

try {
    // Ensure the connection is established
    if (!$pdo) {
        throw new Exception("Database connection is not established.");
    }

    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Initialize variables
        $postal_code = $phone = "";
        // Array to store validation errors
        $errors = [];

        // Collect and sanitize form inputs
        $full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
        $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
        $postal_code = filter_input(INPUT_POST, 'postal_code', FILTER_SANITIZE_STRING);  // Collect postal code
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);  // Collect phone number
        $payment_method = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING);

        // Validate the fields
        if (!$full_name) $errors[] = "Full name is required.";
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
        if (!$country) $errors[] = "Country is required.";
        if (!$city) $errors[] = "City is required.";

        // Validate postal code using regex
        if (!$postal_code || !preg_match("/^[0-9]{5}$/", $postal_code)) {
            $errors[] = "Postal code must be exactly 5 digits.";
        }

        if (!$address) $errors[] = "Address is required.";

        // Validate phone number using regex
        if (!$phone || !preg_match("/^(98|97|96)[0-9]{8}$/", $phone)) {
            $errors[] = "Phone number is required and must be in the format: (98|97|96)XXXXXXXX.";
        }

        if (!$payment_method) $errors[] = "Payment method is required.";

        // If there are errors, display them
        if (!empty($errors)) {
            echo "<h3>Form Validation Errors:</h3>";
            echo "<ul>";
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul>";
            echo "<a href='form.html'>Go back to the form</a>";
            exit;
        }

        // Check the payment method and proceed with insertion
        if ($payment_method === 'Cash on Delivery') {
            // Insert data directly into the database
            try {
                $stmt = $pdo->prepare("INSERT INTO user_info (full_name, email, country, city, postal_code, address, phone, payment_method) 
                                       VALUES (:full_name, :email, :country, :city, :postal_code, :address, :phone, :payment_method)");
                $stmt->bindParam(':full_name', $full_name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':country', $country);
                $stmt->bindParam(':city', $city);
                $stmt->bindParam(':postal_code', $postal_code);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':payment_method', $payment_method);

                if ($stmt->execute()) {
                    echo "<h3>Form Submitted Successfully!</h3>";
                    echo "<p>Your data has been saved.</p>";
                } else {
                    echo "<h3>Failed to Submit Form</h3>";
                    echo "<p>Something went wrong. Please try again.</p>";
                    echo "<a href='form.html'>Go back to the form</a>";
                }
            } catch (PDOException $e) {
                echo "<h3>Error Inserting Data:</h3>";
                echo "<p>" . $e->getMessage() . "</p>";
                echo "<a href='form.html'>Go back to the form</a>";
            }
        }
        // Add other payment method handling here if needed (like eSewa)

    } else {
        echo "<h3>Invalid Request</h3>";
        echo "<p>Please submit the form correctly.</p>";
        echo "<a href='checkout.html'>Go back to the form</a>";
    }
} catch (PDOException $e) {
    echo "<h3>Error Processing Request:</h3>";
    echo $e->getMessage();
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
} finally {
    // Close the database connection
    $pdo = null;
}
?>
