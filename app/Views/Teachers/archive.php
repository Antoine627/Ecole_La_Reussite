<?php
// Connexion à la base de données
require_once '../../Config/config.php';

// Fonction pour archiver un professeur
function archiverProfesseur($db, $id_professeur) {
    $query = "UPDATE professeurs SET archive = 1 WHERE id_professeur = ?";
    $stmt = $db->prepare($query);
    return $stmt->execute([$id_professeur]);
}

// Vérifiez si l'ID est passé dans l'URL pour archiver, désarchiver ou supprimer un professeur
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id_professeur = $_GET['id'];

    if ($_GET['action'] == 'archive') {
        // Archiver le professeur
        if (archiverProfesseur($db, $id_professeur)) {
            $message = "Professeur archivé avec succès.";
            // Rediriger vers la page des professeurs non archivés pour actualiser la liste
            header("Location: teacher.php");
            exit();
        } else {
            $message = "Erreur lors de l'archivage du professeur.";
        }
    } elseif ($_GET['action'] == 'unarchive') {
        // Désarchiver le professeur
        $query = "UPDATE professeurs SET archive = 0 WHERE id_professeur = ?";
        $stmt = $db->prepare($query);
        if ($stmt->execute([$id_professeur])) {
            $message = "Professeur désarchivé avec succès";
            // Rediriger vers la page des professeurs archivés pour actualiser la liste
            header("Location: archive.php");
            exit();
        } else {
            $message = "Erreur lors du désarchivage du professeur.";
        }
    } elseif ($_GET['action'] == 'delete') {
        // Supprimer le professeur
        $query = "DELETE FROM professeurs WHERE id_professeur = ?";
        $stmt = $db->prepare($query);
        if ($stmt->execute([$id_professeur])) {
            $message = "Professeur supprimé avec succès";
            // Rediriger vers la page des professeurs archivés pour actualiser la liste
            header("Location: archive.php");
            exit();
        } else {
            $message = "Erreur lors de la suppression du professeur.";
        }
    }
}

// Fonction pour lister les professeurs non archivés
function listProfesseursNonArchives($db) {
    $sql = "SELECT * FROM professeurs WHERE archive = 0";
    return $db->query($sql);
}

// Fonction pour lister les professeurs archivés
function listArchivedProfesseurs($db) {
    $sql = "SELECT * FROM professeurs WHERE archive = 1";
    return $db->query($sql);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Professeurs Archivés</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .teacher-photo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Gestion des Professeurs</h3>
            <a href="teacher.php" class="btn btn-primary">
                <i class="fas fa-list"></i> Retour à la liste des professeurs
            </a>
        </div>
        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        
        
        <h4 class="mt-5">Professeurs Archivés</h4>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Matricule</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Date de Naissance</th>
                    <th>Adresse</th>
                    <th>Sexe</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Niveau</th>
                    <th>Date d'Embauche</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $professeurs = listArchivedProfesseurs($db);
                while ($row = $professeurs->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                        <td><img src='upload/{$row['photo']}' alt='Photo de {$row['nom']}' class='teacher-photo'></td>
                        <td>{$row['matricule']}</td>
                        <td>{$row['nom']}</td>
                        <td>{$row['prenom']}</td>
                        <td>{$row['date_naissance']}</td>
                        <td>{$row['adresse']}</td>
                        <td>{$row['sexe']}</td>
                        <td>{$row['telephone']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['niveau']}</td>
                        <td>{$row['date_embauche']}</td>
                        <td>{$row['statut']}</td>
                        <td>
                            <a href='?action=unarchive&id={$row['id_professeur']}' class='btn btn-success btn-sm'>
                                <i class='fas fa-undo'></i> Désarchiver
                            </a>
                            <a href='?action=delete&id={$row['id_professeur']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer définitivement ce professeur ?\");'>
                                <i class='fas fa-trash-alt'></i> Supprimer
                            </a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>