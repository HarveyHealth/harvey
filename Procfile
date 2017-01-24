# heroku
web: vendor/bin/heroku-php-apache2 public
worker: php artisan queue:listen --timeout=1200 --tries=3 --sleep=3
