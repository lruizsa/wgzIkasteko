<?php
session_start();
include 'db.php';  // Datu-baseari konektatzeko fitxategia

if (!isset($_SESSION['erabiltzaile_id'])) {
    header("Location: login.php");
    exit();
}

$erabiltzaile_id = $_SESSION['erabiltzaile_id'];
$izena = $_SESSION['izena'];

// Hasierako aldagaiak
$film_izena = $isan = $urtea = $puntuazioa = '';
$error_msg = '';
$success_msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $film_izena = $_POST['izena'];
    $isan = $_POST['isan'];
    $urtea = $_POST['urtea'];
    $puntuazioa = $_POST['puntuazioa'];

    // Validazioak
    if (!$film_izena && !$isan) {
        $error_msg = "Mesedez, sartu filmaren izena edo ISAN zenbakia.";
    } elseif ($isan && (!preg_match("/^[0-9]{8}$/", $isan))) {
        $error_msg = "ISAN zenbakiak 8 digitu izan behar ditu.";
    } elseif ($isan && !$film_izena && !$puntuazioa) {
        // ISAN hutsik uzten bada, filmaren erregistroa ezabatu
        $stmt = $conn->prepare("SELECT id FROM filmak WHERE isan = ?");
        $stmt->bind_param("s", $isan);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            // Ezabatze baieztapena
            if (isset($_POST['confirm_delete'])) {
                $stmt = $conn->prepare("DELETE FROM erabiltzaile_filmak WHERE film_id = (SELECT id FROM filmak WHERE isan = ?) AND erabiltzaile_id = ?");
                $stmt->bind_param("si", $isan, $erabiltzaile_id);
                $stmt->execute();
                $success_msg = "Filma zerrendatik ezabatu da.";
            } else {
                $error_msg = "Ziur zaude ezabatu nahi duzula film hau? Egin klik berriz ezabatzeko.";
                echo '<form method="post">
                    <input type="hidden" name="isan" value="' . $isan . '">
                    <button type="submit" name="confirm_delete">Bai, ezabatu</button>
                </form>';
            }
        } else {
            $error_msg = "Filma ez da aurkitu ISAN zenbaki honekin.";
        }
    } elseif ($film_izena && !$isan) {
        // ISAN hutsik, izena beteta - filmen bilaketa
        $stmt = $conn->prepare("SELECT f.izena, f.urtea, ef.puntuazioa FROM filmak f JOIN erabiltzaile_filmak ef ON f.id = ef.film_id WHERE LOWER(f.izena) = LOWER(?) AND ef.erabiltzaile_id = ?");
        $stmt->bind_param("si", $film_izena, $erabiltzaile_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "Film aurkitua: " . $row['izena'] . " (" . $row['urtea'] . ") Puntuazioa: " . $row['puntuazioa'] . "<br>";
            }
        } else {
            $error_msg = "Ez da filmik aurkitu izen honekin.";
        }
    } elseif ($film_izena && $isan && $urtea && $puntuazioa) {
        // Filmaren datuak bilatu edo gehitu
        $stmt = $conn->prepare("SELECT id FROM filmak WHERE isan = ?");
        $stmt->bind_param("s", $isan);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            // Filma ez badago, gehitu 'filmak' taulan
            $stmt = $conn->prepare("INSERT INTO filmak (izena, isan, urtea) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $film_izena, $isan, $urtea);
            $stmt->execute();
            $film_id = $conn->insert_id;
        } else {
            // Filma badago, bere IDa lortu
            $stmt->bind_result($film_id);
            $stmt->fetch();
        }

        // Erabiltzailearen puntuazioa eguneratu
        $stmt = $conn->prepare("REPLACE INTO erabiltzaile_filmak (erabiltzaile_id, film_id, puntuazioa) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $erabiltzaile_id, $film_id, $puntuazioa);
        $stmt->execute();
        $success_msg = "Filmaren datuak eguneratu dira.";
    } else {
        $error_msg = "Mesedez, bete eremu guztiak (izena, ISAN, urtea eta puntuazioa).";
    }
}
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title><?php echo $izena; ?>-ren filmak</title>
</head>
<body>
    <h1><?php echo $izena; ?>-ren filmak</h1>

    <!-- Abisuak -->
    <?php if ($error_msg): ?>
        <p style="color:red;"><?php echo $error_msg; ?></p>
    <?php endif; ?>
    <?php if ($success_msg): ?>
        <p style="color:green;"><?php echo $success_msg; ?></p>
    <?php endif; ?>

    <!-- Filmaren formularioa -->
    <form action="index.php" method="POST">
        <label>Filmaren izena: </label>
        <input type="text" name="izena" value="<?php echo htmlspecialchars($film_izena); ?>">
        <br>
        <label>ISAN: </label>
        <input type="text" name="isan" value="<?php echo htmlspecialchars($isan); ?>">
        <br>
        <label>Urtea: </label>
        <input type="number" name="urtea" value="<?php echo htmlspecialchars($urtea); ?>">
        <br>
        <label>Puntuazioa: </label>
        <select name="puntuazioa">
            <option value="">Hautatu puntuazioa</option>
            <?php for ($i = 0; $i <= 5; $i++): ?>
                <option value="<?php echo $i; ?>" <?php if ($puntuazioa == $i) echo 'selected'; ?>><?php echo $i; ?></option>
            <?php endfor; ?>
        </select>
        <br>
        <button type="submit">Bidali</button>
    </form>

    <!-- Erabiltzailearen filmen zerrenda -->
    <h2>Zure filmak:</h2>
    <ul>
        <?php
        $stmt = $conn->prepare("SELECT f.izena, f.urtea, ef.puntuazioa, f.isan FROM filmak f JOIN erabiltzaile_filmak ef ON f.id = ef.film_id WHERE ef.erabiltzaile_id = ?");
        $stmt->bind_param("i", $erabiltzaile_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($row['izena']) . " (" . $row['urtea'] . ") Puntuazioa: " . $row['puntuazioa'] . " ISAN: " . $row['isan'] . "</li>";
        }
        ?>
    </ul>
</body>
</html>
