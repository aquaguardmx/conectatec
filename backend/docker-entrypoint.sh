#!/bin/sh
set -e

# Run storage link
if [ ! -e "/var/www/public/storage" ]; then
    echo "Creating storage symlink..."
    php /var/www/artisan storage:link --force
fi

# Check database connection and run migrations with retries
echo "Checking database connection and running migrations..."
MAX_RETRIES=30
RETRY_COUNT=0
MIGRATION_SUCCESS=0

while [ $RETRY_COUNT -lt $MAX_RETRIES ]; do
    if php -r "
    try {
        \$dbh = new PDO('pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
        exit(0);
    } catch (PDOException \$e) {
        exit(1);
    }
    "; then
        echo "Database is up and running!"
        
        # Run migrations
        echo "Running database migrations..."
        if php /var/www/artisan migrate --force 2>&1; then
            echo "Migrations completed successfully!"
            MIGRATION_SUCCESS=1
            break
        else
            echo "Migration failed, retrying..."
            RETRY_COUNT=$((RETRY_COUNT+1))
            sleep 2
        fi
    else
        RETRY_COUNT=$((RETRY_COUNT+1))
        echo "Database not ready yet (Attempt $RETRY_COUNT/$MAX_RETRIES). Retrying in 2 seconds..."
        sleep 2
    fi
done

if [ $MIGRATION_SUCCESS -eq 0 ]; then
    echo "WARNING: Could not run migrations after $MAX_RETRIES attempts. Check database connection."
fi

# Optimize Laravel Cache
echo "Caching configurations..."
php /var/www/artisan config:cache
php /var/www/artisan route:cache
php /var/www/artisan view:cache

# Start Supervisor
echo "Starting supervisor..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
