ARG BASE_PHP_IMAGE=php:8.1-alpine

FROM $BASE_PHP_IMAGE

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql

COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini
RUN chown -R www-data:www-data /usr/local/etc/php/conf.d

WORKDIR /app

COPY . .

ENV DB_HOST="postgres" \
    DB_PORT="5432" \
    DB_DATABASE="somedatabase" \
    DB_USERNAME="someuser" \
    DB_PASSWORD="somepassword"

