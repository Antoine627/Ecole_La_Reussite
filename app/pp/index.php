<?php
require_once 'config/config.php';
require_once 'controllers/SurveillantController.php';

$controller = new SurveillantController($db);
$controller->handleRequest(); // Traiter la requête
require 'views/surveillants.php'; // Inclure la vue



?>
