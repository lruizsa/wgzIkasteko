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

Oharra: Ikasleen baimenak direla eta ezin izan da Docker Desktop instalatu. Konpontzeko, erabiltzailea "docker" talde lokalean sartu.

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

git linux:
```
git config --global user.name "John Doe"
git config --global user.email johndoe@example.com
```

github ssh bidez:
- https://docs.github.com/en/authentication/connecting-to-github-with-ssh/adding-a-new-ssh-key-to-your-github-account
- https://docs.github.com/en/authentication/connecting-to-github-with-ssh
```
$ ssh-keygen -t ed25519 -C "your_email@example.com"

$ cat ~/.ssh/id_ed25519.pub 
ssh-ed25519 AAA.............jaEhduMSb your_email@example.com
```

git aginduak:
```
git clone git@github.com:...
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

#### Ariketa: HTML-ko taula array-ak erabiliz

HTML-ko taula batean inprimantu, matrize batean ([multidimensional array](https://www.w3schools.com/pHP/php_arrays_multidimensional.asp)) batean gordeak dauden datuak erabiliz.

```php
<?php
// Multidimensional array-a definitu
$data = [
    ['Izena' => 'Ane', 'Adina' => 25, 'Hiriburua' => 'Bilbao'],
    ['Izena' => 'Iñaki', 'Adina' => 30, 'Hiriburua' => 'Donostia'],
    ['Izena' => 'Marta', 'Adina' => 22, 'Hiriburua' => 'Gasteiz'],
];

// HTML-taula sortu
echo "<table border='1'>";

// Taularen goiburua
echo "<tr>
        <th>Izena</th>
        <th>Adina</th>
        <th>Hiriburua</th>
      </tr>";

// Datuak taulan inprimatu
foreach ($data as $lerroa) {
    echo "<tr>
            <td>{$lerroa['Izena']}</td>
            <td>{$lerroa['Adina']}</td>
            <td>{$lerroa['Hiriburua']}</td>
          </tr>";
}

echo "</table>";
?>
```

Aldaera: HTML-ko taula bat itzuli beharrean, JSON formatuan itzuli dezala.

```php
<?php
// Multidimensional array-a definitu
$data = [
    ['Izena' => 'Ane', 'Adina' => 25, 'Hiriburua' => 'Bilbao'],
    ['Izena' => 'Iñaki', 'Adina' => 30, 'Hiriburua' => 'Donostia'],
    ['Izena' => 'Marta', 'Adina' => 22, 'Hiriburua' => 'Gasteiz'],
];

// JSON formatuan konbertitu
$jsonData = json_encode($data, JSON_PRETTY_PRINT);

// JSON-a inprimatu
header('Content-Type: application/json');
echo $jsonData;
?>
```

#### Ariketa: 3Dko irudia sortu array batean gordeta dauden puntuekin

Aukera 1: PHP-k irudia sortu eta bidali (PHPko GD liburutegia erabili adibidez)
Aukera 2: PHP-k puntuak JSON moduan bidali eta JS liburutegi bat erabiz irudia erakutsi