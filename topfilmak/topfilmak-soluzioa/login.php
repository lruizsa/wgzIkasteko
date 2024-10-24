<?php
session_start();

require "db.php";

// registratu
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Pasahitza enkriptatu

    // Erabiltzailea datu-basean gorde
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->execute(['username' => $username, 'password' => $password]);

    $success = "Erabiltzailea ongi sortu da!";

    // Saioa hasi
    session_start();
    $_SESSION['username'] = $username;
    header("Location: index.php");
    exit;
}

// login
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

?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
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
        <button type="submit" class="btn btn-primary" name="login" value="login">Saioa Hasi</button>
        <button type="submit" class="btn btn-primary" name="register" value="register">Erregistratu</button>        
    </form>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>