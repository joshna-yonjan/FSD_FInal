CREATE DATABASE IF NOT EXISTS restaurant_system;
USE restaurant_system;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    cuisine VARCHAR(50),
    availability TINYINT(1) DEFAULT 1,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_category (category_id),
    INDEX idx_availability (availability)
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    customer_name VARCHAR(100),
    customer_email VARCHAR(100),
    customer_phone VARCHAR(20),
    total DECIMAL(10, 2) NOT NULL,
    status ENUM('Pending', 'Confirmed', 'Preparing', 'Ready', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status)
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    menu_item_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE
);

-- Default admin user (password: admin123)
INSERT INTO users (username, password, role, email) VALUES 
('admin', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'admin', 'admin@restaurant.com');

-- Sample categories
INSERT INTO categories (name, description) VALUES 
('Appetizers', 'Delicious starters'),
('Main Course', 'Hearty main dishes'),
('Desserts', 'Sweet treats'),
('Beverages', 'Refreshing drinks'),
('Salads', 'Fresh and healthy');

-- Sample menu items
INSERT INTO menu_items (category_id, name, description, price, cuisine, availability) VALUES 
(1, 'Spring Rolls', 'Crispy vegetable spring rolls with sweet chili sauce', 6.99, 'Asian', 1),
(1, 'Garlic Bread', 'Toasted bread with garlic butter and herbs', 4.99, 'Italian', 1),
(2, 'Grilled Salmon', 'Fresh Atlantic salmon with lemon butter sauce', 24.99, 'Seafood', 1),
(2, 'Chicken Tikka Masala', 'Tender chicken in creamy tomato curry', 18.99, 'Indian', 1),
(2, 'Margherita Pizza', 'Classic pizza with fresh mozzarella and basil', 15.99, 'Italian', 1),
(3, 'Chocolate Lava Cake', 'Warm chocolate cake with molten center', 8.99, 'American', 1),
(3, 'Tiramisu', 'Classic Italian coffee-flavored dessert', 7.99, 'Italian', 1),
(4, 'Fresh Orange Juice', 'Freshly squeezed orange juice', 4.99, 'Beverage', 1),
(4, 'Iced Coffee', 'Cold brewed coffee with ice', 3.99, 'Beverage', 1),
(5, 'Caesar Salad', 'Romaine lettuce with parmesan and croutons', 9.99, 'American', 1);
