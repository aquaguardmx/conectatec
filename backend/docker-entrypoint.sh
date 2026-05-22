#!/bin/sh
set -e

# Run storage link
if [ ! -e "/var/www/public/storage" ]; then
    echo "Creating storage symlink..."
    php /var/www/artisan storage:link --force
fi

# Run migrations
echo "Running database migrations..."
php /var/www/artisan migrate --force

# Optimize Laravel Cache
echo "Caching configurations..."
php /var/www/artisan config:cache
php /var/www/artisan route:cache
php /var/www/artisan view:cache

# Start Supervisor
echo "Starting supervisor..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
