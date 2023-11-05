ARG BASE_PHP_IMAGE=php:8.1-alpine

FROM $BASE_PHP_IMAGE

RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable pdo_mysql

# TODO: check if mysqli can be installed without installing pdo & pdo_mysql
# TODO: add pcntl

COPY custom.ini /usr/local/etc/php/conf.d/custom.ini
RUN chown -R www-data:www-data /usr/local/etc/php/conf.d

WORKDIR /app

COPY . .

ENV DB_HOST="mysql" \
    DB_DATABASE="testdb" \
    DB_USERNAME="testuser" \
    DB_PASSWORD="mysqlpassword1"
