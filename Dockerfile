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

# Instalar dependências
RUN composer install --no-dev --optimize-autoloader

# Criar a base de dados SQLite limpa e ajustar permissões
RUN mkdir -p /tmp && touch /tmp/database.sqlite
RUN chmod -R 777 storage bootstrap/cache /tmp /tmp/database.sqlite

EXPOSE 10000

# Executar migrações com --force e iniciar o servidor
CMD php artisan config:clear && php artisan migrate:fresh --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}