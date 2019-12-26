FROM php:7.4.1-fpm-alpine3.10

WORKDIR /app

RUN apk update && docker-php-ext-install pdo_mysql

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

COPY composer.json composer.lock symfony.lock /app/

RUN composer install --no-scripts

COPY . .

RUN composer run auto-scripts

RUN chmod +x /app/bin/console /app/bin/phpunit
RUN php bin/console cache:warmup && php bin/console cache:clear && chmod -R 777 var/cache

ENTRYPOINT ["./docker-entrypoint.sh"]
