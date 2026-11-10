FROM php:8.2-cli

# Instalar dependências do sistema e extensão SQLite
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite

# Copiar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Instalar dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# Garantir que a pasta database existe e criar o ficheiro SQLite
RUN mkdir -p database && touch database/database.sqlite

# Limpar caches compiladas antigas
RUN rm -f bootstrap/cache/*.php

# Dar permissões totais
RUN chmod -R 777 storage bootstrap/cache database

# Expor a porta 10000 exigida pelo Render
EXPOSE 10000

# Forçar CACHE_STORE e SESSION_DRIVER como 'file' via variáveis de ambiente no comando, correr migrações e arrancar
CMD CACHE_STORE=file SESSION_DRIVER=file php artisan config:clear && \
    CACHE_STORE=file SESSION_DRIVER=file php artisan cache:clear && \
    CACHE_STORE=file SESSION_DRIVER=file php artisan migrate --force && \
    CACHE_STORE=file SESSION_DRIVER=file php artisan serve --host=0.0.0.0 --port=10000