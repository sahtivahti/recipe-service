#!/usr/bin/env sh

set -e

# Setup Symfony cache
php /app/bin/console cache:warmup \
    && php /app/bin/console assets:install \
    && php /app/bin/console cache:clear \
    && chmod -R 777 /app/var

# Run database migrations
php /app/bin/console doctrine:migrations:migrate --no-interaction

exec "$@"
