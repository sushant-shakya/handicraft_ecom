USE handicraftdb;
ALTER TABLE user_info
RENAME COLUMN id TO user_id;



-- tables 

1.user_info tables
user_id INT UNSIGNED AUTO_INCREMENT,
        full_name VARCHAR(200) NOT NULL,
        email VARCHAR(200) NOT NULL UNIQUE,
        country VARCHAR(100) NOT NULL,
        city VARCHAR(200) NOT NULL,
        postal_code VARCHAR(50) NOT NULL,
        address VARCHAR(200) NOT NULL,
        phone_number VARCHAR(20) NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
2.order TABLE
order_id(pk)
user_id
order_date
order_status

3. transaction TABLE
transaction_id(pk)
order_id
payment_method
payment_status
amount
transaction_date