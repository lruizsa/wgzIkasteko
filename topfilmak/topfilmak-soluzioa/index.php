<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require "db.php";

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOP Filmak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="text-center my-4"><?php echo $_SESSION['username'];?>&nbsp;Filmen Zerrenda</h1>
        <form action="logout.php" method="GET">
            <button type="submit" class="btn btn-primary">Logout</button>
        </form>
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
