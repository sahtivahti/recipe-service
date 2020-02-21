FROM php:7.4.3-fpm-alpine3.10

WORKDIR /app

RUN apk add --update \
    make \
    gcc \
    g++ \
    autoconf \
    rabbitmq-c \
    rabbitmq-c-dev \
  && pecl install amqp \
  && docker-php-ext-install pdo_mysql \
  && docker-php-ext-enable amqp

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

COPY composer.json composer.lock symfony.lock /app/

RUN composer install --no-scripts

COPY . .

RUN composer run auto-scripts

RUN chmod +x /app/bin/console /app/bin/phpunit

ENTRYPOINT ["./docker-entrypoint.sh"]
