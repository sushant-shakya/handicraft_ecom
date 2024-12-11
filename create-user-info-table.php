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
    FullName VARCHAR(100) NOT NULL,
    Email VARCHAR(150) UNIQUE NOT NULL,
    Country VARCHAR(50) NOT NULL,
    City VARCHAR(50) NOT NULL,
    PostalCode VARCHAR(20) NOT NULL,
    Address VARCHAR(100) NOT NULL,
    Phone VARCHAR(15) NOT NULL,
    PRIMARY KEY (UserID)
);

CREATE TABLE `Order` (
    OrderID INT AUTO_INCREMENT ,
    UserID INT  NOT NULL ,
    OrderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(OrderID),
    FOREIGN KEY (UserID) REFERENCES `User`(UserID) 
);

CREATE TABLE Product (
    ProductID INT AUTO_INCREMENT,
    OrderID INT  NOT NULL ,
    ProductName VARCHAR(100) NOT NULL,
    ProductPrice DECIMAL(10, 2) NOT NULL,
    ProductDescription TEXT,
     PRIMARY KEY(ProductID),
     FOREIGN KEY (OrderID) REFERENCES `Order`(OrderID) 
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
