# WGZ
Web Garapena Zerbitzarian

## PHP
### Intro

Zer den PHP azalpena

### Garapen ingurunea prestatu

Ariketa: 

PHP-n "Hello World" egin Docker eta Visual Code erabiliz

Docker:
- Docker desktop
- Portainer CE: https://docs.portainer.io/start/install-ce
- https://docs.docker.com/engine/install/ubuntu/#install-using-the-convenience-script
  
Docker desktop instalatu: 
- Run Docker in Windows - Setup, Docker Compose, Extensions: https://www.youtube.com/watch?v=cMyoSkQZ41E
- docker run -d -p 8080:80 docker/getting-started
- http://localhost:8080  gida jarraitu

Oharra: Ikasleen baimenak direla eta ezin izan da Docker Desktop instalatu.

codium (visual code open source) addons-ak instalatu daitezke:
- https://vscodium.com/#install

Visual Code:
- addons: 'WSL', 'docker', PHP Tools for VS Code, ...
- https://dev.to/arafatweb/top-10-vs-code-extensions-for-php-developers-in-2024-2m08
- "Install from VSIX" "marketplace-n" sartzeko baimenik ez bait dugu, extensions-ak eskuz instalatuko dira. https://www.vsixhub.com/
  
git
- https://learn.microsoft.com/en-us/windows/wsl/tutorials/wsl-git

git Windows: C:\Users\<user>\.gitconfig
```
[user]
	name = izena
	email = izena@mail.eus
```

github ssh bidez:
- https://docs.github.com/en/authentication/connecting-to-github-with-ssh

git aginduak:
```
git clone ...
git status
git add ..
git commit -am "..."
git pull
git push
```


PHP
- https://marc.it/dockerize-application-with-nginx-and-php8/
- https://medium.com/@tech_18484/deploying-a-php-web-app-with-docker-compose-nginx-and-mariadb-d61a84239c0d

docker-compose.yml:
```
version: '3.9'

services:
  web:
    image: nginx:latest
    ports:
      - '8080:80'
    volumes:
      - ./src:/var/www/html
      - ./default.conf:/etc/nginx/conf.d/default.conf
    links:
      - php-fpm
  php-fpm:
    image: php:8-fpm
    volumes:
      - ./src:/var/www/html
```

default.conf:
```
server {
    index index.php index.html;
    server_name phpfpm.local;
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
    root /var/www/html;
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass php-fpm:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
}
```


```
docker-compose up
docker-compose up -d
docker-compose down

docker logs php-web-1 -f

docker exec -it php-web-1 bash
```

docker volume baimenak aldatu: 
```
sudo chown ubuntu:ubuntu -R src
```

Garapen ingurunerako beste aukerak:
- XAMPP: https://www.apachefriends.org/, MAMP, ...
- visual code studio web: https://code.visualstudio.com/docs/editor/vscode-web
- VirtualBox-en ubuntu server + GUI (https://thelinuxforum.com/articles/919-how-to-install-gui-on-ubuntu-24-04-server)
- VirtualBox erabiliz Ubuntu Desktop birtualizatu eta bertan dena instalatu.
- VirtualBox-en UbuntuServer instalatu eta Visual Studio Code Windows.en. Zerbitzarira konektatzeko VSko remote addon erabili
- https://vscodium.com/#install
  
### PHP tutoriala

- [w3schools](https://www.w3schools.com/php/default.asp)
- https://phptherightway.com/

