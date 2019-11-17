#!/usr/bin/env sh

set -e

# Run database migrations
php /app/bin/console doctrine:migrations:migrate --no-interaction

exec "$@"
