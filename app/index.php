<?php
// index.php

require 'Config/config.php'; // Inclure le fichier de configuration
require 'Controllers/AuthentificationController.php';
require 'Controllers/SurveillantController.php';


$authentificationController = new AuthentificationController($conn);

// Gestion de l'authentification
$errorMessage = $authentificationController->login();
require('Views/Autentification/login.php'); // Charger la vue d'authentification


