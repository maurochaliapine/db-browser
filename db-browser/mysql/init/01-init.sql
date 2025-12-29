-- Script de inicialización de la base de datos
-- Este archivo se ejecuta automáticamente al crear el contenedor por primera vez

-- Crear una tabla de ejemplo
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar algunos datos de ejemplo
INSERT INTO usuarios (nombre, email) VALUES
    ('Juan Pérez', 'juan@example.com'),
    ('María García', 'maria@example.com'),
    ('Carlos López', 'carlos@example.com')
ON DUPLICATE KEY UPDATE nombre=nombre;

-- Crear otra tabla de ejemplo
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar productos de ejemplo
INSERT INTO productos (nombre, precio, stock) VALUES
    ('Laptop', 1200.00, 15),
    ('Mouse', 25.50, 50),
    ('Teclado', 45.00, 30),
    ('Monitor', 300.00, 20)
ON DUPLICATE KEY UPDATE nombre=nombre;



