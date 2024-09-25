# laravel

## install

https://laravel.com/docs/11.x/installation


docker-compose.yml
```
...
  # PHP
  php:
    container_name: php
    build:
      dockerfile: Dockerfile-php
      context: .
    ports:
      # laravel
      - '8000:8000'
...
```

```
composer create-project laravel/laravel example-app
cd example-app/
php artisan serve --host=0.0.0.0 --port=8000
```
edo
```
laravel new example-app
cd example-app/
php artisan serve --host=0.0.0.0 --port=8000
```

Errore ematen badu, ```example-app/.env``` fitxategia aldatu eta 
```
DB_DATABASE=example_app
DB_USERNAME=root
DB_PASSWORD=root
# edo beste batzuk
```
aldagai haue ```Dockerfile-php``` barruan jarri daitezke ```ENV DB_PASSWORD=root``` moduan

ondoren:
```
php artisan migrate
```

```
php artisan serve --host=0.0.0.0 --port=8000
```
http://localhost:8000



