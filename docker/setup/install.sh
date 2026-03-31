#!/bin/bash

if [ ! -f .env ]; then
    echo "setup laravel environment"
    cp .env.dev .env
    composer install
    npm install
    npm run build
    php artisan storage:link
	
	echo "wait for database..."
	timeout 60s bash -c 'until echo > /dev/tcp/mysql/3306; do sleep 1; done'
	
    php artisan migrate
    php artisan db:seed
else
    echo ".env existing already"
fi

echo "APPLICATION READY"
tail -f /dev/null