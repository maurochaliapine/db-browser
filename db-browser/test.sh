#!/bin/bash

# Script de prueba rápida para DB Browser
# Uso: ./test.sh

echo "🔍 Verificando servicios de DB Browser..."
echo ""

# Colores para output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Verificar que estamos en el directorio correcto
if [ ! -f "docker-compose.yml" ]; then
    echo -e "${RED}❌ Error: No se encuentra docker-compose.yml${NC}"
    echo "Asegúrate de estar en el directorio del proyecto"
    exit 1
fi

echo "📦 Estado de contenedores:"
docker-compose ps
echo ""

# Verificar Apache
echo "🌐 Probando Apache (http://localhost:8080):"
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8080 2>/dev/null)
if [ "$HTTP_CODE" = "200" ]; then
    echo -e "${GREEN}✅ Apache está respondiendo (HTTP $HTTP_CODE)${NC}"
else
    echo -e "${RED}❌ Apache no responde (HTTP $HTTP_CODE)${NC}"
fi
echo ""

# Verificar MySQL
echo "🗄️ Probando MySQL:"
if docker-compose exec -T mysql mysqladmin ping -h localhost -u root -prootpassword 2>/dev/null | grep -q "alive"; then
    echo -e "${GREEN}✅ MySQL está funcionando${NC}"
else
    echo -e "${RED}❌ MySQL no responde${NC}"
fi
echo ""

# Verificar MongoDB
echo "🍃 Probando MongoDB:"
if docker-compose exec -T mongodb mongosh --eval "db.adminCommand('ping')" --quiet 2>/dev/null | grep -q "ok.*1"; then
    echo -e "${GREEN}✅ MongoDB está funcionando${NC}"
else
    echo -e "${RED}❌ MongoDB no responde${NC}"
fi
echo ""

# Verificar Mongo Express
echo "📊 Verificando Mongo Express:"
HTTP_CODE_MONGO=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8081 2>/dev/null)
if [ "$HTTP_CODE_MONGO" = "200" ] || [ "$HTTP_CODE_MONGO" = "401" ]; then
    echo -e "${GREEN}✅ Mongo Express está respondiendo (HTTP $HTTP_CODE_MONGO)${NC}"
else
    echo -e "${RED}❌ Mongo Express no responde (HTTP $HTTP_CODE_MONGO)${NC}"
fi
echo ""

# Verificar Adminer
echo "📊 Verificando Adminer:"
if docker-compose exec apache-php test -f /var/www/html/adminer.php 2>/dev/null; then
    echo -e "${GREEN}✅ Adminer está instalado${NC}"
    ADMINER_SIZE=$(docker-compose exec -T apache-php stat -f%z /var/www/html/adminer.php 2>/dev/null || docker-compose exec -T apache-php stat -c%s /var/www/html/adminer.php 2>/dev/null)
    if [ "$ADMINER_SIZE" -gt 1000 ]; then
        echo -e "   Tamaño del archivo: ${ADMINER_SIZE} bytes"
    fi
else
    echo -e "${YELLOW}⚠️ Adminer no encontrado (se descargará al iniciar)${NC}"
fi
echo ""

# Verificar tablas en MySQL
echo "📋 Tablas en MySQL 'db_browser':"
TABLES=$(docker-compose exec -T mysql mysql -u dbuser -pdbpassword db_browser -e "SHOW TABLES;" 2>/dev/null | grep -v "Tables_in" | grep -v "^$")
if [ -n "$TABLES" ]; then
    echo -e "${GREEN}✅ Tablas encontradas:${NC}"
    echo "$TABLES" | while read table; do
        if [ -n "$table" ]; then
            COUNT=$(docker-compose exec -T mysql mysql -u dbuser -pdbpassword db_browser -e "SELECT COUNT(*) as count FROM \`$table\`;" 2>/dev/null | grep -v "count" | grep -v "^$" | xargs)
            echo "   - $table ($COUNT filas)"
        fi
    done
else
    echo -e "${YELLOW}⚠️ No se encontraron tablas${NC}"
fi
echo ""

# Verificar colecciones en MongoDB
echo "🍃 Colecciones en MongoDB 'db_browser':"
COLLECTIONS=$(docker-compose exec -T mongodb mongosh -u admin -p adminpassword --authenticationDatabase admin --quiet --eval "db.getSiblingDB('db_browser').getCollectionNames()" 2>/dev/null | grep -o '"[^"]*"' | tr -d '"')
if [ -n "$COLLECTIONS" ]; then
    echo -e "${GREEN}✅ Colecciones encontradas:${NC}"
    echo "$COLLECTIONS" | while read collection; do
        if [ -n "$collection" ]; then
            COUNT=$(docker-compose exec -T mongodb mongosh -u admin -p adminpassword --authenticationDatabase admin --quiet --eval "db.getSiblingDB('db_browser').getCollection('$collection').countDocuments()" 2>/dev/null | grep -v "MongoServerError" | xargs)
            echo "   - $collection ($COUNT documentos)"
        fi
    done
else
    echo -e "${YELLOW}⚠️ No se encontraron colecciones${NC}"
fi
echo ""

# Verificar conexión PHP a MySQL
echo "🔗 Verificando conexión PHP -> MySQL:"
if docker-compose exec -T apache-php php -r "
\$host = 'mysql';
\$db = 'db_browser';
\$user = 'dbuser';
\$pass = 'dbpassword';
try {
    \$pdo = new PDO(\"mysql:host=\$host;dbname=\$db\", \$user, \$pass);
    echo '✅ Conexión PHP a MySQL exitosa\n';
} catch (PDOException \$e) {
    echo '❌ Error de conexión: ' . \$e->getMessage() . '\n';
    exit(1);
}
" 2>/dev/null; then
    echo -e "${GREEN}✅ Conexión PHP a MySQL exitosa${NC}"
else
    echo -e "${RED}❌ Error en la conexión PHP a MySQL${NC}"
fi
echo ""

# Resumen
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📝 Resumen:"
echo ""
echo "🌐 Aplicación web: http://localhost:8080"
echo "🗂️ Adminer (MySQL): http://localhost:8080/adminer.php"
echo "🍃 Mongo Express (MongoDB): http://localhost:8081"
echo ""
echo "🗄️ MySQL: localhost:3306"
echo "🍃 MongoDB: localhost:27017"
echo ""
echo "Para ver logs en tiempo real:"
echo "  docker-compose logs -f"
echo ""
echo "Para detener los servicios:"
echo "  docker-compose down"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"


