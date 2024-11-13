<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Herri bateko iragarpen egunak</title>
</head>
<body>
    <h2><?php echo $herria?> iragarpen egunak</h2>
    <ul>
        <?php foreach ($egunak as $egun): ?>
            <!--<li>id: <?php //echo htmlspecialchars($egun['id']); ?> </li>-->
            <li>eguna: <?php echo htmlspecialchars($egun['eguna']); ?> </li>
            <li>iragarpen_testua: <?php echo htmlspecialchars($egun['iragarpen_testua']); ?> </li>
            <li>eguraldia: <?php echo htmlspecialchars($egun['eguraldia']); ?> </li>
            <li>tenperatura_minimoa: <?php echo htmlspecialchars($egun['tenperatura_minimoa']); ?> </li>
            <li>tenperatura_maximoa: <?php echo htmlspecialchars($egun['tenperatura_maximoa']); ?> </li>
            <br>
        <?php endforeach; ?>
    </ul>
</body>
</html>
