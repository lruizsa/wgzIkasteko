<?php

$host = 'db';
$db = 'film_database';
$user = 'root';
$pass = 'root';

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);