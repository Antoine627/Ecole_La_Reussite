<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/config.php';

function generateUniqueMatricule($db) {
    $month = date('m');
    $year = date('y');
    do {
        $randomNumber = rand(1000, 9999);
        $matricule = 'SUP' . $month . $year . $randomNumber;
        $sql = "SELECT COUNT(*) FROM surveillants WHERE matricule = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$matricule]);
        $count = $stmt->fetchColumn();
    } while ($count > 0);

    return $matricule;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) {
    try {
        $db = new PDO('mysql:host=localhost;dbname=school_success;charset=utf8', 'root', '');
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
        exit;
    }

    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_naissance = $_POST['date_naissance'];
    $adresse = $_POST['adresse'];
    $sexe = $_POST['sexe'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $niveau = $_POST['niveau'];
    $date_embauche = $_POST['date_embauche'];
    $statut = $_POST['statut'];

    $matricule = generateUniqueMatricule($db);

    // Initialisation du chemin de la photo à NULL par défaut
    $photoPath = null;

    // Vérifier si le champ photo est présent et si un fichier a été correctement téléchargé
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        // Récupérer les informations sur le fichier
        $photoTmpName = $_FILES['photo']['tmp_name'];
        $photoName = uniqid() . '-' . basename($_FILES['photo']['name']); // Utilisation d'un nom unique pour éviter les conflits
        $photoPath = 'uploads/' . $photoName;

        // Déplacer le fichier vers le répertoire de destination
        if (!move_uploaded_file($photoTmpName, $photoPath)) {
            echo 'Erreur lors du déplacement du fichier.';
            $photoPath = null; // Réinitialiser si le déplacement échoue
        }
    } else {
        echo 'Aucun fichier photo téléchargé ou une erreur est survenue.';
    }

    // Préparer la requête SQL pour insérer les données, photo incluse si elle est présente
    $stmt = $mysqli->prepare('INSERT INTO surveillants (nom, prenom, email, telephone, adresse, niveau, photo) VALUES (?, ?, ?, ?, ?, ?, ?)');

    if ($stmt) {
        $stmt->bind_param('sssssssssssss', $nom, $prenom, $email, $telephone, $adresse, $niveau, $photoPath);

        if ($stmt->execute()) {
            echo 'Données enregistrées avec succès.';
        } else {
            echo 'Erreur lors de l\'insertion : ' . $stmt->error;
        }

        $stmt->close();
    } else {
        echo 'Erreur lors de la préparation de la requête : ' . $mysqli->error;
    }

    $mysqli->close();
}

?>
