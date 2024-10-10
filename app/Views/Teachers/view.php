<?php
// Vérifier si l'ID du professeur est passé dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Aucun professeur spécifié.";
    exit;
}

// Inclure le fichier de configuration de la base de données
require_once '../../Config/config.php';
include '../../Views/Components/Header_nav.php';

// Récupérer les détails du professeur
$id = $_GET['id'];
$sql = "SELECT * FROM professeurs WHERE id_professeur = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$id]);
$professeur = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si le professeur existe
if (!$professeur) {
    echo "Professeur non trouvé.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Professeur</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .teacher-details {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .teacher-photo {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Détails du Professeur</h1>
        <div class="teacher-details">
            <div class="row">
                <div class="col-md-4 text-center">
                    <img src="<?php echo htmlspecialchars($professeur['photo']); ?>" alt="Photo du professeur" class="teacher-photo">
                </div>
                <div class="col-md-8">
                    <h2><?php echo htmlspecialchars($professeur['nom'] . ' ' . $professeur['prenom']); ?></h2>
                    <table class="table table-bordered">
                        <tr>
                            <th>Matricule</th>
                            <td><?php echo htmlspecialchars($professeur['matricule']); ?></td>
                        </tr>
                        <tr>
                            <th>Date de naissance</th>
                            <td><?php echo htmlspecialchars($professeur['date_naissance']); ?></td>
                        </tr>
                        <tr>
                            <th>Adresse</th>
                            <td><?php echo htmlspecialchars($professeur['adresse']); ?></td>
                        </tr>
                        <tr>
                            <th>Sexe</th>
                            <td><?php echo htmlspecialchars($professeur['sexe']); ?></td>
                        </tr>
                        <tr>
                            <th>Téléphone</th>
                            <td><?php echo htmlspecialchars($professeur['telephone']); ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo htmlspecialchars($professeur['email']); ?></td>
                        </tr>
                        <tr>
                            <th>Niveau</th>
                            <td><?php echo htmlspecialchars($professeur['niveau']); ?></td>
                        </tr>
                        <tr>
                            <th>Date d'embauche</th>
                            <td><?php echo htmlspecialchars($professeur['date_embauche']); ?></td>
                        </tr>
                        <tr>
                            <th>Statut</th>
                            <td><?php echo htmlspecialchars($professeur['statut']); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="teacher.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>