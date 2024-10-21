<?php

namespace Controllers;

use Models\User;

class HomeController {
    public function index() {
        $userModel = new User();
        $users = $userModel->getAllUsers();

        // Igo erabiltzaileak ikusteko
        require_once __DIR__ . '/../views/home.php';
    }
}