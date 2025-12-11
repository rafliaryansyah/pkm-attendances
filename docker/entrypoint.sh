#!/bin/sh

set -e

echo "Starting Laravel application setup..."

# Wait for database to be ready (optional, uncomment if needed)
# echo "Waiting for database..."
# while ! nc -z $DB_HOST ${DB_PORT:-3306}; do
#   sleep 1
# done
# echo "Database is ready!"

# Clear and cache config
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations (optional, uncomment if you want auto-migration)
# echo "Running database migrations..."
# php artisan migrate --force --no-interaction

# Link storage (if not already linked)
if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link
fi

echo "Application setup completed!"

# Execute the main command
exec "$@"
