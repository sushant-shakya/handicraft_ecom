USE handicraftdb;

--  ALTER TABLE Product
-- DROP FOREIGN KEY product_ibfk_1;

-- ALTER TABLE Product
-- DROP COLUMN OrderID;

ALTER TABLE `User` 
ADD COLUMN OTP VARCHAR(6) NOT NULL , 
ADD COLUMN OTPExpiry DATETIME  NULL;



ALTER TABLE `User`
DROP COLUMN address, 
DROP COLUMN phone;

ALTER TABLE `User` 
DROP COLUMN ResetToken, 
DROP COLUMN TokenExpiry;

ALTER TABLE `User` MODIFY `OTP` VARCHAR(255) DEFAULT '000000';


-- 1.user_info tables
CREATE TABLE `User` (
    UserID INT AUTO_INCREMENT ,
    UserName VARCHAR(100) NOT NULL,
    Email VARCHAR(150) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    OTP VARCHAR(6) DEFAULT '000000',
    OTPExpiry DATETIME  NULL,
    Created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (UserID)
);

-- 2. order table
CREATE TABLE `Order` (
    OrderID INT AUTO_INCREMENT,
    UserID INT NOT NULL,
    FullName VARCHAR(255) NOT NULL,                  
    Country VARCHAR(255),                            
    City VARCHAR(255),                               
    PostalCode VARCHAR(20),                          
    Address VARCHAR(100) NOT NULL,                   
    Phone VARCHAR(20) NOT NULL,                      
    PaymentMethod VARCHAR(100) NOT NULL,             
    ProductName VARCHAR(255) NOT NULL,
    Quantity INT NOT NULL,
    OrderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (OrderID),
    FOREIGN KEY (UserID) REFERENCES `User`(UserID) ON DELETE CASCADE
);


-- 3.product table
CREATE TABLE Product (
    ProductID INT AUTO_INCREMENT,
    -- OrderID INT  NOT NULL ,
    ProductName VARCHAR(100) NOT NULL,
    Subtitle VARCHAR(300),
    Price DECIMAL(10, 2) NOT NULL,
    dimension VARCHAR(500),
    materials VARCHAR(400) NOT NULL,
    Description TEXT NOT NULL,
    Image_path VARCHAR(300) NOT NULL,
     PRIMARY KEY(ProductID),
    --  FOREIGN KEY (OrderID) REFERENCES `Order`(OrderID) 
);


-- 4.payment table
CREATE TABLE Payment (
    PaymentID INT AUTO_INCREMENT ,
    OrderID INT NOT NULL,
    PaymentDate  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PaymentAmount DECIMAL(10, 2) NOT NULL,
    PaymentMode VARCHAR(50) NOT NULL,
    PRIMARY KEY(PaymentID),
    FOREIGN KEY (OrderID) REFERENCES `Order`(OrderID) ON DELETE CASCADE

);



-- db schema daigram query
Table User {
    UserID INT [pk] // Primary Key
    FullName VARCHAR(100) [NOT NULL]
    Email VARCHAR(150) [ unique , NOT NULL]
    Country VARCHAR(50) [NOT NULL]
    City VARCHAR(50) [NOT NULL]
    PostalCode VARCHAR(20) [NOT NULL]
    Address VARCHAR(100) [NOT NULL]
    Phone VARCHAR(15) [NOT NULL]
}

Table Order {
    OrderID INT [pk] // Primary Key
    UserID INT  // Foreign Key referencing User.UserID
    OrderDate TIMESTAMP [default: `CURRENT_TIMESTAMP`,not null]
}

Table Product {
    ProductID INT [pk] // Primary Key
    OrderID INT  // Foreign Key referencing Order.OrderID
    ProductName VARCHAR(100) [NOT NULL]
    ProductPrice DECIMAL(10,2) [NOT NULL]
    ProductDescription TEXT
}

Table Payment {
    PaymentID INT [pk] // Primary Key
    OrderID INT  // Foreign Key referencing Order.OrderID
    PaymentDate TIMESTAMP [default: `CURRENT_TIMESTAMP`, not null]
    PaymentAmount DECIMAL(10,2) [NOT NULL]
    PaymentMode VARCHAR(50) [NOT NULL]
}

// Relationships
Ref: User.UserID < Order.UserID
Ref: Order.OrderID > Payment.OrderID
Ref: Order.OrderID <> Product.OrderID
