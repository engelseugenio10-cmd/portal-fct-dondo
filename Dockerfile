FROM php:8.2-cli

# Instalar dependências do sistema e extensões SQLite
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite

# Copiar o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Instalar dependências do Laravel sem pacotes de desenvolvimento
RUN composer install --no-dev --optimize-autoloader

# Criar o diretório temporário e o ficheiro da base de dados SQLite com permissões totais
RUN mkdir -p /tmp && touch /tmp/database.sqlite
RUN chmod -R 777 storage bootstrap/cache /tmp /tmp/database.sqlite

EXPOSE 10000

# Script executável no arranque: limpa configuração, cria tabelas e arranca o servidor web
CMD php artisan config:clear && php artisan cache:clear && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}