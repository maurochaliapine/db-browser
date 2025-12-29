FROM php:8.2-apache

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Instalar herramientas necesarias para descargar Adminer
RUN apt-get update && apt-get install -y \
    wget \
    && rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Crear script de inicialización para descargar Adminer
RUN echo '#!/bin/bash\n\
# Verificar si Adminer existe y tiene contenido válido (no es placeholder)\n\
if [ ! -f /var/www/html/adminer.php ] || ! grep -q "function adminer_object" /var/www/html/adminer.php 2>/dev/null; then\n\
    echo "Descargando Adminer..."\n\
    wget -q -O /var/www/html/adminer.php https://www.adminer.org/latest.php\n\
    chown www-data:www-data /var/www/html/adminer.php\n\
    echo "Adminer descargado exitosamente"\n\
else\n\
    echo "Adminer ya está instalado"\n\
fi\n\
exec "$@"' > /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html

# Establecer el punto de entrada
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]

