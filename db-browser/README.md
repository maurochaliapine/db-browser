# DB Browser - Proyecto MySQL + Apache + PHP con Docker Compose

Este proyecto proporciona un entorno completo de desarrollo con MySQL y Apache/PHP usando Docker Compose.

## 🚀 Características

- **MySQL 8.0**: Base de datos relacional (SQL)
- **MongoDB 7.0**: Base de datos NoSQL
- **Apache + PHP 8.2**: Servidor web con PHP
- **Adminer**: Herramienta web para administrar MySQL (incluida automáticamente)
- **Mongo Express**: Herramienta web para administrar MongoDB (equivalente a Adminer)
- **Docker Compose**: Orquestación de contenedores
- **Persistencia de datos**: Volúmenes Docker para MySQL y MongoDB
- **Scripts de inicialización**: SQL y JavaScript automáticos al crear las bases de datos

## 📋 Requisitos Previos

- Docker instalado ([Docker Desktop](https://www.docker.com/products/docker-desktop))
- Docker Compose (incluido en Docker Desktop)

## 🛠️ Instalación y Uso

### 1. Clonar o descargar el proyecto

```bash
cd /Users/mauro/Documents/proyectos/db-browser
```

### 2. Configurar variables de entorno (opcional)

Copia el archivo de ejemplo y edítalo según tus necesidades:

```bash
cp env.example .env
```

Edita `.env` para cambiar contraseñas, puertos, etc.

**Nota**: Si no creas el archivo `.env`, se usarán los valores por defecto definidos en `docker-compose.yml`.

### 3. Iniciar los servicios

```bash
docker-compose up -d
```

Este comando:
- Descarga las imágenes necesarias (si no están disponibles)
- Crea los contenedores
- Inicia MySQL y Apache/PHP
- Ejecuta los scripts de inicialización SQL

### 4. Acceder a la aplicación

- **Aplicación web principal**: http://localhost:8080
- **Adminer (Administrador MySQL)**: http://localhost:8080/adminer.php
  - Sistema: `MySQL`
  - Servidor: `mysql` (o `localhost` si te conectas desde fuera del contenedor)
  - Usuario: `dbuser` (o el definido en `.env`)
  - Contraseña: `dbpassword` (o la definida en `.env`)
  - Base de datos: `db_browser` (o la definida en `.env`)
- **Mongo Express (Administrador MongoDB)**: http://localhost:8081
  - Usuario: `admin` (o el definido en `.env` como `MONGO_EXPRESS_USER`)
  - Contraseña: `admin` (o la definida en `.env` como `MONGO_EXPRESS_PASSWORD`)
- **MySQL directo**: localhost:3306
  - Usuario: `dbuser` (o el definido en `.env`)
  - Contraseña: `dbpassword` (o la definida en `.env`)
  - Base de datos: `db_browser` (o la definida en `.env`)
- **MongoDB directo**: localhost:27017
  - Usuario: `admin` (o el definido en `.env` como `MONGODB_ROOT_USER`)
  - Contraseña: `adminpassword` (o la definida en `.env` como `MONGODB_ROOT_PASSWORD`)
  - Base de datos: `db_browser` (o la definida en `.env`)

### 5. Verificar el estado

```bash
docker-compose ps
```

**💡 Tip**: Para una guía completa de pruebas desde la terminal, consulta [TESTING.md](TESTING.md)

## 📁 Estructura del Proyecto

```
db-browser/
├── docker-compose.yml      # Configuración de Docker Compose
├── Dockerfile              # Dockerfile personalizado para Apache/PHP
├── env.example             # Ejemplo de variables de entorno
├── .gitignore              # Archivos a ignorar en Git
├── README.md               # Este archivo
├── www/                    # Archivos PHP (montado en Apache)
│   ├── index.php          # Página principal con conexión a MySQL
│   ├── mongodb-test.php   # Página de prueba para MongoDB
│   ├── adminer.php        # Adminer (descargado automáticamente)
│   └── info.php           # phpinfo()
├── apache/
│   └── php.ini            # Configuración personalizada de PHP
├── mysql/
│   └── init/
│       └── 01-init.sql    # Scripts SQL de inicialización
└── mongodb/
    └── init/
        └── 01-init.js     # Scripts JavaScript de inicialización MongoDB
```

## 🔧 Comandos Útiles

### Iniciar servicios
```bash
docker-compose up -d
```

### Detener servicios
```bash
docker-compose down
```

### Detener y eliminar volúmenes (⚠️ elimina los datos)
```bash
docker-compose down -v
```

### Ver logs
```bash
# Todos los servicios
docker-compose logs -f

# Solo MySQL
docker-compose logs -f mysql

# Solo Apache
docker-compose logs -f apache-php
```

### Acceder a MySQL desde la línea de comandos
```bash
docker-compose exec mysql mysql -u dbuser -pdbpassword db_browser
```

### Acceder al contenedor Apache
```bash
docker-compose exec apache-php bash
```

### Reiniciar un servicio específico
```bash
docker-compose restart mysql
docker-compose restart apache-php
```

## 🗄️ Bases de Datos

### MySQL - Credenciales por defecto

- **Host**: `mysql` (desde PHP) o `localhost` (desde tu máquina)
- **Puerto**: `3306`
- **Usuario root**: `root` / Contraseña: `rootpassword`
- **Usuario**: `dbuser` / Contraseña: `dbpassword`
- **Base de datos**: `db_browser`

### MySQL - Tablas de ejemplo

El script de inicialización crea dos tablas con datos de ejemplo:

1. **usuarios**: Tabla con usuarios de ejemplo
2. **productos**: Tabla con productos de ejemplo

### MySQL - Ejecutar SQL personalizado

Puedes agregar más scripts SQL en `mysql/init/` (se ejecutan en orden alfabético).

### MongoDB - Credenciales por defecto

- **Host**: `mongodb` (desde PHP) o `localhost` (desde tu máquina)
- **Puerto**: `27017`
- **Usuario root**: `admin` / Contraseña: `adminpassword`
- **Base de datos**: `db_browser`

### MongoDB - Colecciones de ejemplo

El script de inicialización crea dos colecciones con datos de ejemplo:

1. **usuarios**: Colección con usuarios de ejemplo (documentos JSON)
2. **productos**: Colección con productos de ejemplo (documentos JSON con especificaciones anidadas)

### MongoDB - Ejecutar scripts personalizados

Puedes agregar más scripts JavaScript en `mongodb/init/` (se ejecutan en orden alfabético).

## 🗂️ Adminer - Administrador de Base de Datos

Adminer es una herramienta web ligera para administrar bases de datos MySQL. Se descarga automáticamente cuando inicias el contenedor por primera vez.

### Acceder a Adminer

1. Inicia los servicios: `docker-compose up -d`
2. Abre tu navegador en: http://localhost:8080/adminer.php
3. Completa el formulario de conexión:
   - **Sistema**: `MySQL`
   - **Servidor**: `mysql` (nombre del servicio en Docker Compose)
   - **Usuario**: `dbuser` (o el definido en `.env`)
   - **Contraseña**: `dbpassword` (o la definida en `.env`)
   - **Base de datos**: `db_browser` (o la definida en `.env`)

### Características de Adminer

- Interfaz web intuitiva para administrar MySQL
- Ejecutar consultas SQL
- Ver y editar tablas y datos
- Importar/exportar bases de datos
- Gestionar usuarios y permisos
- Sin necesidad de instalación adicional

### Nota sobre la descarga automática

Adminer se descarga automáticamente la primera vez que inicias el contenedor. Si necesitas actualizarlo manualmente, puedes:

```bash
docker-compose exec apache-php wget -O /var/www/html/adminer.php https://www.adminer.org/latest.php
```

## 🍃 MongoDB y Mongo Express

**⚠️ Nota importante**: Adminer solo funciona con bases de datos SQL (MySQL, PostgreSQL, SQLite, etc.). Para MongoDB (NoSQL), usamos **Mongo Express**, que es el equivalente a Adminer para MongoDB.

### Acceder a Mongo Express

1. Inicia los servicios: `docker-compose up -d`
2. Abre tu navegador en: http://localhost:8081
3. Inicia sesión con:
   - **Usuario**: `admin` (o el definido en `.env` como `MONGO_EXPRESS_USER`)
   - **Contraseña**: `admin` (o la definida en `.env` como `MONGO_EXPRESS_PASSWORD`)

### Características de Mongo Express

- Interfaz web intuitiva para administrar MongoDB
- Ver y editar colecciones y documentos
- Ejecutar consultas MongoDB
- Importar/exportar datos
- Gestionar índices
- Visualizar estadísticas de la base de datos

### Conectar a MongoDB desde la terminal

```bash
# Conectar usando mongosh (cliente MongoDB)
docker-compose exec mongodb mongosh -u admin -p adminpassword --authenticationDatabase admin

# O desde tu Mac (si tienes mongosh instalado)
mongosh "mongodb://admin:adminpassword@localhost:27017/db_browser?authSource=admin"
```

### Ejemplos de consultas MongoDB

```javascript
// Ver todas las bases de datos
show dbs

// Usar la base de datos
use db_browser

// Ver todas las colecciones
show collections

// Consultar documentos
db.usuarios.find()
db.productos.find({ categoria: "Electrónica" })
```

## 🔒 Seguridad

⚠️ **Importante**: Este proyecto está configurado para desarrollo. Para producción:

1. Cambia todas las contraseñas por defecto
2. No expongas puertos MySQL públicamente
3. Configura SSL/TLS
4. Revisa la configuración de PHP (`apache/php.ini`)
5. Usa variables de entorno seguras

## 🐛 Solución de Problemas

### El puerto 8080 ya está en uso

Edita `.env` y cambia `APACHE_PORT` a otro puerto (ej: `8081`).

### El puerto 3306 ya está en uso

Edita `.env` y cambia `MYSQL_PORT` a otro puerto (ej: `3307`).

### Error de conexión a MySQL

1. Verifica que MySQL esté corriendo: `docker-compose ps`
2. Revisa los logs: `docker-compose logs mysql`
3. Espera unos segundos después de iniciar (MySQL tarda en inicializarse)

### Los cambios en PHP no se reflejan

Asegúrate de que los archivos estén en `www/` y reinicia Apache:
```bash
docker-compose restart apache-php
```

## 📝 Notas

- Los datos de MySQL se persisten en un volumen Docker llamado `mysql_data`
- Los archivos PHP en `www/` se montan directamente, los cambios son inmediatos
- La configuración de PHP se puede modificar en `apache/php.ini`

## 📄 Licencia

Este proyecto es de código abierto y está disponible para uso personal y comercial.

