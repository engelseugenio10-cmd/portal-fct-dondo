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

# Garantir que a pasta database existe e criar o ficheiro SQLite localmente no projeto
RUN mkdir -p database && touch database/database.sqlite

# Dar permissões totais às pastas de escrita e à base de dados
RUN chmod -R 777 storage bootstrap/cache database

EXPOSE 10000

# Executar migrações e iniciar o servidor
CMD php artisan config:clear && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}