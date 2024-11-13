<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Herri zerrenda</title>
</head>
<body>
    <h1>Herri zerrenda</h1>
    <form action="iragarpenak.php" method="GET">
        <?php foreach ($herriak as $herria): ?>
            <label>
                <input type="radio" name="herria_id" value="<?= $herria['id'] ?>" required>
                <?= htmlspecialchars($herria['izena']) ?>
            </label><br>
        <?php endforeach; ?>
        <button type="submit">Gehitu</button>
        <button type="submit">Ezabatu</button>
        <button type="submit">Aldatu</button>
    </form>
</body>
</html>