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

# Criar ficheiro SQLite na pasta do projeto
RUN mkdir -p database && touch database/database.sqlite

# Dar permissões de escrita
RUN chmod -R 777 storage bootstrap/cache database

# Expor a porta do Render
EXPOSE 10000

# Executar migrações primeiro (cria as tabelas todas) e depois arrancar o servidor
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000