#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

# Start the application
echo "Starting the application..."
php artisan serve &

echo "Application is now up running."