<?php
require_once 'config/database.php'; // Inclure la configuration de la base de données
require_once 'controllers/SurveillantController.php';

// Connexion à la base de données
$db = getDatabaseConnection();

// Initialisation du contrôleur
$controller = new SurveillantController($db);

// Vérification de l'ID du surveillant à archiver
if (isset($_GET['id'])) {
    $id_surveillant = $_GET['id'];
    $surveillant = $controller->surveillant->getUserById($id_surveillant);

    if ($surveillant) {
        // Archiver le surveillant (changer son statut à 'archivé')
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $surveillant['statut'] = 'archivé'; // Mettre à jour le statut
            $controller->surveillant->updateUser(
                $surveillant['id_surveillant'],
                $surveillant['num_identite'],
                $surveillant['nom'],
                $surveillant['prenom'],
                $surveillant['date_naissance'],
                $surveillant['adresse'],
                $surveillant['sexe'],
                $surveillant['telephone'],
                $surveillant['email'],
                $surveillant['mot_de_passe'],
                $surveillant['matricule'],
                $surveillant['niveau'],
                $surveillant['date_embauche'],
                $surveillant['salaire'],
                'archivé', // Changer le statut à "archivé"
                $surveillant['role']
            );

            echo "Le surveillant a été archivé.";
        }
?>
        <h2>Archiver le surveillant</h2>
        <p>Voulez-vous vraiment archiver le surveillant <strong><?= $surveillant['nom'] ?> <?= $surveillant['prenom'] ?></strong> ?</p>
        <form method="POST" action="archiver.php?id=<?= $id_surveillant ?>">
            <input type="submit" value="Archiver">
        </form>
<?php
    } else {
        echo "Surveillant introuvable.";
    }
} else {
    echo "ID de surveillant non fourni.";
}
?>
