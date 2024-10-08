<?php
// index.php

require 'Config/config.php'; // Inclure le fichier de configuration
require 'Controllers/AuthentificationController.php';
require 'Controllers/SupervisorController.php';

//inclure les fichiers nécessaires
require 'Models/PaymentModel.php'; 
require 'Controllers/PaymentController.php';



$authentificationController = new AuthentificationController($conn);
$errorMessage = $authentificationController->login();
require('Views/Autentification/login.php'); // Charger la vue



// Initialiser le contrôleur avec la base de données
$paymentController = new PaymentController($db);


// Vérification de l'action demandée
$action = $_GET['action'] ?? 'list';
$action = $_GET['action'] ?? 'home';


// Router
$action = $_GET['action'] ?? 'salaire';


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


switch ($action) {
    case 'salaire':
        $paymentController->afficherSalaire($id_professeur);
        break;
        case 'payer':
            $paymentController->payerProfesseur($id_professeur);
            break;
       
    }
