USE recharge_db;

INSERT INTO users (name, email, password, role) VALUES ('Administrador', 'admin@sisifo.store', '$2y$12$cRaxO4IwpWiXbx9848eqo.mFhFfW/6uBD2tHfbU8uqjb4sNHqD/5y', 'admin')
ON DUPLICATE KEY UPDATE password = '$2y$12$cRaxO4IwpWiXbx9848eqo.mFhFfW/6uBD2tHfbU8uqjb4sNHqD/5y', role = 'admin';
