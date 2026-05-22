#!/bin/sh
set -e

# Run storage link
if [ ! -e "/var/www/public/storage" ]; then
    echo "Creating storage symlink..."
    php /var/www/artisan storage:link --force
fi

# Wait for database connection
echo "Waiting for database connection..."
until php -r "
try {
    \$dbh = new PDO('pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    exit(0);
} catch (PDOException \$e) {
    fwrite(STDERR, 'Database connection failed: ' . \$e->getMessage() . PHP_EOL);
    exit(1);
}
"; do
    echo "Database not ready yet. Retrying in 2 seconds..."
    sleep 2
done
echo "Database is up and running!"

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
