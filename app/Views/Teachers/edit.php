<?php
// Connexion à la base de données
require_once '../../Config/config.php';

// Vérifier si l'ID du professeur est passé dans l'URL
if (isset($_GET['id'])) {
    $id_professeur = $_GET['id'];

    // Récupérer les informations du professeur
    $query = "SELECT * FROM professeurs WHERE id_professeur = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$id_professeur]);
    $professeur = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$professeur) {
        // Rediriger si le professeur n'existe pas
        header("Location: teacher.php");
        exit();
    }
} else {
    // Rediriger si l'ID n'est pas fourni
    header("Location: teacher.php");
    exit();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    // Récupérer les données du formulaire
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

    // Traitement de la photo si une nouvelle est téléchargée
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["photo"]["name"];
        $filetype = $_FILES["photo"]["type"];
        $filesize = $_FILES["photo"]["size"];

        // Vérifications sur le fichier...

        $photo = "upload/" . uniqid() . "." . pathinfo($filename, PATHINFO_EXTENSION);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $photo);
    } else {
        $photo = $professeur['photo']; // Garder l'ancienne photo si aucune nouvelle n'est téléchargée
    }

    // Mettre à jour les données dans la base de données
    $sql = "UPDATE professeurs SET nom = ?, prenom = ?, date_naissance = ?, adresse = ?, sexe = ?, telephone = ?, email = ?, niveau = ?, date_embauche = ?, statut = ?, photo = ? WHERE id_professeur = ?";
    $stmt = $db->prepare($sql);

    if ($stmt->execute([$nom, $prenom, $date_naissance, $adresse, $sexe, $telephone, $email, $niveau, $date_embauche, $statut, $photo, $id_professeur])) {
        $message = "Professeur modifié avec succès !";
        $modification_reussie = true;
    } else {
        $message = "Erreur lors de la modification du professeur.";
        $modification_reussie = false;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Professeur</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Modifier un Professeur</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $professeur['nom']; ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="prenom">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $professeur['prenom']; ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="date_naissance">Date de Naissance</label>
                    <input type="date" class="form-control" id="date_naissance" name="date_naissance" value="<?php echo $professeur['date_naissance']; ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="adresse">Adresse</label>
                    <input type="text" class="form-control" id="adresse" name="adresse" value="<?php echo $professeur['adresse']; ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="sexe">Sexe</label>
                    <select class="form-control" id="sexe" name="sexe" required>
                        <option value="Homme" <?php echo ($professeur['sexe'] == 'Homme') ? 'selected' : ''; ?>>Masculin</option>
                        <option value="Femme" <?php echo ($professeur['sexe'] == 'Femme') ? 'selected' : ''; ?>>Feminin</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" class="form-control" id="telephone" name="telephone" value="<?php echo $professeur['telephone']; ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $professeur['email']; ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="niveau">Niveau</label>
                    <select class="form-control" id="niveau" name="niveau" required>
                        <option value="Primaire" <?php echo ($professeur['niveau'] == 'Primaire') ? 'selected' : ''; ?>>Primaire</option>
                        <option value="Secondaire" <?php echo ($professeur['niveau'] == 'Secondaire') ? 'selected' : ''; ?>>Secondaire</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="date_embauche">Date d'Embauche</label>
                    <input type="date" class="form-control" id="date_embauche" name="date_embauche" value="<?php echo $professeur['date_embauche']; ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="statut">Statut</label>
                    <select class="form-control" id="statut" name="statut" required>
                        <option value="Actif" <?php echo ($professeur['statut'] == 'Actif') ? 'selected' : ''; ?>>Actif</option>
                        <option value="En Congé" <?php echo ($professeur['statut'] == 'En Congé') ? 'selected' : ''; ?>>En Congé</option>
                        <option value="Inactif" <?php echo ($professeur['statut'] == 'Inactif') ? 'selected' : ''; ?>>Inactif</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="photo">Photo</label>
                <input type="file" class="form-control-file" id="photo" name="photo">
                <?php if ($professeur['photo']): ?>
                    <img src="<?php echo $professeur['photo']; ?>" alt="Photo actuelle" class="mt-2" style="max-width: 100px;">
                <?php endif; ?>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Modifier Professeur</button>
            <a href="teacher.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

    <!-- Modal de succès -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Modification réussie</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo $message; ?>
                </div>
                <div class="modal-footer">
                    <a href="teacher.php" class="btn btn-primary">Retourner à la liste des professeurs</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <?php if (isset($modification_reussie) && $modification_reussie): ?>
    <script>
        $(document).ready(function() {
            $('#successModal').modal('show');
        });
    </script>
    <?php endif; ?>
</body>
</html>