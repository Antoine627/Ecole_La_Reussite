<?php
// Connexion à la base de données
require_once '../../Config/config.php';

// Vérifier si l'ID du professeur est passé dans l'URL
if (isset($_GET['id'])) {
    $id_professeur = $_GET['id'];

    // Si la confirmation est reçue
    if (isset($_POST['confirm_delete'])) {
        // Supprimer le professeur
        $query = "DELETE FROM professeurs WHERE id_professeur = ?";
        $stmt = $db->prepare($query);
        if ($stmt->execute([$id_professeur])) {
            // Redirection vers la page des professeurs avec un message de succès
            header("Location: teacher.php?message=Professeur supprimé avec succès");
            exit();
        } else {
            $error_message = "Erreur lors de la suppression du professeur.";
        }
    }

    // Récupérer les informations du professeur
    $query = "SELECT nom, prenom FROM professeurs WHERE id_professeur = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$id_professeur]);
    $professeur = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Redirection si l'ID n'est pas fourni
    header("Location: teacher.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un Professeur</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Supprimer un Professeur</h2>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <p>Êtes-vous sûr de vouloir supprimer le professeur <?php echo $professeur['nom'] . ' ' . $professeur['prenom']; ?> ?</p>
        <form action="" method="post">
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal">
                Supprimer
            </button>
            <a href="teacher.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

    <!-- Modal de confirmation -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmer la suppression</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Êtes-vous vraiment sûr de vouloir supprimer ce professeur ? Cette action est irréversible.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <form action="" method="post">
                        <input type="hidden" name="confirm_delete" value="1">
                        <button type="submit" class="btn btn-danger">Confirmer la suppression</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>