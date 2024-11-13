<?php

namespace Controllers;

use Models\Herria;

class HerriaController {

    public function listAll(){
        $herria = new Herria();
        $herriak = $herria->getAll();
        //echo "<pre>"; var_dump($herriak);echo "</pre>";
        require_once '../views/herri-zerrenda.php';
        //require_once '../views/herri-zerrenda-iragarpen-egunak-lortzeko.php';
    }

    public function kudeatu(){
        $herria = new Herria();
        $herriak = $herria->getAll();
        //echo "<pre>"; var_dump($herriak);echo "</pre>";
        //require_once '../views/herri-zerrenda.php';
        require_once '../views/herrien-kudeaketa.php';
    }

    public function herriaGehitu($izena){
        //var_dump($izena);
        $herria = new Herria();
        $herria->create($izena);
        $herriak = $herria->getAll();
        //echo "<pre>"; var_dump($herriak);echo "</pre>";
        //require_once '../views/herri-zerrenda.php';
        require_once '../views/herrien-kudeaketa.php';
    }

    public function herriaEzabatu($id){
        //var_dump($izena);
        $herria = new Herria();
        $herria->delete($id);
        $herriak = $herria->getAll();
        //echo "<pre>"; var_dump($herriak);echo "</pre>";
        //require_once '../views/herri-zerrenda.php';
        require_once '../views/herrien-kudeaketa.php';
    }    

}
