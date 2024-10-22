# Use a imagem oficial do PHP 8.3 FPM
FROM php:8.3-fpm

# Instale as dependências do sistema necessárias
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    zlib1g-dev \
    libicu-dev \
    libxml2-dev \
    libxslt-dev \
    mariadb-client \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mysqli \
        intl \
        gd \
        zip \
        opcache \
        bcmath \
        xsl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure a extensão PDO MySQL
RUN docker-php-ext-enable pdo_mysql

# Instalar o Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Copie o Composer da imagem oficial do Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copie configurações personalizadas do PHP
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Criar a pasta de logs e os arquivos error.log e access.log
RUN mkdir -p /var/www/docker/nginx/log \
    && touch /var/www/docker/nginx/log/error.log /var/www/docker/nginx/log/access.log

# Defina permissões adequadas para a pasta de logs
RUN chmod -R 775 /var/www/docker/nginx/log

# Defina o diretório de trabalho padrão
WORKDIR /var/www

# Exponha a porta 9000 para conexões PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]