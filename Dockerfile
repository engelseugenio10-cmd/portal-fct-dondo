FROM php:8.2-cli

# Instalar dependências do sistema e extensões necessárias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir a pasta de trabalho
WORKDIR /app

# Copiar os ficheiros do projeto
COPY . .

# Instalar dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# Criar a base de dados SQLite se não existir e dar permissões
RUN mkdir -p /tmp && touch /tmp/database.sqlite
RUN chmod -R 777 storage bootstrap/cache /tmp

# Expor a porta que o Render vai utilizar
EXPOSE 10000

# Executar migrações e arrancar o servidor na porta do Render
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}