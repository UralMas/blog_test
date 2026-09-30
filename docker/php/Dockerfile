FROM php:8.5-fpm-alpine

# Необходимые расширения
RUN apk add --no-cache \
        oniguruma-dev \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mbstring

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Кастомный php.ini
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html

EXPOSE 9000
CMD ["php-fpm"]