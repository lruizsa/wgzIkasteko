<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Herri zerrenda</h1>
    <ul>
    <?php 
        foreach ($herriak as $herri) {
            echo "<li>". htmlspecialchars($herri['izena']) . "</li>";
        }
        ?>
    </ul>    
</body>
</html>
