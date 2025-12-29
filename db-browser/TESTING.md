# 🧪 Guía de Pruebas - Terminal Mac

Esta guía te ayudará a probar el proyecto desde la terminal de Mac.

## 📋 Prerequisitos

Verifica que Docker esté instalado y funcionando:

```bash
docker --version
docker-compose --version
```

Si no tienes Docker instalado, descárgalo desde: https://www.docker.com/products/docker-desktop

## 🚀 Paso 1: Navegar al directorio del proyecto

```bash
cd /Users/mauro/Documents/proyectos/db-browser
```

## 🏗️ Paso 2: Construir e iniciar los servicios

### Construir las imágenes (primera vez o después de cambios en Dockerfile)

```bash
docker-compose build
```

### Iniciar los servicios en segundo plano

```bash
docker-compose up -d
```

### Ver el proceso de inicio en tiempo real

```bash
docker-compose up
```

(Presiona `Ctrl+C` para detener, pero los contenedores seguirán corriendo)

## ✅ Paso 3: Verificar el estado de los contenedores

### Ver el estado de todos los servicios

```bash
docker-compose ps
```

Deberías ver algo como:
```
NAME                  STATUS          PORTS
db-browser-apache     Up             0.0.0.0:8080->80/tcp
db-browser-mysql      Up (healthy)   0.0.0.0:3306->3306/tcp
```

### Ver todos los contenedores Docker (incluyendo otros proyectos)

```bash
docker ps
```

### Ver solo los contenedores de este proyecto

```bash
docker ps --filter "name=db-browser"
```

## 📊 Paso 4: Ver los logs

### Ver logs de todos los servicios

```bash
docker-compose logs -f
```

### Ver logs solo de MySQL

```bash
docker-compose logs -f mysql
```

### Ver logs solo de Apache/PHP

```bash
docker-compose logs -f apache-php
```

### Ver las últimas 50 líneas de logs

```bash
docker-compose logs --tail=50
```

## 🌐 Paso 5: Probar la aplicación web

### Verificar que Apache esté respondiendo

```bash
curl http://localhost:8080
```

Deberías ver el HTML de la página principal.

### Abrir en el navegador desde la terminal

```bash
open http://localhost:8080
```

### Probar Adminer (MySQL)

```bash
curl http://localhost:8080/adminer.php | head -20
```

O abrir en el navegador:

```bash
open http://localhost:8080/adminer.php
```

### Probar Mongo Express (MongoDB)

```bash
curl http://localhost:8081 | head -20
```

O abrir en el navegador:

```bash
open http://localhost:8081
```

## 🗄️ Paso 6: Probar la conexión a MySQL

### Conectar a MySQL desde la terminal (dentro del contenedor)

```bash
docker-compose exec mysql mysql -u dbuser -pdbpassword db_browser
```

Una vez dentro, puedes ejecutar comandos SQL:

```sql
SHOW TABLES;
SELECT * FROM usuarios;
SELECT * FROM productos;
EXIT;
```

### Ejecutar una consulta SQL directamente

```bash
docker-compose exec mysql mysql -u dbuser -pdbpassword db_browser -e "SHOW TABLES;"
```

### Ver las bases de datos disponibles

```bash
docker-compose exec mysql mysql -u dbuser -pdbpassword -e "SHOW DATABASES;"
```

### Ver el contenido de una tabla

```bash
docker-compose exec mysql mysql -u dbuser -pdbpassword db_browser -e "SELECT * FROM usuarios;"
```

### Verificar que MySQL esté saludable

```bash
docker-compose exec mysql mysqladmin ping -h localhost -u root -prootpassword
```

Debería responder: `mysqld is alive`

## 🍃 Paso 6b: Probar la conexión a MongoDB

### Conectar a MongoDB desde la terminal (dentro del contenedor)

```bash
docker-compose exec mongodb mongosh -u admin -p adminpassword --authenticationDatabase admin
```

Una vez dentro, puedes ejecutar comandos MongoDB:

```javascript
show dbs
use db_browser
show collections
db.usuarios.find()
db.productos.find()
exit
```

### Ejecutar una consulta MongoDB directamente

```bash
docker-compose exec mongodb mongosh -u admin -p adminpassword --authenticationDatabase admin --eval "db.getSiblingDB('db_browser').getCollectionNames()"
```

### Ver las bases de datos disponibles

```bash
docker-compose exec mongodb mongosh -u admin -p adminpassword --authenticationDatabase admin --eval "show dbs"
```

### Ver el contenido de una colección

```bash
docker-compose exec mongodb mongosh -u admin -p adminpassword --authenticationDatabase admin --eval "db.getSiblingDB('db_browser').usuarios.find().pretty()"
```

### Verificar que MongoDB esté saludable

```bash
docker-compose exec mongodb mongosh --eval "db.adminCommand('ping')" --quiet
```

Debería responder con información de estado.

### Contar documentos en una colección

```bash
docker-compose exec mongodb mongosh -u admin -p adminpassword --authenticationDatabase admin --eval "db.getSiblingDB('db_browser').usuarios.countDocuments()"
```

## 🔍 Paso 7: Verificar que Adminer se descargó correctamente

### Verificar que el archivo adminer.php existe

```bash
docker-compose exec apache-php ls -lh /var/www/html/adminer.php
```

### Ver el inicio del archivo (debe contener código PHP de Adminer)

```bash
docker-compose exec apache-php head -20 /var/www/html/adminer.php
```

### Ver los logs de descarga de Adminer

```bash
docker-compose logs apache-php | grep -i adminer
```

## 🧹 Paso 8: Comandos de limpieza y reinicio

### Detener los servicios (sin eliminar datos)

```bash
docker-compose stop
```

### Iniciar servicios detenidos

```bash
docker-compose start
```

### Reiniciar un servicio específico

```bash
docker-compose restart mysql
docker-compose restart mongodb
docker-compose restart apache-php
docker-compose restart mongo-express
```

### Detener y eliminar contenedores (⚠️ mantiene los datos)

```bash
docker-compose down
```

### Detener y eliminar contenedores Y volúmenes (⚠️ ELIMINA TODOS LOS DATOS)

```bash
docker-compose down -v
```

### Reconstruir las imágenes desde cero

```bash
docker-compose build --no-cache
docker-compose up -d
```

## 🐛 Paso 9: Solución de problemas

### Ver si hay errores en los contenedores

```bash
docker-compose ps
```

Si algún contenedor está en estado "Exited", revisa los logs:

```bash
docker-compose logs [nombre-del-servicio]
```

### Verificar que los puertos no estén en uso

```bash
# Verificar puerto 8080 (Apache)
lsof -i :8080

# Verificar puerto 3306 (MySQL)
lsof -i :3306
```

Si están en uso, puedes cambiar los puertos en el archivo `.env` o `docker-compose.yml`.

### Ver el uso de recursos

```bash
docker stats
```

### Acceder al shell del contenedor Apache

```bash
docker-compose exec apache-php bash
```

Una vez dentro:
```bash
php -v
php -m | grep -i mysql
ls -la /var/www/html/
exit
```

### Acceder al shell del contenedor MySQL

```bash
docker-compose exec mysql bash
```

### Acceder al shell del contenedor MongoDB

```bash
docker-compose exec mongodb bash
```

### Acceder a MongoDB con mongosh

```bash
docker-compose exec mongodb mongosh -u admin -p adminpassword --authenticationDatabase admin
```

### Ver la configuración de red Docker

```bash
docker network ls
docker network inspect db-browser_db-browser-network
```

### Verificar variables de entorno en los contenedores

```bash
docker-compose exec apache-php env | grep MYSQL
docker-compose exec apache-php env | grep MONGODB
```

## 📝 Paso 10: Pruebas rápidas (script de verificación)

Crea un script de prueba rápida:

```bash
#!/bin/bash
echo "🔍 Verificando servicios..."
echo ""
echo "📦 Estado de contenedores:"
docker-compose ps
echo ""
echo "🌐 Probando Apache:"
curl -s -o /dev/null -w "HTTP Status: %{http_code}\n" http://localhost:8080
echo ""
echo "🗄️ Probando MySQL:"
docker-compose exec -T mysql mysqladmin ping -h localhost -u root -prootpassword 2>/dev/null && echo "✅ MySQL está funcionando" || echo "❌ MySQL no responde"
echo ""
echo "📊 Tablas en la base de datos:"
docker-compose exec -T mysql mysql -u dbuser -pdbpassword db_browser -e "SHOW TABLES;" 2>/dev/null
```

Guarda esto en un archivo `test.sh`, dale permisos de ejecución y ejecútalo:

```bash
chmod +x test.sh
./test.sh
```

## ✅ Checklist de verificación

- [ ] Docker y Docker Compose están instalados
- [ ] Los contenedores están corriendo (`docker-compose ps`)
- [ ] Apache responde en http://localhost:8080
- [ ] La página principal muestra la conexión a MySQL exitosa
- [ ] Adminer está disponible en http://localhost:8080/adminer.php
- [ ] Mongo Express está disponible en http://localhost:8081
- [ ] Puedo conectarme a MySQL desde la terminal
- [ ] Puedo conectarme a MongoDB desde la terminal
- [ ] Las tablas de ejemplo existen (`usuarios` y `productos`)
- [ ] Las colecciones de ejemplo existen (`usuarios` y `productos` en MongoDB)
- [ ] Los logs no muestran errores críticos

## 🎯 Comandos más usados (resumen)

```bash
# Iniciar todo
docker-compose up -d

# Ver estado
docker-compose ps

# Ver logs
docker-compose logs -f

# Conectar a MySQL
docker-compose exec mysql mysql -u dbuser -pdbpassword db_browser

# Conectar a MongoDB
docker-compose exec mongodb mongosh -u admin -p adminpassword --authenticationDatabase admin

# Detener todo
docker-compose down

# Reiniciar todo
docker-compose restart
```


