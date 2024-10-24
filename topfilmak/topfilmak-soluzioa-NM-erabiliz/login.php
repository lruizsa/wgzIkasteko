<?php
session_start();
include 'db.php';  // Datu-baseari konektatzeko fitxategia

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $izena = $_POST['izena'];
    
    // Erabiltzailea datu-basean bilatu
    $stmt = $conn->prepare("SELECT id FROM erabiltzaileak WHERE izena = ?");
    $stmt->bind_param("s", $izena);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        // Erabiltzailea ez badago, sortu berria
        $stmt = $conn->prepare("INSERT INTO erabiltzaileak (izena) VALUES (?)");
        $stmt->bind_param("s", $izena);
        $stmt->execute();
        $erabiltzaile_id = $conn->insert_id;
    } else {
        // Erabiltzailea badago, hartu bere ID-a
        $stmt->bind_result($erabiltzaile_id);
        $stmt->fetch();
    }

    $_SESSION['erabiltzaile_id'] = $erabiltzaile_id;
    $_SESSION['izena'] = $izena;
    
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Saioa hasi</h1>
    <form action="login.php" method="POST">
        <label>Izena: </label>
        <input type="text" name="izena" required>
        <button type="submit">Bidali</button>
    </form>
</body>
</html>
