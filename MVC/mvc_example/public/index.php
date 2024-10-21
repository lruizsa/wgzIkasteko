<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';

use Controllers\HomeController;

// Url-aren arabera, kontrolatzaile ezberdinak bideratuko dira
$controller = new HomeController();
$controller->index();