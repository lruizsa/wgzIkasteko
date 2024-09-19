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
    ['Izena' => 'Ane', 'Adina' => 25, 'Hiriburua' => 'Bilbo'],
    ['Izena' => 'Iñaki', 'Adina' => 30, 'Hiriburua' => 'Donostia'],
    ['Izena' => 'Koxme', 'Adina' => 22, 'Hiriburua' => 'Iruñea'],
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

beste adibide bat:

```php
<!DOCTYPE html>
<html>
<head>
    <title>Ikasleen Datuak Taula batean</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Ikasleen Datuak</h2>

<?php
// Matrize multidimentsional bat definitu
$ikasleak = array(
    array("Izen-abizenak" => "Jon Aizpuru", "Adina" => 22, "Emaitza" => 90),
    array("Izen-abizenak" => "Maite Ibarrola", "Adina" => 21, "Emaitza" => 85),
    array("Izen-abizenak" => "Ane Olaizola", "Adina" => 23, "Emaitza" => 88),
    array("Izen-abizenak" => "Xabier Elizondo", "Adina" => 22, "Emaitza" => 92)
);

?>

<!-- HTMLko taula sortu -->
<table>
    <tr>
        <th>Izen-abizenak</th>
        <th>Adina</th>
        <th>Emaitza</th>
    </tr>

    <?php
    // Datuak matrizeatik ateratzen eta taulara gehitzen
    foreach ($ikasleak as $ikaslea) {
        echo "<tr>";
        echo "<td>" . $ikaslea["Izen-abizenak"] . "</td>";
        echo "<td>" . $ikaslea["Adina"] . "</td>";
        echo "<td>" . $ikaslea["Emaitza"] . "</td>";
        echo "</tr>";
    }
    ?>

</table>

</body>
</html>

```

Aldaera: HTML-ko taula bat itzuli beharrean, JSON formatuan itzuli dezala.

'ikasleak.php'
```php
<?php
// Matrize multidimentsional bat definitu
$ikasleak = array(
    array("Izen-abizenak" => "Jon Aizpuru", "Adina" => 22, "Emaitza" => 90),
    array("Izen-abizenak" => "Maite Ibarrola", "Adina" => 21, "Emaitza" => 85),
    array("Izen-abizenak" => "Ane Olaizola", "Adina" => 23, "Emaitza" => 88),
    array("Izen-abizenak" => "Xabier Elizondo", "Adina" => 22, "Emaitza" => 92)
);

// JSON formatura bihurtu eta itzuli
header('Content-Type: application/json');
echo json_encode($ikasleak, JSON_PRETTY_PRINT);
?>


```

Orain, JS erabiliz JSON-ari formatua eman Taula bihurtuz:

'ikasleak.html'
```html
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ikasleen Datuak Taulan</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Ikasleen Datuak</h2>
<!-- Taula hemen sortuko dugu -->
<table id="ikasleTaula">
    <tr>
        <th>Izen-abizenak</th>
        <th>Adina</th>
        <th>Emaitza</th>
    </tr>
</table>

<script>
// Fetch metodoa erabiliz PHPtik JSON datuak jaso
fetch('ikasleak.php')
    .then(response => response.json())  // JSON erantzuna lortu
    .then(ikasleak => {
        // Taula elementua eskuratu
        const taula = document.getElementById("ikasleTaula");

        // JSON datuak erabiliz taula sortu
        ikasleak.forEach(function(ikaslea) {
            // Lerro berri bat sortu
            const errenkada = document.createElement("tr");

            // Izen-abizenak
            const izenAbizenak = document.createElement("td");
            izenAbizenak.textContent = ikaslea["Izen-abizenak"];
            errenkada.appendChild(izenAbizenak);

            // Adina
            const adina = document.createElement("td");
            adina.textContent = ikaslea["Adina"];
            errenkada.appendChild(adina);

            // Emaitza
            const emaitza = document.createElement("td");
            emaitza.textContent = ikaslea["Emaitza"];
            errenkada.appendChild(emaitza);

            // Lerroa taulara gehitu
            taula.appendChild(errenkada);
        });
    })
    .catch(error => console.error('Errorea datuak kargatzean:', error));  // Akatsen tratamendua
</script>

</body>
</html>
```

document.createElement eta errenkada.appendChild erabili beharrean beste modu batetara (hau JavaScript da!):
```javascript
...
<script>
// Fetch metodoa erabiliz PHPtik JSON datuak jaso
fetch('ikasleak.php')
    .then(response => response.json())  // JSON erantzuna lortu
    .then(ikasleak => {
        // Taula elementua eskuratu
        const taula = document.getElementById("ikasleTaula");

        // JSON datuak erabiliz taula sortu
        let taulaEdukia = "";  // Taularako lerroak gordetzeko string bat
        ikasleak.forEach(function(ikaslea) {
            // Lerroa string moduan sortu
            taulaEdukia += `
                <tr>
                    <td>${ikaslea["Izen-abizenak"]}</td>
                    <td>${ikaslea["Adina"]}</td>
                    <td>${ikaslea["Emaitza"]}</td>
                </tr>
            `;
        });

        // String moduan sortutako taula lerroak taulan txertatu
        taula.innerHTML += taulaEdukia;
    })
    .catch(error => console.error('Errorea datuak kargatzean:', error));  // Akatsen tratamendua
</script>
...
```


http://localhost:8080/ikasleak.html


#### Ariketa (aurreratua): 3Dko irudia sortu array batean gordeta dauden puntuekin

- Aukera 1: PHP-k irudia sortu eta bidali (PHPko liburutegia erabili behar da, horrek 'docker' konfigurazioa aldatzea eskatuko du)
- Aukera 2: PHP-k puntuak JSON moduan bidali eta JS liburutegi bat erabiz irudia erakutsi