#!/bin/sh
set -e

# Ensure .env exists
if [ ! -f .env ] && [ -f .env.example ]; then
    cp .env.example .env
fi

# Install dependencies if vendor directory is empty or missing
if [ ! -f vendor/autoload.php ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --optimize-autoloader
fi

exec "$@"
