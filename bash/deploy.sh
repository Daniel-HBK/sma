#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

# Install Laravel dependencies
echo "Installing Laravel dependencies..."
composer install

# Set up environment variables
echo "Setting up environment variables..."
cp .env.example .env

# Prompt user for database credentials
# read -p "Enter your database name: " db_name
# read -p "Enter your database username: " db_user
# read -p "Enter your database password: " db_password

# Update .env file with user input
# sed -i "s/DB_DATABASE=.*/DB_DATABASE=$db_name/" .env
# sed -i "s/DB_USERNAME=.*/DB_USERNAME=$db_user/" .env
# sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$db_password/" .env

# Generate application key
echo "Generating application key..."
php artisan key:generate

# Run migrations and seeds
echo "Running migrations and seeds..."
php artisan migrate:fresh --seed
# sed -i "s/DB_CONNECTION=.*/DB_CONNECTION=db/" .env

# Refresh routes, config, and cache
php artisan route:clear
php artisan config:clear
php artisan cache:clear

echo $USER
echo "hi"

# Set appropriate permissions
echo "Setting folder permissions..."
sudo chown -R www-data:www-data storage # Update your user if needed...
sudo chown -R www-data:www-data bootstrap/cache # Update your user if needed...
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Start the application
echo "Starting the application..."
php artisan serve &

echo "Application is now up running."





