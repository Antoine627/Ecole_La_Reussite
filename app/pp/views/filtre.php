<?php

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=school_success', 'root', '');

// Initialisation de la requête de base
$query = "SELECT * FROM surveillants WHERE 1=1";

// Gestion des critères de filtrage
$params = [];

// Filtre par nom
if (!empty($_GET['nom'])) {
    $query .= " AND nom LIKE :nom";
    $params[':nom'] = '%' . $_GET['nom'] . '%';
}

// Filtre par matricule
if (!empty($_GET['matricule'])) {
    $query .= " AND matricule LIKE :matricule";
    $params[':matricule'] = '%' . $_GET['matricule'] . '%';
}

// Filtre par niveau
if (!empty($_GET['niveau'])) {
    $query .= " AND niveau = :niveau";
    $params[':niveau'] = $_GET['niveau'];
}

// Préparation et exécution de la requête
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$surveillants = $stmt->fetchAll(PDO::FETCH_ASSOC);


 ?>
