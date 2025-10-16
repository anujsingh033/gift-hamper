-- Create database
CREATE DATABASE IF NOT EXISTS gift_hamper_db;
USE gift_hamper_db;

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category_id INT,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Create users table for both customers and admins
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_address TEXT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create order_items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Create admins table (for backward compatibility, but we'll use users table with role='admin')
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample categories
INSERT INTO categories (name, description) VALUES
('Birthday Hampers', 'Special hampers for birthday celebrations'),
('Wedding Gifts', 'Elegant gifts for weddings and anniversaries'),
('Corporate Gifts', 'Professional gifts for business occasions'),
('Baby & Kids', 'Gifts for newborns and children'),
('Wellness & Spa', 'Relaxation and wellness focused hampers');

-- Insert sample products
INSERT INTO products (name, description, price, category_id, stock, image) VALUES
('Deluxe Birthday Hamper', 'A premium collection of gourmet treats and luxury items for birthdays', 89.99, 1, 15, 'birthday_deluxe.jpg'),
('Classic Celebration Box', 'Traditional favorites in an elegant presentation box', 59.99, 1, 20, 'classic_birthday.jpg'),
('Romantic Wedding Hamper', 'Champagne, chocolates, and romantic touches for newlyweds', 129.99, 2, 8, 'wedding_romantic.jpg'),
('Corporate Excellence Gift', 'Premium items suitable for executive gifts and client appreciation', 149.99, 3, 12, 'corporate_excellence.jpg'),
('Baby Care Essentials', 'Gentle, organic products for new parents and babies', 45.99, 4, 25, 'baby_essentials.jpg'),
('Spa Retreat Package', 'Luxurious spa products for ultimate relaxation at home', 79.99, 5, 10, 'spa_retreat.jpg');

-- Insert sample admin user (password is hashed 'admin123')
INSERT INTO users (username, email, password_hash, full_name, role) VALUES
('admin', 'admin@example.com', '$2y$10$4ZP.EasjVjnjtH/sXbDyUuNkzFiWfEmM.L.F.6d3RzvQBEcTRNqOu', 'Administrator', 'admin');

-- Insert sample customer user (password is hashed 'password123')
INSERT INTO users (username, email, password_hash, full_name, role) VALUES
('john_doe', 'john.doe@example.com', '$2y$10$55JzVr9GvM6j4G8yA9hG7u6v9N7p3E4t5R6y7U8i9O0p1Q2w3E4r', 'John Doe', 'user');

-- Insert sample order
INSERT INTO orders (customer_name, customer_email, customer_address, total_amount, status) VALUES
('John Smith', 'john.smith@example.com', '123 Main St, Cityville, State 12345', 149.98, 'processing');

-- Insert sample order items
INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
(1, 3, 1, 129.99),
(1, 5, 1, 19.99);