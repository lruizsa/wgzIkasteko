<?php
$servername = "db";
$username = "root";  // Aldatu zure erabiltzailearen arabera
$password = "root";      // Aldatu zure pasahitzaren arabera
$dbname = "top_filmak";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Konektatzeko errorea: " . $conn->connect_error);
}
?>
