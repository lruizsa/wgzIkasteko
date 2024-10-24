## Top Movies soluzioa

Hona hemen zure aplikazioaren funtzionalitate nagusiak garatzeko PHP kodearen adibide bat. Zure aplikazioaren oinarria izango da, eta datu-base bat erabiliko du MySQL bidez filmak gordetzeko eta kudeatzeko.

## Datu-basearen konfigurazioa:

Lehenik, datu-basea sortu behar da. Adibidez, film_database izeneko datu-base bat sor dezakezu, eta bertan, films izeneko taula bat:

```sql
CREATE DATABASE film_database;

USE film_database;

CREATE TABLE films (
    id INT AUTO_INCREMENT PRIMARY KEY,
    isan VARCHAR(8) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    year INT NOT NULL,
    rating INT NOT NULL,
    user VARCHAR(255) NOT NULL
);
```

## 1. Login orria (login.php)

Login orri bat sortuko dugu, non erabiltzaileak bere izena idatzi eta gero nagusira bideratuko da.

login.php:
```php
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    if (!empty($username)) {
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $error = "Please enter your name.";
    }
}
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Sartu zure izena</h1>
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Zure izena" required>
        <button type="submit">Sartu</button>
    </form>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
```

## 2. Nagusia (index.php)

Hemen, erabiltzaileak filmak gehitu, ikusi, editatu eta ezabatu ahal izango ditu.

```php
<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$db = 'film_database';
$user = 'root';
$pass = '';

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Erregistro bat gehitzea
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $isan = trim($_POST['isan']);
    $year = trim($_POST['year']);
    $rating = trim($_POST['rating']);
    $username = $_SESSION['username'];

    if (empty($name) && empty($isan)) {
        $error = "Izena eta ISAN zenbakia hutsik daude.";
    } elseif (!empty($name) && empty($isan)) {
        // Izena eman baina ISAN ez: Bilatu izena duen filmak
        $stmt = $conn->prepare("SELECT * FROM films WHERE LOWER(name) = LOWER(:name) AND user = :user");
        $stmt->execute(['name' => $name, 'user' => $username]);
        $films = $stmt->fetchAll();
    } elseif (!empty($isan)) {
        // Gehitu edo eguneratu
        if (strlen($isan) != 8) {
            $error = "ISAN zenbakiak 8 digitu izan behar ditu.";
        } else {
            $stmt = $conn->prepare("SELECT * FROM films WHERE isan = :isan AND user = :user");
            $stmt->execute(['isan' => $isan, 'user' => $username]);
            $film = $stmt->fetch();

            if ($film) {
                if (empty($name)) {
                    // Ezabatu filmaren erregistroa
                    $stmt = $conn->prepare("DELETE FROM films WHERE isan = :isan AND user = :user");
                    $stmt->execute(['isan' => $isan, 'user' => $username]);
                    $success = "Filma zerrendatik ezabatua izan da.";
                } else {
                    // Eguneratu
                    $stmt = $conn->prepare("UPDATE films SET name = :name, year = :year, rating = :rating WHERE isan = :isan AND user = :user");
                    $stmt->execute(['name' => $name, 'year' => $year, 'rating' => $rating, 'isan' => $isan, 'user' => $username]);
                    $success = "Filma eguneratua izan da.";
                }
            } else {
                // Gehitu
                if (!empty($name) && !empty($year) && !empty($rating)) {
                    $stmt = $conn->prepare("INSERT INTO films (isan, name, year, rating, user) VALUES (:isan, :name, :year, :rating, :user)");
                    $stmt->execute(['isan' => $isan, 'name' => $name, 'year' => $year, 'rating' => $rating, 'user' => $username]);
                    $success = "Filma gehitua izan da.";
                } else {
                    $error = "Eremu guztiak bete behar dira.";
                }
            }
        }
    }
}

// Zerrenda erakutsi
$stmt = $conn->prepare("SELECT * FROM films WHERE user = :user");
$stmt->execute(['user' => $_SESSION['username']]);
$films = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Filmak</title>
</head>
<body>
    <h1><?php echo $_SESSION['username']; ?>-ren filmak</h1>

    <h2>Filmen zerrenda</h2>
    <ul>
        <?php foreach ($films as $film): ?>
            <li><?php echo "{$film['name']} ({$film['year']}) - ISAN: {$film['isan']} - Puntuazioa: {$film['rating']}"; ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Filma gehitu edo eguneratu</h2>
    <form action="index.php" method="POST">
        <label>Izena:</label>
        <input type="text" name="name"><br>
        <label>ISAN:</label>
        <input type="text" name="isan"><br>
        <label>Urtea:</label>
        <input type="number" name="year"><br>
        <label>Puntuazioa:</label>
        <select name="rating">
            <?php for ($i = 0; $i <= 5; $i++): ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php endfor; ?>
        </select><br>
        <button type="submit">Bidali</button>
    </form>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
</body>
</html>
```

## Aplikazioaren funtzionalitateak:

- Login sistema: Erabiltzaileak bere izena idazten du login.php orrian, eta ondoren index.php orrira bideratuko da.
- Filmen kudeaketa: Erabiltzaileak filmak gehitu, editatu edo ezabatu ditzake.
  - Filmak gehitu ahal izateko, ISAN zenbakia 8 digitu izan behar ditu.
  - Erabiltzaileak ISAN zenbakia jarri eta izena hutsik uzten badu, filmaren erregistroa ezabatuko da.
  - ISAN zenbakia ez badago, film berri bat sortuko da.
  - ISAN zenbakia badago, filmaren izena eta puntuazioa eguneratu ahal izango dira.


## Hobekuntzak

### Aplikazioaren interfazea hobetzea.

Erabiltzaileen esperientzia hobetzeko interfazeak garapen garrantzitsua behar du:

- Bootstrap edo Tailwind CSS bezalako CSS framework-ak erabiliz, interfazea errazago egin dezakezu eraginkorrago eta estetikoagoa.
- Responsive diseinua gehitu, mugikorrean eta tabletean erabiltzeko egokia izan dadin.
- Gehitu erabiltzaileen feedbacka azkar identifikatzeko mezuak (adibidez, "Filma ongi gehitua izan da", "Ezabatzeko konfirmazioa", etab.).
- Formularioan gure aurrebista gehitu. Filma gehitzen ari direnean, erabiltzaileak informazioa zuzenean ikusi dezake.
Adibidea Bootstrap erabiliz:
- Bootstrap framework-arekin interfaze sinple bat sor dezakezu eta elementu guztiak formatu eta diseinatu:

```php
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOP Filmak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="text-center my-4">Filmen Zerrenda</h1>
        <div class="row">
            <div class="col-md-8">
                <ul class="list-group">
                    <?php foreach ($films as $film): ?>
                        <li class="list-group-item">
                            <strong><?php echo "{$film['name']} ({$film['year']})"; ?></strong> 
                            <span class="badge bg-primary"><?php echo $film['rating']; ?></span> 
                            <span class="text-muted">ISAN: <?php echo $film['isan']; ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-md-4">
                <h2>Filma Gehitu</h2>
                <form action="index.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Izena:</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="isan" class="form-label">ISAN:</label>
                        <input type="text" class="form-control" name="isan" required>
                    </div>
                    <div class="mb-3">
                        <label for="year" class="form-label">Urtea:</label>
                        <input type="number" class="form-control" name="year" required>
                    </div>
                    <div class="mb-3">
                        <label for="rating" class="form-label">Puntuazioa:</label>
                        <select class="form-select" name="rating">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Gehitu Filma</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

```



### Login orriari pasahitza jarri

Login orrian pasahitza gehitzea oso garrantzitsua da erabiltzaileen segurtasuna bermatzeko. Hona hemen nola gehitu pasahitza login orriari eta datu-basean erabiltzaileak sortzeko beharrezko kodea.


1. Datu-baseko taula aldatu

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

2. Pasahitzak gorde

Erabiltzaileak sortzen diren bakoitzean, pasahitzak enkriptatu egin behar dira segurtasun arrazoiak direla eta. Hona hemen pasahitz bat nola gorde:
```php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Pasahitza enkriptatu

    // Erabiltzailea datu-basean gorde
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->execute(['username' => $username, 'password' => $password]);

    $success = "Erabiltzailea ongi sortu da!";
}
```

3. Login formularioa

Login formularioa gehitzea:
```html
<h2>Login</h2>
<form action="login.php" method="POST">
    <div class="mb-3">
        <label for="username" class="form-label">Erabiltzaile izena:</label>
        <input type="text" class="form-control" name="username" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Pasahitza:</label>
        <input type="password" class="form-control" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary" name="login">Saioa Hasi</button>
</form>
```

4. Login logika

Login prozesua kudeatzeko logika gehitzea:
```php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Erabiltzailearen informazioa lortu
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    // Pasahitza egiaztatu
    if ($user && password_verify($password, $user['password'])) {
        // Saioa hasi
        session_start();
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    } else {
        $error = "Erabiltzaile izena edo pasahitza okerra da.";
    }
}
```

5. Erabiltzaileak saioa amaitzea

Erabiltzaileak saioa amaitzeko aukera emateko, hurrengo kodea gehitu:
```php
if (isset($_GET['logout'])) {
    session_start();
    session_destroy();
    header("Location: login.php");
    exit;
}
```

6. Orrialdeak babesteko logika

Saioa hasi duten erabiltzaileek bakarrik sartu beharko lukete index.php orrialdean:
```php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
```

7. Erabiltzaileak erregistratzeko formularioa

Erabiltzaileak erregistratzeko formularioa gehitzeko aukera ere ematen duzu:
```html
<h2>Erregistratu</h2>
<form action="register.php" method="POST">
    <div class="mb-3">
        <label for="username" class="form-label">Erabiltzaile izena:</label>
        <input type="text" class="form-control" name="username" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Pasahitza:</label>
        <input type="password" class="form-control" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary" name="register">Erregistratu</button>
</form>
```

8. Erregistratzeko logika

Erabiltzaileak erregistratzeko logika kudeatzeko:
```php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Pasahitza enkriptatu

    // Erabiltzailea datu-basean gorde
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->execute(['username' => $username, 'password' => $password]);

    $success = "Erabiltzailea ongi sortu da!";
}
```

Laburbilduz
Horrek guztiak erabiltzaileek pasahitza erabiliz logeatzeko aukera ematen du, eta baita erabiltzaile berriak erregistratzeko aukera ere. Pasahitzak segurtasunez kudeatzea funtsezkoa da, eta password_hash eta password_verify funtzioak erabiltzen dira segurtasuna handitzeko.

Gainera, session_start() erabiliz, erabiltzailearen saioa kontrolatzen da

### bilaketa aurreratua

Bilaketa aurreratua gehitzea ideia ona da, erabiltzaileei filmak hainbat irizpideren arabera aurkitzeko aukera emateko. Hona hemen nola egin:

- Bilaketa egiteko formulario bat gehitu, non erabiltzaileak izena, urtea edo puntuazioa sar ditzake.
- SQL kontsultak irizpide bat edo gehiago kontuan hartuz eragiteko.

Bilaketa egiteko formularioa:

```php
<form action="index.php" method="GET">
    <div class="mb-3">
        <label for="search_name" class="form-label">Izena:</label>
        <input type="text" class="form-control" name="search_name">
    </div>
    <div class="mb-3">
        <label for="search_year" class="form-label">Urtea:</label>
        <input type="number" class="form-control" name="search_year">
    </div>
    <div class="mb-3">
        <label for="search_rating" class="form-label">Puntuazioa:</label>
        <select class="form-select" name="search_rating">
            <option value="">Guztiak</option>
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
    </div>
    <button type="submit" class="btn btn-secondary">Bilatu</button>
</form>
```

Bilaketa logika (SQL kontsulta):
```php
$sql = "SELECT * FROM films WHERE user = :user";
$params = ['user' => $_SESSION['username']];

if (!empty($_GET['search_name'])) {
    $sql .= " AND LOWER(name) LIKE LOWER(:search_name)";
    $params['search_name'] = '%' . $_GET['search_name'] . '%';
}
if (!empty($_GET['search_year'])) {
    $sql .= " AND year = :search_year";
    $params['search_year'] = $_GET['search_year'];
}
if (!empty($_GET['search_rating'])) {
    $sql .= " AND rating = :search_rating";
    $params['search_rating'] = $_GET['search_rating'];
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$films = $stmt->fetchAll();
```

### Ikusle guztien arteko TOP 10 filmak

TOP 10 filmak erakusteko, ikusle guztien puntuazio altuenak dituzten filmak erakutsi daitezke:
```php
$sql = "SELECT name, year, AVG(rating) as avg_rating FROM films GROUP BY name, year ORDER BY avg_rating DESC LIMIT 10";
$stmt = $conn->prepare($sql);
$stmt->execute();
$top_films = $stmt->fetchAll();
```

Honek filmak puntuazioaren arabera sailkatuko ditu, eta bataz besteko puntuazioa erakutsiko du. Ondoren, zerrenda moduan bistaratzen da:
```php
<h2>TOP 10 Filmak</h2>
<ul class="list-group">
    <?php foreach ($top_films as $film): ?>
        <li class="list-group-item">
            <?php echo "{$film['name']} ({$film['year']}) - Puntuazioaren bataz bestekoa: {$film['avg_rating']}"; ?>
        </li>
    <?php endforeach; ?>
</ul>
```

### Erabiltzaileek iruzkinak gehitzeko aukera

Filmen inguruko iritziak partekatzeko.

Lehenik eta behin, datu-basean iruzkinak gordetzeko taula berri bat sortu behar duzu:
```sql
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    film_id INT NOT NULL,
    user VARCHAR(255) NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (film_id) REFERENCES films(id) ON DELETE CASCADE
);
```
Iruzkinak gehitzeko kodea:

Iruzkinak erakusteko eta gehitzeko HTML formularioa:
```php
<?php
// Iruzkinak erakutsi filma bakoitzeko
$film_id = $film['id'];
$stmt = $conn->prepare("SELECT * FROM comments WHERE film_id = :film_id ORDER BY created_at DESC");
$stmt->execute(['film_id' => $film_id]);
$comments = $stmt->fetchAll();
?>

<div class="comments">
    <h3>Iruzkinak:</h3>
    <ul class="list-group">
        <?php foreach ($comments as $comment): ?>
            <li class="list-group-item">
                <strong><?php echo $comment['user']; ?>:</strong> <?php echo $comment['comment']; ?>
                <small class="text-muted">(<?php echo $comment['created_at']; ?>)</small>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<h4>Iruzkin bat gehitu:</h4>
<form action="index.php" method="POST">
    <input type="hidden" name="film_id" value="<?php echo $film_id; ?>">
    <div class="mb-3">
        <textarea class="form-control" name="comment" rows="3" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Gehitu Iruzkina</button>
</form>
```

Iruzkin bat gordetzeko logika:
```php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['comment'])) {
    $film_id = $_POST['film_id'];
    $comment = trim($_POST['comment']);
    $user = $_SESSION['username'];

    $stmt = $conn->prepare("INSERT INTO comments (film_id, user, comment) VALUES (:film_id, :user, :comment)");
    $stmt->execute([
        'film_id' => $film_id,
        'user' => $user,
        'comment' => $comment
    ]);

    $success = "Iruzkina gehitua izan da!";
}
```

### Erabiltzaileen artean bozketa sistema

Beste erabiltzaileen puntuazioak ikusita, erabiltzaileek filmak bozkatu ditzakete.

Datu-basean bozketa gordetzeko taula gehitu:
```sql
CREATE TABLE votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    film_id INT NOT NULL,
    user VARCHAR(255) NOT NULL,
    vote TINYINT NOT NULL CHECK (vote IN (1, -1)),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (film_id) REFERENCES films(id) ON DELETE CASCADE,
    UNIQUE (film_id, user)
);

```

Bozkatzeko botoiak gehitzea:
```php
<?php
// Bozkak erakutsi
$film_id = $film['id'];
$stmt = $conn->prepare("SELECT SUM(vote) AS total_votes FROM votes WHERE film_id = :film_id");
$stmt->execute(['film_id' => $film_id]);
$total_votes = $stmt->fetchColumn();
?>

<div class="votes">
    <strong>Bozken kopurua:</strong> <?php echo $total_votes ?: 0; ?>
    <form action="index.php" method="POST" class="d-inline">
        <input type="hidden" name="film_id" value="<?php echo $film_id; ?>">
        <button type="submit" name="vote" value="1" class="btn btn-success">Gustuko</button>
        <button type="submit" name="vote" value="-1" class="btn btn-danger">Ez gustuko</button>
    </form>
</div>
```

Bozketak kudeatzeko logika:
```php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vote'])) {
    $film_id = $_POST['film_id'];
    $vote = $_POST['vote'];
    $user = $_SESSION['username'];

    // Bozketa badaude, eguneratu; bestela, sartu berri bat
    $stmt = $conn->prepare("SELECT * FROM votes WHERE film_id = :film_id AND user = :user");
    $stmt->execute(['film_id' => $film_id, 'user' => $user]);
    $existing_vote = $stmt->fetch();

    if ($existing_vote) {
        $stmt = $conn->prepare("UPDATE votes SET vote = :vote WHERE film_id = :film_id AND user = :user");
        $stmt->execute(['vote' => $vote, 'film_id' => $film_id, 'user' => $user]);
        $success = "Bozketa eguneratua izan da!";
    } else {
        $stmt = $conn->prepare("INSERT INTO votes (film_id, user, vote) VALUES (:film_id, :user, :vote)");
        $stmt->execute(['film_id' => $film_id, 'user' => $user, 'vote' => $vote]);
        $success = "Bozketa gehitua izan da!";
    }
}
```


### "Gorde gustukoetan" funtzioa

Erabiltzaile bakoitzak bere gustukoen zerrenda gordetzeko aukera izango du.

Datu-basean gustukoen taula sortu:
```sql
CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    film_id INT NOT NULL,
    user VARCHAR(255) NOT NULL,
    FOREIGN KEY (film_id) REFERENCES films(id) ON DELETE CASCADE,
    UNIQUE (film_id, user)
);
```

Gustukoen botoia gehitu:
```php
<?php
// Gustukoetan dagoen ala ez egiaztatu
$film_id = $film['id'];
$stmt = $conn->prepare("SELECT * FROM favorites WHERE film_id = :film_id AND user = :user");
$stmt->execute(['film_id' => $film_id, 'user' => $_SESSION['username']]);
$is_favorite = $stmt->fetch();
?>

<div class="favorite">
    <form action="index.php" method="POST">
        <input type="hidden" name="film_id" value="<?php echo $film_id; ?>">
        <?php if ($is_favorite): ?>
            <button type="submit" name="remove_favorite" class="btn btn-warning">Kendu gustukoetatik</button>
        <?php else: ?>
            <button type="submit" name="add_favorite" class="btn btn-success">Gorde gustukoetan</button>
        <?php endif; ?>
    </form>
</div>
```

Gustukoen logika:
```php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_favorite'])) {
    $film_id = $_POST['film_id'];
    $user = $_SESSION['username'];

    // Gustukoetan gehitu
    $stmt = $conn->prepare("INSERT INTO favorites (film_id, user) VALUES (:film_id, :user)");
    $stmt->execute(['film_id' => $film_id, 'user' => $user]);
    $success = "Filma gustukoetan gehitua izan da!";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_favorite'])) {
    $film_id = $_POST['film_id'];
    $user = $_SESSION['username'];

    // Gustukoetatik kendu
    $stmt = $conn->prepare("DELETE FROM favorites WHERE film_id = :film_id AND user = :user");
    $stmt->execute(['film_id' => $film_id, 'user' => $user]);
    $success = "Filma gustukoetatik kendua izan da!";
}
```

### Filmak generoen arabera sailkatu eta filtratu

Filmak generoaren arabera sailkatzeko aukera gehitu. (Genero sistema bat sortzea datu-basean gehigarria izan daiteke).

Lehenik, "generoa" eremua gehitu behar diozu datu-baseko films taulari:
```sql
ALTER TABLE films ADD genre VARCHAR(255) NOT NULL;
```

Generoaren bidez filtratzeko formularioa:
```php
<form action="index.php" method="GET">
    <div class="mb-3">
        <label for="genre" class="form-label">Generoa:</label>
        <select class="form-select" name="genre">
            <option value="">Generoa aukeratu</option>
            <option value="Action">Action</option>
            <option value="Comedy">Comedy</option>
            <option value="Drama">Drama</option>
            <option value="Horror">Horror</option>
            <!-- Gehitu beste genero batzuk -->
        </select>
    </div>
    <button type="submit" class="btn btn-secondary">Filtratu</button>
</form>
```

Generoaren arabera filmak bilatzeko logika:
```php
$sql = "SELECT * FROM films WHERE user = :user";
$params = ['user' => $_SESSION['username']];

if (!empty($_GET['genre'])) {
    $sql .= " AND genre = :genre";
    $params['genre'] = $_GET['genre'];
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$films = $stmt->fetchAll();
```

