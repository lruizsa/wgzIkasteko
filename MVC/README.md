# MVC Eredua PHP-n

## MVC adibidea

(ChatGPT erabiliz: Eskaera: MVC eredua jarraitzen duen PHP adibide bat behar dut, namespaceak eta datubase bat erabiltzen duena ikasleei erakusteko)

MVC eredua (Model-View-Controller) oso erabilia da garapen web aplikazioetan, eta PHP-rekin lan egitean, ohikoa da framework-ak erabiltzea, hala nola Laravel edo Symfony. Hala ere, framework-ik gabe ere MVC eredua ezarri daiteke PHPn. Hemen adibide sinple bat ikusiko dugu, namespace-ak eta datu-base bat erabiliz, zure ikasleei nola antolatu erakusteko.

### 1. Direktorio egitura:

```bash
/mvc-example
    /app
        /controllers
            HomeController.php
        /models
            User.php
        /views
            home.php
    /config
        config.php
    /public
        index.php
    /vendor
```

### 2. Datu-basearen konfigurazioa (MySQL adibidean)

Sortu config/config.php fitxategia, datu-basearen konfigurazioarekin:

```php
<?php

namespace Config;

use PDO;

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $host = 'localhost';
        $dbname = 'my_database';
        $username = 'root';
        $password = '';

        $this->connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
```

### 3. Modeloa: User eredua

Sortu app/models/User.php fitxategia:

```php
<?php

namespace Models;

use Config\Database;

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllUsers() {
        $stmt = $this->db->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
```

### 4. Kontrolatzailea: HomeController

Sortu app/controllers/HomeController.php fitxategia:

```php
<?php

namespace Controllers;

use Models\User;

class HomeController {
    public function index() {
        $userModel = new User();
        $users = $userModel->getAllUsers();

        // Igo erabiltzaileak ikusteko
        require_once __DIR__ . '/../views/home.php';
    }
}
```

### 5. Bista: home.php

Sortu app/views/home.php fitxategia:

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
</head>
<body>
    <h1>User List</h1>
    <ul>
        <?php foreach ($users as $user): ?>
            <li><?php echo htmlspecialchars($user['name']); ?> - <?php echo htmlspecialchars($user['email']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
```

### 6. index.php: Sarrera puntua

Sortu public/index.php fitxategia:

```php
<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';

use Controllers\HomeController;

// Url-aren arabera, kontrolatzaile ezberdinak bideratuko dira
$controller = new HomeController();
$controller->index();
```

### 7. Composer autoload (namespace-ak erabiltzeko)

composer.json fitxategia sortu erro karpetan, autoload ezartzeko:

```json
{
    "autoload": {
        "psr-4": {
            "Controllers\\": "app/controllers/",
            "Models\\": "app/models/",
            "Config\\": "config/"
        }
    }
}
```

Ondoren, exekutatu composer dump-autoload komandoa terminalean.

### 8. Datu-basea

Datu-base sinple bat ere sortu behar duzu erabiltzaileak gordetzeko. Adibidez, MySQLn hurrengo komandoa erabiliz:

```sql
CREATE DATABASE my_database;
USE my_database;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
);

INSERT INTO users (name, email) VALUES ('Jon', 'jon@example.com'), ('Ane', 'ane@example.com');
```

Amaiera

Hau adibide sinple bat da, namespace-ak eta datu-basea erabiltzen dituen PHP MVC eredua erakusteko. Aplikazioa arakatzailean exekutatzeko, sartu public/index.php URLan. Honek erakutsiko du datu-basetik ateratako erabiltzaileen zerrenda.
