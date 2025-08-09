FROM php:8.3-cli

# Instala dependencias para compilar sqlite y utilidades
RUN apt-get update \
 && apt-get install -y --no-install-recommends \
      libsqlite3-dev \
      git unzip \
 && docker-php-ext-configure pdo_sqlite \
 && docker-php-ext-install pdo_sqlite sqlite3 \
 && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Opcional: si vas a copiar el proyecto en la imagen (en dev usamos volumen)
# COPY . /app

# Exponer puerto que usa artisan serve
EXPOSE 8000

# El comando final lo define docker-compose (artisan serve)
