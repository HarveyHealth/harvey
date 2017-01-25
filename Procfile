# heroku
web: vendor/bin/heroku-php-nginx public/
worker: php artisan queue:listen --timeout=1200 --tries=5 --sleep=3
