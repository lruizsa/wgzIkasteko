<?php

// config
require_once '../config/config.php';
// models
require_once '../models/IragarpenEguna.php';
require_once '../models/Herria.php';
// controllers
require_once '../controllers/IragarpenEgunaController.php';

use Controllers\IragarpenEgunaController;

$iragarpenEgunaController = new IragarpenEgunaController();
$iragarpenEgunaController->list($_REQUEST["herria_id"]);
