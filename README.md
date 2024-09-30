# WGZ
Web Garapena Zerbitzarian

# PHP
## Intro

Zer den PHP azalpena

## Garapen ingurunea prestatu

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

.gitignore (fitxategi honetan jarri git-etik kanpo dauden direktorio eta fitxategiak)


PHP
- https://marc.it/dockerize-application-with-nginx-and-php8/
- https://medium.com/@tech_18484/deploying-a-php-web-app-with-docker-compose-nginx-and-mariadb-d61a84239c0d

docker-compose.yml:
```yml
services:
  web:
    image: nginx:latest
    ports:
      - '8080:80'
    volumes:
      - ./src:/var/www/html
      - ./default.conf:/etc/nginx/conf.d/default.conf

  php:
    image: php:8-fpm
    volumes:
      - ./src:/var/www/html
```

default.conf:
```
server {
    listen 80;
    listen [::]:80;    
    
    index index.php index.html;
    server_name localhost;
    
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
    
    root /var/www/html;
    
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
}
```

```
docker-compose up
```

'src' volume baimenak aldatu: 
```
sudo chown ubuntu:ubuntu -R src 
```


```
docker-compose up
docker-compose up -d
docker-compose down

docker logs php-web-1 -f

docker exec -it php-web-1 bash
```


build berriro egiteko Dockerfile aldaten bada:
```
docker-compose build
```

Garapen ingurunerako beste aukerak:
- XAMPP: https://www.apachefriends.org/, MAMP, ...
- visual code studio web: https://code.visualstudio.com/docs/editor/vscode-web
- VirtualBox-en ubuntu server + GUI (https://thelinuxforum.com/articles/919-how-to-install-gui-on-ubuntu-24-04-server)
- VirtualBox erabiliz Ubuntu Desktop birtualizatu eta bertan dena instalatu.
- VirtualBox-en UbuntuServer instalatu eta Visual Studio Code Windows.en. Zerbitzarira konektatzeko VSko remote addon erabili
- https://vscodium.com/#install

## composer

gomendatutako aurrebaldintza: PHP OOP eta namespace-ak jakitea

nola erabili docker-ekin?
- https://getcomposer.org/doc/00-intro.md#docker-image
- https://hub.docker.com/_/composer
- https://medium.com/@lukaspereyra8/docker-compose-php-composer-the-missing-vendors-folder-issue-66faa5475c59

## HTTPS

- nginx konfiguratu HTTPS erabiltzeko. docker
- certbot

## Database

composer erabiliz

## PHP tutoriala

- [w3schools](https://www.w3schools.com/php/default.asp)
- https://phptherightway.com/

### Ariketa: HTML-ko taula array-ak erabiliz

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


### Ariketa (aurreratua): 3Dko irudia sortu array batean gordeta dauden puntuekin

- Aukera 1: PHP-k irudia sortu eta bidali (PHPko liburutegia erabili behar da, horrek 'docker' konfigurazioa aldatzea eskatuko du)
- Aukera 2: PHP-k puntuak JSON moduan bidali eta JS liburutegi bat erabiz irudia erakutsi


### funtzioak reference value

```php
<?php

function gehitu_balioa($balioa) {
    $balioa += 5;
    return $balioa;
}

function add_five_value($value) {
    $value += 5;
}


function add_five(&$value) {
    $value += 5;
}

$num = 2;

add_five_value($num);
echo $num;

echo "<br>";

add_five($num);
echo $num;

$num = 2;

echo "<br>";
$num = gehitu_balioa($num);
echo $num;

```

### Ariketa: Forms and validation

formulariotik bidaltzen den informazioa (GET eta POST) erabili aurretik balidatu. Formularioko elementu ezberdinak probatu (Text Fields, Radio Buttons, textarea, select, email, password, ...)


https://tryphp.w3schools.com/showphp.php?filename=demo_form_validation_complete:
```php
<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  

<?php
// define variables and set to empty values
$nameErr = $emailErr = $genderErr = $websiteErr = "";
$name = $email = $gender = $comment = $website = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $name = test_input($_POST["name"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
      $nameErr = "Only letters and white space allowed";
    }
  }
  
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
    }
  }
    
  if (empty($_POST["website"])) {
    $website = "";
  } else {
    $website = test_input($_POST["website"]);
    // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
      $websiteErr = "Invalid URL";
    }
  }

  if (empty($_POST["comment"])) {
    $comment = "";
  } else {
    $comment = test_input($_POST["comment"]);
  }

  if (empty($_POST["gender"])) {
    $genderErr = "Gender is required";
  } else {
    $gender = test_input($_POST["gender"]);
  }
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<h2>PHP Form Validation Example</h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  Name: <input type="text" name="name" value="<?php echo $name;?>">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>
  E-mail: <input type="text" name="email" value="<?php echo $email;?>">
  <span class="error">* <?php echo $emailErr;?></span>
  <br><br>
  Website: <input type="text" name="website" value="<?php echo $website;?>">
  <span class="error"><?php echo $websiteErr;?></span>
  <br><br>
  Comment: <textarea name="comment" rows="5" cols="40"><?php echo $comment;?></textarea>
  <br><br>
  Gender:
  <input type="radio" name="gender" <?php if (isset($gender) && $gender=="female") echo "checked";?> value="female">Female
  <input type="radio" name="gender" <?php if (isset($gender) && $gender=="male") echo "checked";?> value="male">Male
  <input type="radio" name="gender" <?php if (isset($gender) && $gender=="other") echo "checked";?> value="other">Other  
  <span class="error">* <?php echo $genderErr;?></span>
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>

<?php
echo "<h2>Your Input:</h2>";
echo $name;
echo "<br>";
echo $email;
echo "<br>";
echo $website;
echo "<br>";
echo $comment;
echo "<br>";
echo $gender;
?>

</body>
</html>
```

### Ariketa: berbideraketa (PHP redirection)

PHP batetara eskaera egin ondoren, erantzuna beste helbide batetara berbideratu.

### Ariketa: Login

'PHP session' erabilita 'login' bat garatu. Babestutako orrialdeak atzitzeko erabiltzailea logeatua egon behar du. 'logout' egiteko aukera egongo da. Pasahitza hash bezala gordeko da.

### PHP Database

- https://www.w3schools.com/php/php_mysql_intro.asp
- https://en.wikipedia.org/wiki/Create,_read,_update_and_delete
- Docker: Nginx, PHP, mariadb: https://blog.jonsdocs.org.uk/2023/04/08/using-docker-for-a-php-mariadb-and-nginx-project/

docker-compose.yml
```yml
services:
  # nginx
  web:
    container_name: web
    image: nginx:latest
    ports:
      - '8080:80'
    links:
      - 'php'      
    volumes:
      - ./src:/var/www/html
      - ./default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - php

  # PHP
  php:
    container_name: php
    build:
      dockerfile: Dockerfile-php
      context: .
    volumes:
      - ./src:/var/www/html
    depends_on:
      - mariadb

  # MariaDB Service
  mariadb:
    container_name: db
    image: mariadb:10.9
    ports:
      - '8306:3306'    
    environment:
      MYSQL_ROOT_PASSWORD: root
      #MYSQL_DATABASE: mydatabase
    volumes:
      - './mysqldata:/var/lib/mysql'

  # Adminer
  adminer:
    image: adminer:latest
    container_name: adminer
    environment:
      ADMINER_DEFAULT_SERVER: db
    restart: always
    ports:
      - 7777:8080  

# Volumes
volumes:
  mysqldata:
```

Dockerfile-php:
```
FROM php:8.1-fpm

# Installing dependencies for the PHP modules
RUN apt-get update && \
    apt-get install -y zip curl libcurl3-dev libzip-dev libpng-dev libonig-dev libxml2-dev
    # libonig-dev is needed for oniguruma which is needed for mbstring

# Installing additional PHP modules
RUN docker-php-ext-install curl gd mbstring mysqli pdo pdo_mysql xml

# Install and configure ImageMagick
RUN apt-get install -y libmagickwand-dev
RUN pecl install imagick
RUN docker-php-ext-enable imagick
RUN apt-get purge -y libmagickwand-dev

# Install Composer so it's available
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
```

container-ak ikusteko:
```
docker ps
```

mariadb container-era sartzeko:
```
docker exec -it db bash
root@6691667cf708:/# mysql -u root -p
```

edo:
```
sudo apt update
sudo apt install mysql-client

# mysql 8306 portuan entzuten dago
mysql -u root -h 127.0.0.1 -P 8306 -p
```
edo Adminer erabiliz:
```
http://localhost:7777
```

db.php: (https://www.w3schools.com/php/php_mysql_connect.asp)
```php
<?php
$servername = "db";  // docker-compose.yml begiratu
$username = "root";
$password = "root";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
```

### Ariketa: Database 'CRUD operations' egin 

Create, Read, Update, Delete

Create Database:
```php
<?php
$servername = "localhost";
$username = "username";
$password = "password";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE myDB";
if ($conn->query($sql) === TRUE) {
  echo "Database created successfully";
} else {
  echo "Error creating database: " . $conn->error;
}

$conn->close();
?>
```

### Ariketa: login 2

Aurreko login ariketari erabiltzaile berriak erregistratzeko aukera eman (erregistroan erabiltzailearen argazkia ere jarri). Honek informazioa modu iraunkorrean gordetzea eskatzen du (datu-base batean adibidez).
