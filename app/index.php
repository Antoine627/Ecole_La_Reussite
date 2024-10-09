<?php
// index.php

require 'Config/config.php'; // Inclure le fichier de configuration
require 'Controllers/AuthentificationController.php';
require 'Controllers/SurveillantController.php';
require 'Controllers/StudentController.php';
require 'Models/StudentModel.php';




$eleveController = new EleveController($pdo);


$authentificationController = new AuthentificationController($conn);

// Gestion de l'authentification
$errorMessage = $authentificationController->login();
require('Views/Autentification/login.php'); // Charger la vue d'authentification


// Vérifier si l'utilisateur a cliqué sur le lien de déconnexion
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $authentificationController->logout(); // Appeler la méthode de déconnexion
}

// Gestion de l'authentification
$errorMessage = $authentificationController->login();



$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'create':
        $eleveController->create();
        break;
    case 'edit':
        $eleveController->edit($id);
        break;
    case 'delete':
        $eleveController->delete($id);
        break;
    case 'index':
    default:
        $eleveController->index();
        break;
}