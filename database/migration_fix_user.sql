-- Migración: Agregar user_id a pedidos y configuración de tasa de cambio
-- Fecha: 2025-12-29

USE recharge_db;

-- Agregar columna user_id a pedidos (opcional, para tracking de usuarios)
ALTER TABLE pedidos 
ADD COLUMN user_id INT NULL AFTER id,
ADD INDEX idx_user_id (user_id);

-- Tabla de configuración del sistema (tasa de cambio, etc.)
CREATE TABLE IF NOT EXISTS system_config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    config_key VARCHAR(50) NOT NULL UNIQUE,
    config_value TEXT NOT NULL,
    description VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar tasa de cambio inicial
INSERT INTO system_config (config_key, config_value, description) VALUES
('exchange_rate_usd_bs', '36.50', 'Tasa de cambio USD a Bolívares para Pago Móvil')
ON DUPLICATE KEY UPDATE config_value = config_value;
