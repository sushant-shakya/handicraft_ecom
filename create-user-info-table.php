<?php
require 'dbConnectionWithPDO.php'; 


try {
    // Ensure the connection is established
    if (!$pdo) {
        throw new Exception("Database connection is not established.");
    }

    // Set the timezone
    $pdo->exec("SET time_zone = '+05:45'");

    // Create the  user info table
    $sql = "CREATE TABLE user_info (
        user_id INT UNSIGNED AUTO_INCREMENT,
        full_name VARCHAR(200) NOT NULL,
        email VARCHAR(200) NOT NULL UNIQUE,
        country VARCHAR(100) NOT NULL,
        city VARCHAR(200) NOT NULL,
        postal_code VARCHAR(50) NOT NULL,
        address VARCHAR(200) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        payment_method VARCHAR(255) NOT NULL,
        PRIMARY KEY (user_id)
    );
    
    -- CREATE TABLE payment_method(
    --     payment_id INT UNSIGNED AUTO_INCREMENT,
    --      method_name VARCHAR(50) NOT NULL,
    --      PRIMARY KEY (payment_id)
    --     );
            ";

  
    // Execute the query
    $pdo->exec($sql);
    echo "Table created successfully.";
} catch (PDOException $e) {
    echo "PDO ERROR: " . $e->getMessage();
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
} 
// finally {
//    // Close the connection
//     $conn = null;
// }
?>
