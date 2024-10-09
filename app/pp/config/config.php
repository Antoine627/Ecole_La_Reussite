<?php
$host = 'localhost'; // ou l'adresse de votre serveur de base de données
$db_name = 'school_success';
$username = 'root';
$password = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    // Configurez le mode d'erreur de PDO pour lancer une exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
