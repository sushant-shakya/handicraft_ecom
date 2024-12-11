USE handicraftdb;
 

-- 1.user_info tables
CREATE TABLE `User` (
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

-- 2. order table
CREATE TABLE `Order` (
    OrderID INT AUTO_INCREMENT ,
    UserID INT  NOT NULL ,
    OrderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(OrderID),
    FOREIGN KEY (UserID) REFERENCES `User`(UserID) 
);

-- 3.product table
CREATE TABLE Product (
    ProductID INT AUTO_INCREMENT,
    OrderID INT  NOT NULL ,
    ProductName VARCHAR(100) NOT NULL,
    ProductPrice DECIMAL(10, 2) NOT NULL,
    ProductDescription TEXT,
     PRIMARY KEY(ProductID),
     FOREIGN KEY (OrderID) REFERENCES `Order`(OrderID) 
);


-- 4.payment table
CREATE TABLE Payment (
    PaymentID INT AUTO_INCREMENT ,
    OrderID INT NOT NULL,
    PaymentDate  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PaymentAmount DECIMAL(10, 2) NOT NULL,
    PaymentMode VARCHAR(50) NOT NULL,
    PRIMARY KEY(PaymentID),
    FOREIGN KEY (OrderID) REFERENCES `Order`(OrderID) 
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
