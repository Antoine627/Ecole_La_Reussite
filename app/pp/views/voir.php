<?php
// Inclusion de la configuration pour établir la connexion à la base de données
require_once '../config/config.php';

// Fonction pour récupérer les détails d'un surveillant par ID
function getSurveillantById($db, $id) {
    $sql = "SELECT * FROM surveillants WHERE id_surveillant = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Vérification si l'ID du surveillant est passé dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']); // Conversion en entier pour éviter les injections SQL

    // Récupération des détails du surveillant
    $surveillant = getSurveillantById($db, $id);

    // Vérification si le surveillant existe
    if ($surveillant) {
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="../css/style.css"> <!-- Lien vers votre fichier CSS -->
            <title>Détails du Surveillant</title>
        </head>
        <body>
            <div class="container">
                <h1>Détails du Surveillant</h1>
                <div class="card">
                    <div class="card-header">
                        <h2><?php echo htmlspecialchars($surveillant['nom'] . ' ' . $surveillant['prenom']); ?></h2>
                    </div>
                    <div class="card-body">
                        <p><strong>ID:</strong> <?php echo htmlspecialchars($surveillant['id_surveillant']); ?></p>
                        <p><strong>Matricule:</strong> <?php echo htmlspecialchars($surveillant['matricule']); ?></p>
                        <p><strong>Nom:</strong> <?php echo htmlspecialchars($surveillant['nom']); ?></p>
                        <p><strong>Prénom:</strong> <?php echo htmlspecialchars($surveillant['prenom']); ?></p>
                        <p><strong>Date de Naissance:</strong> <?php echo htmlspecialchars($surveillant['date_naissance']); ?></p>
                        <p><strong>Adresse:</strong> <?php echo htmlspecialchars($surveillant['adresse']); ?></p>
                        <p><strong>Sexe:</strong> <?php echo htmlspecialchars($surveillant['sexe']); ?></p>
                        <p><strong>Téléphone:</strong> <?php echo htmlspecialchars($surveillant['telephone']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($surveillant['email']); ?></p>
                        <p><strong>Niveau:</strong> <?php echo htmlspecialchars($surveillant['niveau']); ?></p>
                        <p><strong>Salaire:</strong> <?php echo htmlspecialchars($surveillant['salaire']); ?></p>
                        <p><strong>Date d'Embauche:</strong> <?php echo htmlspecialchars($surveillant['date_embauche']); ?></p>
                        <p><strong>Statut:</strong> <?php echo htmlspecialchars($surveillant['statut']); ?></p>
                    </div>
                    <div class="card-footer">
                        <a href="liste_surveillants.php" class="btn btn-primary">Retour à la Liste des Surveillants</a>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        // Affichage d'un message d'erreur si le surveillant n'est pas trouvé
        echo "<p style='color: red;'>Aucun surveillant trouvé pour l'ID : " . htmlspecialchars($id) . "</p>";
    }
} else {
    // Message d'erreur si l'ID est manquant ou invalide
    echo "<p style='color: red;'>ID manquant ou invalide.</p>";
}
?>
