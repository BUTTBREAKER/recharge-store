USE recharge_db;

INSERT INTO users (name, email, password, role) VALUES ('Administrador', 'admin@sisifo.store', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'admin')
ON DUPLICATE KEY UPDATE password = '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', role = 'admin';
