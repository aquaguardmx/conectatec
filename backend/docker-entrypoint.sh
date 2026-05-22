#!/bin/sh
set -e

# Run storage link
if [ ! -e "/var/www/public/storage" ]; then
    echo "Creating storage symlink..."
    php /var/www/artisan storage:link --force
fi

# Check database connection (max 5 retries to prevent blocking health checks)
echo "Checking database connection..."
MAX_RETRIES=5
RETRY_COUNT=0
DB_CONNECTED=0

while [ $RETRY_COUNT -lt $MAX_RETRIES ]; do
    if php -r "
    try {
        \$dbh = new PDO('pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
        exit(0);
    } catch (PDOException \$e) {
        fwrite(STDERR, 'Database connection failed: ' . \$e->getMessage() . PHP_EOL);
        exit(1);
    }
    "; then
        echo "Database is up and running!"
        DB_CONNECTED=1
        break
    fi
    RETRY_COUNT=$((RETRY_COUNT+1))
    echo "Database not ready yet (Attempt $RETRY_COUNT/$MAX_RETRIES). Retrying in 2 seconds..."
    sleep 2
done

if [ $DB_CONNECTED -eq 1 ]; then
    # Run migrations
    echo "Running database migrations..."
    php /var/www/artisan migrate --force
else
    echo "WARNING: Could not connect to database after $MAX_RETRIES attempts. Skipping migrations for now so Nginx can start."
fi

# Optimize Laravel Cache
echo "Caching configurations..."
php /var/www/artisan config:cache
php /var/www/artisan route:cache
php /var/www/artisan view:cache

# Start Supervisor
echo "Starting supervisor..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
