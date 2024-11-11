<?php

namespace Models;

//use PDO;
use Config\Database;

class Herria {
    private $db;
        
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM herria ORDER BY izena");
        $stmt->execute();
        return $stmt->fetchAll();
    }    
}
