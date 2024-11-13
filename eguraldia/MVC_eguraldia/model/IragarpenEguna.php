<?php

namespace Models;

use PDO;
use Config\Database;
use Models\Herria;

class IragarpenEguna {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // herri baten izena eta data bateko iragerpena
    // [TODO]

    // herria_id bateko iragarpen egun guztiak
    public function getAllbyHerriaId($herria_id) {
        $query = "SELECT * FROM iragarpena_eguna WHERE herria_id = :herria_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":herria_id", $herria_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // herri guztien iragarpen egun guztiak, herriaren izenarekin eguna-ren arabera ordenatua
    public function getAll() {
        $query = "SELECT h.izena AS herria, ie.eguna, ie.iragarpen_testua, ie.eguraldia, ie.tenperatura_minimoa, ie.tenperatura_maximoa FROM herria h JOIN iragarpena_eguna ie ON h.id = ie.herria_id ORDER BY ie.eguna";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }    

    // herri guztien iragarpen egun guztiak
    public function getAll2() {
        $query = "SELECT * FROM iragarpena_eguna ORDER BY herria_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // iragarpen egun bat sortu herria_id erabiliz
    public function createHerriaId($herria_id, $eguna, $iragarpen_testua, $eguraldia, $tenperatura_minimoa, $tenperatura_maximoa) {
        $query = "INSERT INTO iragarpena_eguna SET herria_id = :herria_id, eguna = :eguna, iragarpen_testua = :iragarpen_testua, eguraldia = :eguraldia, tenperatura_minimoa = :tenperatura_minimoa, tenperatura_maximoa = :tenperatura_maximoa";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":herria_id", $herria_id, PDO::PARAM_INT);
        $stmt->bindParam(":eguna", $eguna, PDO::PARAM_STR);
        $stmt->bindParam(":iragarpen_testua", $iragarpen_testua, PDO::PARAM_STR);
        $stmt->bindParam(":eguraldia", $eguraldia, PDO::PARAM_INT);
        $stmt->bindParam(":tenperatura_minimoa", $tenperatura_minimoa, PDO::PARAM_INT);
        $stmt->bindParam(":tenperatura_maximoa", $tenperatura_maximoa, PDO::PARAM_INT);

        return $stmt->execute();
        /*
        if ($stmt->execute()) {
            return true;
        }
        return false;
        */
    }

    // iragarpen egun bat sortu herri izena erabiliz
    // SQL erabiliz ere egin daiteke
    public function createHerria($izena, $eguna, $iragarpen_testua, $eguraldia, $tenperatura_minimoa, $tenperatura_maximoa) {
        $herria = new Herria();
        $herria_id = $herria->getId($izena);
        //var_dump($herria_id);
        $iragarpenEguna = new IragarpenEguna();
        $iragarpenEguna->createHerriaId($herria_id, $eguna, $iragarpen_testua, $eguraldia, $tenperatura_minimoa, $tenperatura_maximoa);
    }

}
