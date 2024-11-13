<?php

namespace Models;

use PDO;
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

    public function create($izena) {
        $query = "INSERT INTO herria SET izena = :izena";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":izena", $izena, PDO::PARAM_STR);
        return $stmt->execute();
    }
    
    public function delete($id) {
        $query = "DELETE FROM herria WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }     

    // herria lortu id erabiliz
    public function get($id) {
        $query = "SELECT izena FROM herria WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        //return $stmt->fetchAll();
        $herria = $stmt->fetchAll();
        //var_dump($herria);
        return $herria[0]["izena"];
    }    
}
