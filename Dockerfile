FROM php:8.2-cli

# Instalar dependências e SQLite
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Instalar pacotes do Laravel
RUN composer install --no-dev --optimize-autoloader

# Criar a base de dados SQLite e dar permissões de escrita
RUN mkdir -p /tmp && touch /tmp/database.sqlite
RUN chmod -R 777 storage bootstrap/cache /tmp /tmp/database.sqlite

EXPOSE 10000

# Executar migrações ANTES de iniciar o servidor da aplicação
CMD php artisan config:clear && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}