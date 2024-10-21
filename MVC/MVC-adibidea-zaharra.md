# MVC Eredua PHP-n

## MVC adibidea

(ChatGPT erabiliz: Eskaera: MVC eredua jarraitzen duen PHP adibide bat behar dut iksaleei erakusteko)

MVC (Model-View-Controller) arkitektura eredua erabiltzen duen PHP adibide sinple bat. Erabiltzaileak datu bat bidaltzen duen formulario bat eta datu horiek erakusteko orrialde bat izango ditu.

1. Proiektuko Egitura
```bash
/my_mvc_app
    /controllers
        UserController.php
    /models
        User.php
    /views
        user_form.php
        user_list.php
    /public
        index.php
```
2. Modeloa: User.php

```php
<?php
// models/User.php

class User {
    private $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }
}
```
3. Kontroladorea: UserController.php

```php
<?php
// controllers/UserController.php

require_once '../models/User.php';

class UserController {
    private $users = [];

    public function addUser($name) {
        $user = new User($name);
        $this->users[] = $user;
    }

    public function getUsers() {
        return $this->users;
    }
}
```
   
4. Ikuslea: user_form.php

```php
<!-- views/user_form.php -->
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Erabiltzailea Gehitu</title>
</head>
<body>
    <h1>Erabiltzailea Gehitu</h1>
    <form action="index.php" method="POST">
        <input type="text" name="name" placeholder="Sartu izena" required>
        <button type="submit">Gehitu</button>
    </form>
</body>
</html>
```

5. Ikuslea: user_list.php

```php
<!-- views/user_list.php -->
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Erabiltzaileen Zerrenda</title>
</head>
<body>
    <h1>Erabiltzaileen Zerrenda</h1>
    <ul>
        <?php foreach ($users as $user): ?>
            <li><?php echo htmlspecialchars($user->getName()); ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="index.php">Itzuli formularioara</a>
</body>
</html>
```

6.Sarrera Puntua: index.php 

```php
<?php
// public/index.php

require_once '../controllers/UserController.php';

session_start();

$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $userController->addUser($_POST['name']);
    $_SESSION['users'] = $userController->getUsers();
}

$users = isset($_SESSION['users']) ? $_SESSION['users'] : [];

if (empty($users)) {
    include '../views/user_form.php';
} else {
    include '../views/user_list.php';
}
```

Azalpena

- Modeloa (User): Datuak (izena) kudeatzen ditu.
- Kontroladorea (UserController): Erabiltzaileen izenak gehitzen eta lortzen ditu.
- Ikusleak (user_form.php eta user_list.php): Datuak sartzeko eta erakusteko orrialdeak.

Sarrera Puntua (index.php): Erabiltzailearen interakzioak kudeatzen ditu.

## Datu basea erabiliz

(ChatGPT: eta datu base bat erabiliz? adibidez mariadb)

Proiektu egitura:
```bash
/my_mvc_app
    /controllers
        UserController.php
    /models
        User.php
    /views
        user_form.php
        user_list.php
    /public
        index.php
    /config
        database.php

```

1. Datu Basea Konfigurazioa: database.php
```php
<?php
// config/database.php

$host = 'localhost';
$db   = 'your_database_name';
$user = 'your_username';
$pass = 'your_password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

```

2. Modeloa: User.php
```php
<?php
// models/User.php

require_once '../config/database.php';

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($name) {
        $stmt = $this->pdo->prepare("INSERT INTO users (name) VALUES (:name)");
        $stmt->execute(['name' => $name]);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }
}

```

3. Kontroladorea: UserController.php
```php
<?php
// controllers/UserController.php

require_once '../models/User.php';

class UserController {
    private $userModel;

    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

    public function addUser($name) {
        $this->userModel->create($name);
    }

    public function getUsers() {
        return $this->userModel->getAll();
    }
}

```

4. Ikuslea: user_form.php
```html
<!-- views/user_form.php -->
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Erabiltzailea Gehitu</title>
</head>
<body>
    <h1>Erabiltzailea Gehitu</h1>
    <form action="index.php" method="POST">
        <input type="text" name="name" placeholder="Sartu izena" required>
        <button type="submit">Gehitu</button>
    </form>
</body>
</html>

```

5. Ikuslea: user_list.php
```php
<!-- views/user_list.php -->
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Erabiltzaileen Zerrenda</title>
</head>
<body>
    <h1>Erabiltzaileen Zerrenda</h1>
    <ul>
        <?php foreach ($users as $user): ?>
            <li><?php echo htmlspecialchars($user['name']); ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="index.php">Itzuli formularioara</a>
</body>
</html>

```

6. Sarrera Puntua: index.php
```php
<?php
// public/index.php

require_once '../config/database.php';
require_once '../controllers/UserController.php';

session_start();

$userController = new UserController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $userController->addUser($_POST['name']);
}

$users = $userController->getUsers();

if (empty($users)) {
    include '../views/user_form.php';
} else {
    include '../views/user_list.php';
}

```

7. Datu Basea Sortzea
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

```

Azalpena

- Datu Basea Konfigurazioa: database.php fitxategiak datu-basearen konexioa kudeatzen du.
- Modeloa (User): User modeloak datu-basean erabiltzaileak sortu eta irakurri ditu.
- Kontroladorea (UserController): Erabiltzaileen datuak kudeatzen ditu.
- Ikusleak (user_form.php eta user_list.php): Formularioa eta erabiltzaileen zerrenda erakusten ditu.
- Sarrera Puntua (index.php): Erabiltzailearen interakzioak kudeatzen ditu.

## Proiektua handitu. Modelo eta kontroladore gehiago

(ChatGPT: Proiektu hau handitzeko, beste modelo eta kontroladore gehiagoren adibideak jar ditzakezu?)

kontzeptua antzekoa izango da, baina beste entitate batzuen datuak kudeatuko ditugu. Adibide honetan, “Post” modelo eta kontroladorea gehituko ditugu

Proiektuko Egitura
```bash
/my_mvc_app
    /controllers
        UserController.php
        PostController.php
    /models
        User.php
        Post.php
    /views
        user_form.php
        user_list.php
        post_form.php
        post_list.php
    /public
        index.php
    /config
        database.php

```

1. Datu Basea Sortzea
```sql
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

```

2. Modeloa: Post.php
```php
<?php
// models/Post.php

require_once '../config/database.php';

class Post {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($title, $content, $user_id) {
        $stmt = $this->pdo->prepare("INSERT INTO posts (title, content, user_id) VALUES (:title, :content, :user_id)");
        $stmt->execute(['title' => $title, 'content' => $content, 'user_id' => $user_id]);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM posts");
        return $stmt->fetchAll();
    }
}

```

3. Kontroladorea: PostController.php

```php
<?php
// controllers/PostController.php

require_once '../models/Post.php';

class PostController {
    private $postModel;

    public function __construct($pdo) {
        $this->postModel = new Post($pdo);
    }

    public function addPost($title, $content, $user_id) {
        $this->postModel->create($title, $content, $user_id);
    }

    public function getPosts() {
        return $this->postModel->getAll();
    }
}

```

4. Ikuslea: post_form.php
```php
<!-- views/post_form.php -->
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Posta Gehitu</title>
</head>
<body>
    <h1>Posta Gehitu</h1>
    <form action="index.php" method="POST">
        <input type="text" name="title" placeholder="Postaren izena" required>
        <textarea name="content" placeholder="Edukia" required></textarea>
        <input type="hidden" name="user_id" value="<?php echo $_GET['user_id']; ?>">
        <button type="submit">Gehitu</button>
    </form>
</body>
</html>

```

5. Ikuslea: post_list.php
```
<!-- views/post_list.php -->
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Posten Zerrenda</title>
</head>
<body>
    <h1>Posten Zerrenda</h1>
    <ul>
        <?php foreach ($posts as $post): ?>
            <li>
                <strong><?php echo htmlspecialchars($post['title']); ?></strong><br>
                <?php echo htmlspecialchars($post['content']); ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="index.php">Itzuli formularioara</a>
</body>
</html>

```

6. Sarrera Puntua: index.php (eguneratuta)
```php
<?php
// public/index.php

require_once '../config/database.php';
require_once '../controllers/UserController.php';
require_once '../controllers/PostController.php';

session_start();

$userController = new UserController($pdo);
$postController = new PostController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $userController->addUser($_POST['name']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $postController->addPost($_POST['title'], $_POST['content'], $_POST['user_id']);
}

$users = $userController->getUsers();
$posts = $postController->getPosts();

if (empty($users)) {
    include '../views/user_form.php';
} elseif (empty($posts)) {
    include '../views/post_form.php';
} else {
    include '../views/post_list.php';
}

```

Azalpena

- Post Modeloa (Post): Posten datuak kudeatzen ditu, erabiltzaileek idatzitako postak.
- Post Kontroladorea (PostController): Postak gehitu eta irakurtzeko logika kudeatzen du.
- Ikusleak (post_form.php eta post_list.php): Posta gehitzeko formularioa eta posten zerrenda erakusten ditu.
- Sarrera Puntua (index.php): Erabiltzailearen eta postaren interakzioak kudeatzen ditu.

  
