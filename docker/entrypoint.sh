#!/bin/sh
set -e

cd /var/www/html

# Las variables reales vienen de compose.yaml; .env solo se usa para guardar APP_KEY.
if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ -z "$APP_KEY" ] && ! grep -q '^APP_KEY=.\+' .env; then
    echo "Generando APP_KEY..."
    php artisan key:generate --force
fi

php artisan migrate --force
php artisan optimize

chown -R www-data:www-data storage bootstrap/cache

exec "$@"
