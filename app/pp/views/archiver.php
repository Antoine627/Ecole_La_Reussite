<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure le fichier de configuration pour la connexion à la base de données
require_once '../config/config.php';

try {
    // Connexion à la base de données
    $db = new PDO('mysql:host=localhost;dbname=school_success;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérification de l'ID dans les paramètres GET
    if (isset($_GET['id_surveillant'])) {
        $id_surveillant = intval($_GET['id_surveillant']); // Assurez-vous que l'ID est un entier

        // Mise à jour de la colonne d'archivage
        $sql = "UPDATE surveillants SET archive = 1 WHERE id_surveillant = ?";
        $stmt = $db->prepare($sql);

        if ($stmt->execute([$id_surveillant])) {
            // Redirection ou message de succès
            header('Location: surveillants.php?success=1');
            exit();
        } else {
            echo "<script>alert('Erreur lors de l\'archivage.');</script>";
        }
    }
} catch (PDOException $e) {
    echo "Erreur de connexion : " . htmlspecialchars($e->getMessage());
    exit();
}
?>
