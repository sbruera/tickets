#!/bin/bash
set -e

if [ ! -d "/var/www/html/storage" ]; then
    mkdir -p /var/www/html/storage
fi

if [ ! -d "/var/www/html/bootstrap/cache" ]; then
    mkdir -p /var/www/html/bootstrap/cache
fi

chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

if [ -d "/var/www/html/storage/logs" ]; then
    chown -R www-data:www-data /var/www/html/storage/logs
    chmod -R 775 /var/www/html/storage/logs
fi

if [ -d "/var/www/html/storage/framework" ]; then
    chown -R www-data:www-data /var/www/html/storage/framework
    chmod -R 775 /var/www/html/storage/framework
fi

exec "$@"

