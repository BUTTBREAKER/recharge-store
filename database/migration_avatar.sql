-- Migración: Agregar columna avatar_url a usuarios
-- Fecha: 2026-01-21

USE recharge_db;

ALTER TABLE users 
ADD COLUMN avatar_url VARCHAR(255) NULL AFTER email;

-- Default null implies using initials generated from name as fallback
