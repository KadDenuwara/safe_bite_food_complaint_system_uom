CREATE DATABASE IF NOT EXISTS safebite_food_complaints;
USE safebite_food_complaints;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','complainer','handler') NOT NULL DEFAULT 'complainer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    complaint_code VARCHAR(30) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    handler_id INT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    media_path VARCHAR(255) NULL,
    status ENUM('Pending','In Progress','Solved') NOT NULL DEFAULT 'Pending',
    actions_taken TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (handler_id) REFERENCES users(id) ON DELETE SET NULL
);


-- Default password for all sample accounts: 123456
INSERT INTO users (name, email, password, role) VALUES
('System Administrator', 'admin@safebite.lk', '$2y$10$pBC7XPm/NbL8goz4kg6A2e3OP0ig8GOuhHo0FkjFYq1/U0kU7OOoG', 'admin'),
('Canteen Manager', 'canteen@uom.lk', '$2y$10$pBC7XPm/NbL8goz4kg6A2e3OP0ig8GOuhHo0FkjFYq1/U0kU7OOoG', 'handler'),
('Food Quality Officer', 'quality@uom.lk', '$2y$10$pBC7XPm/NbL8goz4kg6A2e3OP0ig8GOuhHo0FkjFYq1/U0kU7OOoG', 'handler');
