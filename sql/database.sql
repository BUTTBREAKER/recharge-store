CREATE DATABASE IF NOT EXISTS recharge_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE recharge_db;

CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    juego VARCHAR(100) NOT NULL,
    player_id VARCHAR(50) NOT NULL,
    server_id VARCHAR(50) NOT NULL,
    paquete VARCHAR(100) NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    metodo_pago ENUM('pagomovil', 'binance') NOT NULL,
    estado ENUM('pendiente', 'confirmado', 'realizada', 'cancelado') DEFAULT 'pendiente',
    telefono VARCHAR(20) NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    referencia VARCHAR(100),
    comprobante VARCHAR(255),
    estado ENUM('pendiente', 'validado', 'rechazado') DEFAULT 'pendiente',
    provider ENUM('pagomovil', 'binance') NOT NULL,
    binance_order_id VARCHAR(100),
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar admin por defecto (password: password123)
-- Hash generated for testing
-- Note: For 'password123', use: $2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa
INSERT INTO users (name, email, password, role) VALUES ('Administrador', 'admin@sisifo.store', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'admin') ON DUPLICATE KEY UPDATE password='$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa';
