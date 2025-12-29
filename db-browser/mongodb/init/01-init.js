// Script de inicialización de MongoDB
// Este archivo se ejecuta automáticamente al crear el contenedor por primera vez

// Cambiar a la base de datos
db = db.getSiblingDB('db_browser');

// Crear una colección de usuarios de ejemplo
db.usuarios.insertMany([
    {
        nombre: 'Juan Pérez',
        email: 'juan@example.com',
        edad: 30,
        fecha_creacion: new Date(),
        activo: true
    },
    {
        nombre: 'María García',
        email: 'maria@example.com',
        edad: 25,
        fecha_creacion: new Date(),
        activo: true
    },
    {
        nombre: 'Carlos López',
        email: 'carlos@example.com',
        edad: 35,
        fecha_creacion: new Date(),
        activo: false
    }
]);

// Crear una colección de productos de ejemplo
db.productos.insertMany([
    {
        nombre: 'Laptop',
        precio: 1200.00,
        stock: 15,
        categoria: 'Electrónica',
        fecha_creacion: new Date(),
        especificaciones: {
            marca: 'Dell',
            modelo: 'XPS 13',
            ram: '16GB',
            almacenamiento: '512GB SSD'
        }
    },
    {
        nombre: 'Mouse',
        precio: 25.50,
        stock: 50,
        categoria: 'Accesorios',
        fecha_creacion: new Date(),
        especificaciones: {
            marca: 'Logitech',
            tipo: 'Inalámbrico',
            dpi: 1600
        }
    },
    {
        nombre: 'Teclado',
        precio: 45.00,
        stock: 30,
        categoria: 'Accesorios',
        fecha_creacion: new Date(),
        especificaciones: {
            marca: 'Corsair',
            tipo: 'Mecánico',
            switches: 'Cherry MX Red'
        }
    },
    {
        nombre: 'Monitor',
        precio: 300.00,
        stock: 20,
        categoria: 'Electrónica',
        fecha_creacion: new Date(),
        especificaciones: {
            marca: 'LG',
            tamaño: '27 pulgadas',
            resolucion: '2560x1440',
            tipo: 'IPS'
        }
    }
]);

// Crear índices para mejorar el rendimiento
db.usuarios.createIndex({ email: 1 }, { unique: true });
db.usuarios.createIndex({ nombre: 1 });
db.productos.createIndex({ nombre: 1 });
db.productos.createIndex({ categoria: 1 });

print('✅ Base de datos MongoDB inicializada con datos de ejemplo');


