CREATE DATABASE IF NOT EXISTS tienda_hogar;
USE tienda_hogar;

CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    categoria VARCHAR(50),
    descripcion TEXT,
    precio DECIMAL(10,2),
    imagen_url VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255),
    email VARCHAR(100),
    notas_secretas TEXT
);

INSERT INTO productos (nombre, categoria, descripcion, precio, imagen_url) VALUES
('Lampara de Escritorio LED', 'lamparas', 'Iluminacion regulable para entorno de oficina.', 25.50, 'img/lampara_01.jpg'),
('Silla Ergonomica Pro', 'sillas', 'Silla de oficina con soporte lumbar neumatico.', 120.00, 'img/silla_pro.jpg'),
('Mesa de Centro Roble', 'mesas', 'Mesa de madera barnizada para salas de espera.', 85.00, 'img/mesa_centro.jpg');

INSERT INTO usuarios (username, password, email, notas_secretas) VALUES
('adminbogota', MD5('Bogota2026!*'), 'admin.bogota@hmentor.local', 'Pin del Data Center: 4458-Alpha. No compartir con soporte.'),
('malava', MD5('PasswordSecure123'), 'm.alava@hmentor.local', 'Recordatorio: El tunel VPN hacia la DMZ se reinicia a las 03:00 AM.');
