<?php

namespace Config;

use PDO;

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $host = 'db';
        $dbname = 'eguraldia';
        $username = 'root';
        $password = 'root';

        $this->connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}