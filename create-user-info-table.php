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
    $sql = "CREATE TABLE `User` (
    UserID INT AUTO_INCREMENT ,
    UserName VARCHAR(100) NOT NULL,
    Email VARCHAR(150) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (UserID)
);

CREATE TABLE `Order` (
    OrderID INT AUTO_INCREMENT ,
    UserID INT  NOT NULL ,
    FullName VARCHAR(255) NOT NULL,                   -- Customer's full name
    Country VARCHAR(255),                             -- Customer's country
    City VARCHAR(255),                                -- Customer's city
    PostalCode VARCHAR(20),                           -- Postal code (if necessary)
    Address VARCHAR(100) NOT NULL,                            -- Customer's address
    Phone VARCHAR(20) NOT NULL,                       -- Customer's phone number
    PaymentMethod VARCHAR(100) NOT NULL,              -- Payment method (e.g., Credit Card, PayPal, etc.)
    ProductName VARCHAR(255) NOT NULL,
    Quantity INT NOT NULL,
    OrderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(OrderID),
    FOREIGN KEY (UserID) REFERENCES `User`(UserID) 
);

-- 3.product table
CREATE TABLE Product (
    ProductID INT AUTO_INCREMENT,
    ProductName VARCHAR(100) NOT NULL,
    Subtitle VARCHAR(300),
    Price DECIMAL(10, 2) NOT NULL,
    dimension VARCHAR(500),
    materials VARCHAR(400) NOT NULL,
    Description TEXT NOT NULL,
    Image_path VARCHAR(300) NOT NULL,
     PRIMARY KEY(ProductID),
);

CREATE TABLE Payment (
    PaymentID INT AUTO_INCREMENT ,
    OrderID INT NOT NULL,
    PaymentDate  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PaymentAmount DECIMAL(10, 2) NOT NULL,
    PaymentMode VARCHAR(50) NOT NULL,
    PRIMARY KEY(PaymentID),
    FOREIGN KEY (OrderID) REFERENCES `Order`(OrderID) 
);
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
