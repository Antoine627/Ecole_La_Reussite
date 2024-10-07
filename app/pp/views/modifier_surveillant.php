<?php
// Connexion à la base de données
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    // Récupérer les données du formulaire
    $id_surveillant = $_POST['id_surveillant'];
    $num_identite = $_POST['num_identite'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_naissance = $_POST['date_naissance'];
    $adresse = $_POST['adresse'];
    $sexe = $_POST['sexe'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $niveau = $_POST['niveau'];
    $date_embauche = $_POST['date_embauche'];
    $statut = $_POST['statut'];

    // Préparer la requête de mise à jour
    $sql = "UPDATE surveillants SET
                num_identite = ?,
                nom = ?,
                prenom = ?,
                date_naissance = ?,
                adresse = ?,
                sexe = ?,
                telephone = ?,
                email = ?,
                niveau = ?,
                date_embauche = ?,
                statut = ?
            WHERE id_surveillant = ?";

    $stmt = $db->prepare($sql);

    if ($stmt->execute([$num_identite, $nom, $prenom, $date_naissance, $adresse, $sexe, $telephone, $email, $niveau, $date_embauche, $statut, $id_surveillant])) {
        // Redirection après mise à jour réussie
        header("Location: surveillants.php?message=Surveillant mis à jour avec succès");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de la mise à jour du surveillant.</div>";
    }
}

// Récupérer l'ID du surveillant à modifier
if (isset($_GET['id'])) {
    $id_surveillant = $_GET['id'];

    // Récupérer les données du surveillant
    $sql = "SELECT * FROM surveillants WHERE id_surveillant = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id_surveillant]);
    $surveillant = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Surveillant</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center mb-4">Modifier un Surveillant</h3>
        <?php if ($surveillant): ?>
            <form action="" method="post">
                <input type="hidden" name="id_surveillant" value="<?php echo htmlspecialchars($surveillant['id_surveillant']); ?>">

                <div class="form-group">
                    <label for="num_identite">Numéro d'identité</label>
                    <input type="text" class="form-control" id="num_identite" name="num_identite" value="<?php echo htmlspecialchars($surveillant['num_identite']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($surveillant['nom']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo htmlspecialchars($surveillant['prenom']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="date_naissance">Date de Naissance</label>
                    <input type="date" class="form-control" id="date_naissance" name="date_naissance" value="<?php echo htmlspecialchars($surveillant['date_naissance']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <input type="text" class="form-control" id="adresse" name="adresse" value="<?php echo htmlspecialchars($surveillant['adresse']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="sexe">Sexe</label>
                    <select class="form-control" id="sexe" name="sexe" required>
                        <option value="Homme" <?php echo $surveillant['sexe'] == 'Homme' ? 'selected' : ''; ?>>Homme</option>
                        <option value="Femme" <?php echo $surveillant['sexe'] == 'Femme' ? 'selected' : ''; ?>>Femme</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" class="form-control" id="telephone" name="telephone" value="<?php echo htmlspecialchars($surveillant['telephone']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($surveillant['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="niveau">Niveau</label>
                    <select class="form-control" id="niveau" name="niveau" required>
                        <option value="Primaire" <?php echo $surveillant['niveau'] == 'Primaire' ? 'selected' : ''; ?>>Primaire</option>
                        <option value="Secondaire" <?php echo $surveillant['niveau'] == 'Secondaire' ? 'selected' : ''; ?>>Secondaire</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date_embauche">Date d'Embauche</label>
                    <input type="date" class="form-control" id="date_embauche" name="date_embauche" value="<?php echo htmlspecialchars($surveillant['date_embauche']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="statut">Statut</label>
                    <select class="form-control" id="statut" name="statut" required>
                        <option value="Actif" <?php echo $surveillant['statut'] == 'Actif' ? 'selected' : ''; ?>>Actif</option>
                        <option value="En Congé" <?php echo $surveillant['statut'] == 'En Congé' ? 'selected' : ''; ?>>En Congé</option>
                        <option value="Inactif" <?php echo $surveillant['statut'] == 'Inactif' ? 'selected' : ''; ?>>Inactif</option>
                    </select>
                </div>

                <button type="submit" name="update" class="btn btn-success">Mettre à Jour</button>
                <!-- Bouton de retour -->
                <button type="button" class="btn btn-primary" onclick="window.location.href='surveillants.php'">
                    Retour
                </button>
            </form>
        <?php else: ?>
            <div class="alert alert-warning">Surveillant non trouvé.</div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
