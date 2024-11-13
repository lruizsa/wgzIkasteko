<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Herri zerrenda</title>
</head>
<body>
    <h1>Herri zerrenda</h1>
    <form method="GET">
        <?php foreach ($herriak as $herria): ?>
            <label>
                <input type="radio" name="id" value="<?= $herria['id'] ?>" required>
                <?= htmlspecialchars($herria['izena']) ?>
            </label><br>
        <?php endforeach; ?>
        Aukeratutako herria <button type="submit" formaction="herria-ezabatu.php">Ezabatu</button>
        <br>
        Aukeratutako herriari izena aldatu. Izen berria: <input type="text" name="izenBerria"/>
        <button type="submit" formaction="herria-aldatu.php">Aldatu</button>
    </form>
    <br>---<br>
    <!-- herria create -->
    <form action="herria-gehitu.php" method="GET">
        Herria berria gehitu. Izena: <input type="text" name="herria"/>
        <button type="submit">Gehitu</button>   
    </form>
</body>
</html>
