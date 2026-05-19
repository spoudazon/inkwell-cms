FROM dunglas/frankenphp:1-php8.5 AS vendor

RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip ca-certificates \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

FROM dunglas/frankenphp:1-php8.5

RUN install-php-extensions opcache intl

WORKDIR /app
COPY --from=vendor /app/vendor ./vendor
COPY . .

RUN php bin/compile-container.php || true

ENV SERVER_NAME=":80"

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
