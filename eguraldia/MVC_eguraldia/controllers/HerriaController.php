<?php

namespace Controllers;

use Models\Herria;

class HerriaController {

    public function listAll(){
        $herria = new Herria();
        $herriak = $herria->getAll();
        //echo "<pre>"; var_dump($herriak);echo "</pre>";

        require_once '../views/herri-zerrenda.php';
    }
}
