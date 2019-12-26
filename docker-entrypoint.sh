#!/usr/bin/env sh

set -e

# Initialize Symfony cache
php /app/bin/console cache:warmup && php /app/bin/console cache:clear && chmod -R 777 /app/var/cache

# Run database migrations
php /app/bin/console doctrine:migrations:migrate --no-interaction

exec "$@"
