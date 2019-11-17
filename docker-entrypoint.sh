#!/usr/bin/env sh

set -e

# Run database migrations
./bin/console doctrine:migrations:migrate --no-interaction

exec "$@"
