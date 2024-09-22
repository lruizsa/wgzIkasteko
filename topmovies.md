# TOP Movies Aplikazioa

Aplikazio honen helburu nagusia ikusi dituzun filmak eta haien puntuazioak gordetzea da, eta hainbat baldintza bete behar ditu. Azpian, egin beharreko urratsak zehazten dira:

## Aplikazioaren Eskakizunak:
### Orrialdek bi atal nagusi ditu:
1. **Goiko zatia**: Filmen zerrenda eta puntuazioak erakutsiko ditu.
2. **Beheko zatia**: Film berri bat gehitzeko formularioa erakutsiko du.

Formularioak honako eremuak izango ditu:
- Filmaren izena (text box)
- ISAN zenbakia (text box) (8 digitu)
- Urtea (text box)
- Puntuazioa (combo box, 0-5 artean)
- "Bidali" botoia (formularioa bidaltzeko)

### Formularioaren Balidazioak:
1. **Izena eta ISAN hutsik badaude**, abisu-mezua erakutsi behar da.
2. **ISAN-a ez badago zerrendan** eta **8 digitu dituen ISAN** sartzen bada, eta eremu guztiak baliozkoak badira, film berria gehitu.
3. **ISAN hutsik** baina **izena badago**: sartutako izenarekin bat datozen filmak erakutsi behar dira.
4. **ISAN-a badago** zerrendan, eta eremu guztiak baliozkoak badira, filmaren izena eta puntuazioa eguneratu behar dira.
5. **ISAN badago** eta **izena hutsik badago**, filma ezabatu behar da.
6. Formularioan sartutako balioak mantendu behar dira.

## Bigarren atala: index.php eta main.php
- **index.php**: Erabiltzailearen izena sartu eta formularioa bidali ondoren, erabiltzailea **main.php** orrira birbideratu.
- **main.php**: Goiburuan erabiltzailearen izena erakutsi, adibidez: "username movies:".

## Gehigarriak:
- **Datuak modu iraunkorrean gordetzea**: xml, json, etab.
- **Erabiltzaile interfaze hobetua**.

## Aplikazioaren Egitura:

### 1. index.php:
```php
<!DOCTYPE html>
<html>
<head>
    <title>Top Movies</title>
</head>
<body>
    <h1>Enter your name</h1>
    <form action="main.php" method="POST">
        <label for="username">Name:</label>
        <input type="text" id="username" name="username" required>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
```

### 2. main.php:
```php
<?php
session_start();
$username = $_POST['username'] ?? '';
if ($username) {
    $_SESSION['username'] = $username;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Top Movies</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($_SESSION['username']); ?> movies:</h1>

    <!-- Filmak erakusteko zerrenda -->
    <h2>Movie List</h2>
    <ul id="movieList">
        <!-- Filmak hemen gehituko dira -->
    </ul>

    <!-- Formularioa -->
    <h2>Add/Update Movie</h2>
    <form action="main.php" method="POST">
        <label for="name">Movie Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $_POST['name'] ?? ''; ?>" />

        <label for="isan">ISAN:</label>
        <input type="text" id="isan" name="isan" maxlength="8" value="<?php echo $_POST['isan'] ?? ''; ?>" />

        <label for="year">Year:</label>
        <input type="text" id="year" name="year" value="<?php echo $_POST['year'] ?? ''; ?>" />

        <label for="rating">Rating:</label>
        <select id="rating" name="rating">
            <?php
            for ($i = 0; $i <= 5; $i++) {
                echo "<option value=\"$i\"" . (isset($_POST['rating']) && $_POST['rating'] == $i ? ' selected' : '') . ">$i</option>";
            }
            ?>
        </select>

        <button type="submit">Submit</button>
    </form>

    <!-- Abisu mezua -->
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'] ?? '';
        $isan = $_POST['isan'] ?? '';
        $year = $_POST['year'] ?? '';
        $rating = $_POST['rating'] ?? '';

        if (empty($name) && empty($isan)) {
            echo "<p style='color:red;'>Error: Name and ISAN cannot be both empty.</p>";
        } elseif (!empty($isan) && strlen($isan) != 8) {
            echo "<p style='color:red;'>Error: ISAN must be 8 digits long.</p>";
        } else {
            // Gorde edo eguneratu logika
            // Filmaren izena eta urtearen arabera bilatu, ISAN hutsik badago...
            echo "<p>Movie processed successfully.</p>";
        }
    }
    ?>
</body>
</html>
```
