<?php

namespace Controllers;

use Models\Herria;

class HerriaController {

    public function listAll(){
        $herria = new Herria();
        $herriak = $herria->getAll();
        //echo "<pre>"; var_dump($herriak);echo "</pre>";
        //require_once '../views/herri-zerrenda.php';
        require_once '../views/herri-zerrenda-iragarpen-egunak-lortzeko.php';
    }

    public function kudeatu(){
        $herria = new Herria();
        $herriak = $herria->getAll();
        //echo "<pre>"; var_dump($herriak);echo "</pre>";
        //require_once '../views/herri-zerrenda.php';
        require_once '../views/herrien-kudeaketa.php';
    }

}
