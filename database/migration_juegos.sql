-- Migración: Tabla de Juegos
-- Fecha: 2026-01-17

USE recharge_db;

-- Tabla de juegos
CREATE TABLE IF NOT EXISTS juegos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT NULL,
    imagen VARCHAR(255) NULL COMMENT 'Ruta a la imagen del juego',
    icono VARCHAR(50) NULL COMMENT 'Emoji o código de icono',
    activo TINYINT(1) DEFAULT 1,
    orden INT DEFAULT 0 COMMENT 'Orden de visualización',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar juegos iniciales
INSERT INTO juegos (nombre, slug, descripcion, icono, orden) VALUES
('Mobile Legends', 'mobile-legends', 'Mobile Legends: Bang Bang - El MOBA más popular de móviles', '📱', 1)
ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;
