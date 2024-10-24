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