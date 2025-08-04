-- Create the database
CREATE DATABASE coffee_shop;
USE coffee_shop;

-- Create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user'
);


-- Create product table
CREATE TABLE  product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255)
);

-- Sample product data
INSERT INTO product (name, price, image) VALUES
('Americano Pure', 7500, 'img/p1.png'),
('Latte Classic', 8000, 'img/p2.png'),
('Cappuccino Gold', 8500, 'img/p3.png'),
('Mocha Delight', 9000, 'img/p4.png'),
('Espresso Shot', 9500, 'img/p5.png'),
('Double Americano', 10000, 'img/p1.png');

-- Create orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    order_date DATETIME NOT NULL,
    total_price DECIMAL(10,2) NOT NULL
);

-- Create order_items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES product(id)
);
