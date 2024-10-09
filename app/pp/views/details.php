<?php
// Connexion à la base de données (assurez-vous que votre connexion est correcte)
require_once '../config/config.php';

// Récupérer l'ID du surveillant depuis l'URL
$surveillant_id = $_GET['id'];

// Requête SQL pour récupérer les informations du surveillant
$sql = "SELECT id_surveillant, nom, prenom, date_naissance, adresse, sexe, telephone, email, mot_de_passe, matricule, niveau, date_embauche, salaire, photo, statut, role, date_creation, date_modification FROM surveillants WHERE id_surveillant = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$surveillant_id]);

// Récupérer les informations sous forme de tableau associatif
$surveillant = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifiez si le surveillant existe
if ($surveillant) {
    // Déclarer les variables à partir du tableau associatif $surveillant
    $nom = $surveillant['nom'];
    $prenom = $surveillant['prenom'];
    $date_naissance = $surveillant['date_naissance'];
    $adresse = $surveillant['adresse'];
    $sexe = $surveillant['sexe'];
    $telephone = $surveillant['telephone'];
    $email = $surveillant['email'];
    $matricule = $surveillant['matricule'];
    $niveau = $surveillant['niveau'];
    $date_embauche = $surveillant['date_embauche'];
    $salaire = $surveillant['salaire'];
    $photo = $surveillant['photo'];
    $statut = $surveillant['statut'];
    $role = $surveillant['role'];
    $date_creation = $surveillant['date_creation'];
    $date_modification = $surveillant['date_modification'];
} else {
    echo "Surveillant non trouvé.";
    exit;
}

// Affichage des détails
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Surveillant</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Couleur de fond douce */
        }
        .card {
            margin-bottom: 20px; /* Espacement entre les cartes */
            border: 1px solid #dee2e6; /* Bordure de carte */
            border-radius: 10px; /* Coins arrondis */
        }
        .profile-img {
            width: 100%; /* Largeur de l'image */
            height: auto; /* Hauteur automatique pour garder les proportions */
            border-radius: 10px; /* Coins arrondis pour l'image */
            border:  1px solid #dee2e6; /* Bordure bleue */
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.5); /* Ombre portée */
        }
        .info-label {
            font-weight: bold; /* Étiquette en gras */
        }
        .text-center {
            margin: 20px 0; /* Espacement pour le titre */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Détails du Surveillant</h2>

        <div class="row">
            <div class="col-md-8">
                <!-- Conteneur pour les informations personnelles -->
                <div class="card p-4 mb-3">
                    <h5 class="card-title text-center">Informations Personnelles</h5>
                    <div class="row">
                      <div class="col-md-4">
                        <img src="<?php echo htmlspecialchars($photo); ?>" alt="Photo du surveillant" class="profile-img mb-3">
                      </div>
                        <div class="col-md-4">
                            <p><span class="info-label">Nom :</span> <?php echo htmlspecialchars($nom); ?></p>
                            <p><span class="info-label">Date de naissance :</span> <?php echo htmlspecialchars($date_naissance); ?></p>
                            <p><span class="info-label">Téléphone :</span> <?php echo htmlspecialchars($telephone); ?></p>
                            <p><span class="info-label">Date d'embauche :</span> <?php echo htmlspecialchars($date_embauche); ?></p>
                        </div>
                        <div class="col-md-3">
                            <p><span class="info-label">Prénom :</span> <?php echo htmlspecialchars($prenom); ?></p>
                            <p><span class="info-label">Sexe :</span> <?php echo htmlspecialchars($sexe); ?></p>
                            <p><span class="info-label">Email :</span> <?php echo htmlspecialchars($email); ?></p>
                            <p><span class="info-label">Salaire :</span> <?php echo htmlspecialchars($salaire); ?></p>
                        </div>
                    </div>
                </div>
                <!-- Autres informations -->
                <div class="card p-4">
                    <h5 class="card-title text-center">Autres Informations</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><span class="info-label">Adresse :</span> <?php echo htmlspecialchars($adresse); ?></p>
                            <p><span class="info-label">Matricule :</span> <?php echo htmlspecialchars($matricule); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><span class="info-label">Niveau :</span> <?php echo htmlspecialchars($niveau); ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><span class="info-label">Statut :</span> <?php echo htmlspecialchars($statut); ?></p>
                            <p><span class="info-label">Rôle :</span> <?php echo htmlspecialchars($role); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><span class="info-label">Date de création :</span> <?php echo htmlspecialchars($date_creation); ?></p>
                            <p><span class="info-label">Date de modification :</span> <?php echo htmlspecialchars($date_modification); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="surveillants.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Retour à la liste des surveillants</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
