#!/bin/bash

set -e

echo "🚂 Starting Laravel application on Railway..."
echo "PORT: ${PORT:-8080}"

# Use Railway's PORT or default to 8080
export PORT=${PORT:-8080}

# Process nginx config template with PORT
envsubst '${PORT}' < /etc/nginx/http.d/default.conf.template > /etc/nginx/http.d/default.conf
echo "✓ Nginx configured to listen on port $PORT"

# Wait for database to be ready if DATABASE_URL is set
if [ ! -z "$DATABASE_URL" ]; then
    echo "⏳ Waiting for database..."
    
    # Extract host and port from DATABASE_URL
    DB_HOST=$(echo $DATABASE_URL | sed -n 's/.*@\([^:]*\).*/\1/p')
    DB_PORT=$(echo $DATABASE_URL | sed -n 's/.*:\([0-9]*\)\/.*/\1/p')
    
    if [ ! -z "$DB_HOST" ] && [ ! -z "$DB_PORT" ]; then
        timeout=30
        while ! nc -z $DB_HOST $DB_PORT 2>/dev/null; do
            timeout=$((timeout - 1))
            if [ $timeout -le 0 ]; then
                echo "⚠️  Database connection timeout, proceeding anyway..."
                break
            fi
            sleep 1
        done
        echo "✓ Database is ready!"
    fi
fi

# Generate application key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:generate" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force --no-interaction
fi

# Clear and optimize Laravel
echo "⚡ Optimizing application..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:optimize

# Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force --no-interaction || {
    echo "⚠️  Migration failed, but continuing..."
}

# Link storage if not already linked
if [ ! -L /var/www/html/public/storage ]; then
    echo "🔗 Linking storage..."
    php artisan storage:link || {
        echo "⚠️  Storage link failed, but continuing..."
    }
fi

# Set final permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

echo "✅ Application setup completed!"
echo "🌐 Server will be available on port $PORT"

# Execute the main command
exec "$@"
