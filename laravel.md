# laravel

## install

https://laravel.com/docs/11.x/installation

[docker-compose.yml](docker-compose/docker-compose.yml)

[Dockerfile-php](docker-compose/Dockerfile-php)

```
docker exec -it php bash

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
DB_CONNECTION=mariadb
DB_HOST=db
...
DB_USERNAME=root
DB_PASSWORD=root
```

ondoren:
```
php artisan migrate
```

```
php artisan serve --host=0.0.0.0 --port=8000
```
http://localhost:8000


## laravel breeze

https://laravel.com/docs/11.x/starter-kits#laravel-breeze

```
laravel new laravel-app-breeze
```

http://localhost:8000/login

http://localhost:8000/register



