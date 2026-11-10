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

# Dar permissões totais
RUN chmod -R 777 storage bootstrap/cache database

# Expor a porta 10000 que o Render exige
EXPOSE 10000

# Executar migrações e iniciar o servidor a escutar explicitamente na porta 10000 (ou ${PORT})
CMD php artisan config:clear && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000