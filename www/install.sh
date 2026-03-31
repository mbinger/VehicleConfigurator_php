#!/bin/bash
if [ ! -f .env ]; then
    echo "setup laravel environment"
    cp .env.dev .env
    composer install
    php artisan migrate
    php artisan db:seed
    npm install
    npm run build
    php artisan storage:link
else
    echo ".env existing already"
fi
