<?php
// Inclure le fichier de connexion à la base de données
include('../config/config.php'); // Remplacez 'config.php' par le chemin de votre fichier de configuration

// Vérifiez si l'ID est passé dans l'URL
if (isset($_GET['id'])) {
    $id_surveillant = $_GET['id'];

    // Vérifiez si le formulaire de confirmation a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Supprimez le surveillant de la base de données
        $query = "DELETE FROM surveillants WHERE id_surveillant = ?";
        $stmt = $db->prepare($query);

        // Exécutez la requête en passant l'ID dans execute
        if ($stmt->execute([$id_surveillant])) {
            // Redirigez vers la page des surveillants après la suppression
            header('Location: surveillants.php');
            exit();
        } else {
            echo "Erreur lors de la suppression du surveillant.";
        }
    }
} else {
    // Si aucun ID n'est spécifié, redirigez vers la page des surveillants
    header('Location: views/surveillants.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Suppression</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Confirmation de Suppression</h2>
        <p>Êtes-vous sûr de vouloir supprimer ce surveillant ?</p>
        <form method="POST">
            <button type="submit" class="btn btn-danger">Oui, supprimer</button>
            <a href="surveillants.php" class="btn btn-secondary">Non, retourner</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
