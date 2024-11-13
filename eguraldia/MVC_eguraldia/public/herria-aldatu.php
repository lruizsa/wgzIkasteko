<?php

// config
require_once '../config/config.php';
// models
require_once '../models/Herria.php';
// controllers
require_once '../controllers/HerriaController.php';

use Controllers\HerriaController;

$herriaController = new HerriaController();
//
//var_dump($_REQUEST["herria"]);
$id = $_REQUEST["id"];
$izenBerria = $_REQUEST["izenBerria"];
$herriaController->herriaAldatu($id, $izenBerria);
