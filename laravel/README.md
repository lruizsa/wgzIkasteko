# laravel

## install

https://laravel.com/docs/11.x/installation

### PHP and the Laravel Installer

- https://laravel.com/docs/11.x/installation#installing-php

```bash
/bin/bash -c "$(curl -fsSL https://php.new/install/linux)"
```
```bash
 Success! ─────────────────────────────────────────────────────────
│ php, composer, and laravel have been installed successfully.
│ Please restart your terminal or run 'source /home/ubuntu/.bashrc' to update your PATH.

```
```bash
composer global require laravel/installer
```

DB:
```bash
sudo apt update
sudo apt install mariadb-server
sudo mysql_secure_installation
```
```bash
sudo mariadb-secure-installation
```

github:

*.gitignore-tik .env lerroa kendu (git add .env)

github clone:

```bash
git clone ... laravelapp

cd laravel-app
composer install
php artisan migrate
php artisan serve
```
