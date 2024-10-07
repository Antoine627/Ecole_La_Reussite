<?php
// Inclure le fichier de connexion à la base de données
include('../config/config.php'); // Remplacez 'config.php' par le chemin de votre fichier de configuration

// Vérifiez si l'ID est passé dans l'URL
if (isset($_GET['id'])) {
    $id_surveillant = $_GET['id'];

    // Vérifiez si le formulaire de confirmation a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Démarrer une transaction
            $db->beginTransaction();

            // Récupérer le surveillant à archiver
            $querySelect = "SELECT * FROM surveillants WHERE id_surveillant = ?";
            $stmtSelect = $db->prepare($querySelect);
            $stmtSelect->execute([$id_surveillant]);
            $surveillant = $stmtSelect->fetch(PDO::FETCH_ASSOC);

            if ($surveillant) {
                // Insérer le surveillant dans la table archive
                $queryInsert = "INSERT INTO archive (matricule, num_identite, nom, prenom, date_naissance, adresse, sexe, telephone, email, mot_de_passe, niveau, date_embauche, statut)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmtInsert = $db->prepare($queryInsert);
                $stmtInsert->execute([
                    $surveillant['matricule'],
                    $surveillant['num_identite'],
                    $surveillant['nom'],
                    $surveillant['prenom'],
                    $surveillant['date_naissance'],
                    $surveillant['adresse'],
                    $surveillant['sexe'],
                    $surveillant['telephone'],
                    $surveillant['email'],
                    $surveillant['mot_de_passe'],
                    $surveillant['niveau'],
                    $surveillant['date_embauche'],
                    $surveillant['statut'],
                ]);

                // Supprimer le surveillant de la table principale
                $queryDelete = "DELETE FROM surveillants WHERE id_surveillant = ?";
                $stmtDelete = $db->prepare($queryDelete);
                $stmtDelete->execute([$id_surveillant]);

                // Valider la transaction
                $db->commit();

                // Rediriger vers la page des surveillants après l'archivage
                header('Location: surveillants.php');
                exit();
            } else {
                echo "Surveillant introuvable.";
            }
        } catch (Exception $e) {
            // En cas d'erreur, annuler la transaction
            $db->rollBack();
            echo "Erreur lors de l'archivage du surveillant : " . $e->getMessage();
        }
    }
} else {
    // Si aucun ID n'est spécifié, redirigez vers la page des surveillants
    header('Location: surveillant.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation d'Archivage</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Confirmation d'Archivage</h2>
        <p>Êtes-vous sûr de vouloir archiver ce surveillant ?</p>
        <form method="POST">
            <button type="submit" class="btn btn-danger">Oui, archiver</button>
            <a href="surveillants.php" class="btn btn-secondary">Non, retourner</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
