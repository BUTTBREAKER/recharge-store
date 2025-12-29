-- Migration: Productos y Configuración de Pagos
-- Fecha: 2025-12-28

USE recharge_db;

-- Tabla de productos/paquetes
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    juego VARCHAR(100) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    cantidad INT NOT NULL COMMENT 'Cantidad de diamantes/moneda del juego',
    precio DECIMAL(10, 2) NOT NULL,
    precio_original DECIMAL(10, 2) NULL COMMENT 'Precio original para mostrar descuentos',
    activo TINYINT(1) DEFAULT 1,
    orden INT DEFAULT 0 COMMENT 'Orden de visualización',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_juego (juego),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de configuración de métodos de pago
CREATE TABLE IF NOT EXISTS payment_config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    metodo VARCHAR(50) NOT NULL UNIQUE,
    config JSON NOT NULL COMMENT 'Configuración en formato JSON',
    activo TINYINT(1) DEFAULT 1,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar productos iniciales para Mobile Legends
INSERT INTO productos (juego, nombre, cantidad, precio, precio_original, orden) VALUES
('Mobile Legends', '86 Diamantes', 86, 1.50, NULL, 1),
('Mobile Legends', '172 Diamantes', 172, 3.00, NULL, 2),
('Mobile Legends', '257 Diamantes', 257, 4.50, NULL, 3),
('Mobile Legends', '344 Diamantes', 344, 6.00, NULL, 4),
('Mobile Legends', '429 Diamantes', 429, 7.50, NULL, 5),
('Mobile Legends', '514 Diamantes', 514, 9.00, NULL, 6),
('Mobile Legends', '706 Diamantes', 706, 12.00, NULL, 7),
('Mobile Legends', '878 Diamantes', 878, 15.00, NULL, 8),
('Mobile Legends', '1050 Diamantes', 1050, 18.00, NULL, 9),
('Mobile Legends', '1412 Diamantes', 1412, 24.00, NULL, 10),
('Mobile Legends', '2195 Diamantes', 2195, 36.00, NULL, 11),
('Mobile Legends', '3688 Diamantes', 3688, 60.00, NULL, 12),
('Mobile Legends', 'Starlight Member', 1, 10.00, NULL, 13),
('Mobile Legends', 'Twilight Pass', 1, 12.00, NULL, 14)
ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;

-- Insertar configuración inicial de métodos de pago
INSERT INTO payment_config (metodo, config, activo) VALUES
('pagomovil', JSON_OBJECT(
    'banco', 'Banco de Venezuela',
    'telefono', '0424-1234567',
    'cedula', 'V-12345678',
    'titular', 'John Doe'
), 1),
('binance', JSON_OBJECT(
    'merchant_id', '',
    'api_key', '',
    'instrucciones', 'Enviar pago a través de Binance Pay y subir comprobante.'
), 1)
ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;
