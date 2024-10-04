<?php
// index.php

require 'Config/config.php'; // Inclure le fichier de configuration
require 'Controllers/AuthentificationController.php';
require 'Controllers/SupervisorController.php';

$authentificationController = new AuthentificationController($conn);
$errorMessage = $authentificationController->login();
require('Views/Autentification/login.php'); // Charger la vue



// Vérification de l'action demandée
$action = $_GET['action'] ?? 'list';
$action = $_GET['action'] ?? 'home';

$surveillantController = new SupervisorController($conn);

// Vérification de l'action demandée
$action = $_GET['action'] ?? 'list'; // Par défaut, afficher la liste

switch ($action) {
    case 'create':
        $controller->create();
        break;
        case 'logout':
            echo "Action logout triggered"; // Debugging pour voir si cette action est bien captée
            $authentificationController->logout();
            break;
}

