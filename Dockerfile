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

# Dar permissões totais às pastas de escrita e base de dados
RUN chmod -R 777 storage bootstrap/cache database

# Expor a porta 10000 que o Render exige
EXPOSE 10000

# Executar comandos de limpeza, forçar migrações apontando para o caminho absoluto e iniciar o servidor
CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan migrate --database=sqlite --path=database/migrations --force && \
    php artisan serve --host=0.0.0.0 --port=10000