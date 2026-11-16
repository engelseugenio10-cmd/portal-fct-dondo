FROM php:8.2-cli

# Instalar dependências do sistema e extensões do PostgreSQL
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Copiar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Instalar dependências do Laravel com bypass seguro para extensões
RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-pdo_pgsql --ignore-platform-req=ext-pgsql

# Limpar caches compiladas antigas
RUN rm -f bootstrap/cache/*.php

# Dar permissões totais às pastas necessárias
RUN chmod -R 777 storage bootstrap/cache

# Expor a porta 10000 exigida pelo Render
EXPOSE 10000

# Limpar caches, recriar todas as tabelas do zero no PostgreSQL e arrancar a aplicação
CMD CACHE_STORE=file SESSION_DRIVER=file php artisan config:clear && \
    CACHE_STORE=file SESSION_DRIVER=file php artisan cache:clear && \
    CACHE_STORE=file SESSION_DRIVER=file php artisan migrate:fresh --force --seed && \
    CACHE_STORE=file SESSION_DRIVER=file php artisan serve --host=0.0.0.0 --port=10000