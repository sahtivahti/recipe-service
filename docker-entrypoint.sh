#!/usr/bin/env sh

set -e

# Initialize Symfony cache
php bin/console cache:warmup && php bin/console cache:clear && chmod -R 777 var/cache

# Run database migrations
php /app/bin/console doctrine:migrations:migrate --no-interaction

exec "$@"
