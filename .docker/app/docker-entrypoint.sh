#!/bin/sh
set -e

# Wait for the database to be ready
until nc -z -v -w30 db 5432
do
  echo "Waiting for database connection..."
  # wait for 5 seconds before check again
  sleep 5
done

# Run artisan commands...
php artisan key:generate
php artisan migrate:fresh --seed
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan test

service cron start

# Start php-fpm
exec php-fpm