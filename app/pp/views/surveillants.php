<?php
// Connexion à la base de données
require_once '../config/config.php'; // Assurez-vous que le fichier config contient les bonnes informations pour la connexion

// Fonction pour générer un matricule unique
function generateUniqueMatricule($db) {
    $month = date('m'); // Récupère le mois courant (format numérique avec zéro devant)
    $year = date('y'); // Récupère les deux derniers chiffres de l'année
    do {
        $randomNumber = rand(1000, 9999); // Génère un nombre aléatoire à quatre chiffres
        $matricule = 'SUP' . $month . $year . $randomNumber; // Construit le matricule
        $sql = "SELECT COUNT(*) FROM surveillants WHERE matricule = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$matricule]);
        $count = $stmt->fetchColumn(); // Récupère le nombre de matricules existants
    } while ($count > 0); // Si le matricule existe déjà, générez-en un autre

    return $matricule; // Retourne un matricule unique
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) {
    // Récupérer les données du formulaire
    $num_identite = $_POST['num_identite'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_naissance = $_POST['date_naissance'];
    $adresse = $_POST['adresse'];
    $sexe = $_POST['sexe'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT); // Hacher le mot de passe
    $niveau = $_POST['niveau'];
    $date_embauche = $_POST['date_embauche'];
    $statut = $_POST['statut'];

    // Générer un matricule unique
    $matricule = generateUniqueMatricule($db);

    // Insérer les données dans la base de données
    $sql = "INSERT INTO surveillants (matricule, num_identite, nom, prenom, date_naissance, adresse, sexe, telephone, email, mot_de_passe, niveau, date_embauche, statut)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $db->prepare($sql);

    if ($stmt->execute([$matricule, $num_identite, $nom, $prenom, $date_naissance, $adresse, $sexe, $telephone, $email, $mot_de_passe, $niveau, $date_embauche, $statut])) {
        echo "Surveillant ajouté avec succès !";
    } else {
        echo "Erreur lors de l'ajout du surveillant.";
    }
}

// Récupérer la liste des surveillants
function listSurveillants($db) {
    $sql = "SELECT * FROM surveillants";
    return $db->query($sql);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Ajoutez ici les styles pour la mise en page */

    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4" style="margin-top: 250px;">
            <h3 class="title">Liste des Surveillants</h3>
            <button type="button" id="btn1" class="btn btn-primary mb-4" data-toggle="modal" data-target="#addSurveillantModal">
                Ajouter Surveillant
            </button>
          

            <!-- Modal pour ajouter un surveillant -->
            <div class="modal fade" id="addSurveillantModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Ajouter un Surveillant</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post">
                                <!-- Formulaire pour ajouter un surveillant -->
                                <input type="hidden" name="id_surveillant" value="">
                                <div class="form-row">
                                    <fieldset class="personal-info">
                                        <legend>Informations Personnelles</legend>
                                        <div class="form-group">
                                            <label for="num_identite">Numéro d'identité</label>
                                            <input type="text" class="form-control" id="num_identite" name="num_identite" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="nom">Nom</label>
                                            <input type="text" class="form-control" id="nom" name="nom" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="prenom">Prénom</label>
                                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="date_naissance">Date de Naissance</label>
                                            <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                                        </div>
                                    </fieldset>

                                    <fieldset class="contact-info">
                                        <legend>Coordonnées</legend>
                                        <div class="form-group">
                                            <label for="adresse">Adresse</label>
                                            <input type="text" class="form-control" id="adresse" name="adresse" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="sexe">Sexe</label>
                                            <select class="form-control" id="sexe" name="sexe" required>
                                                <option value="">Choisir...</option>
                                                <option value="Homme">Homme</option>
                                                <option value="Femme">Femme</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="telephone">Téléphone</label>
                                            <input type="tel" class="form-control" id="telephone" name="telephone" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                    </fieldset>
                                </div>

                                <!-- Informations professionnelles -->
                                <fieldset class="professional-info">
                                    <legend>Informations Professionnelles</legend>
                                    <div class="form-group">
                                        <label for="mot_de_passe">Mot de Passe</label>
                                        <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="niveau">Niveau</label>
                                        <select class="form-control" id="niveau" name="niveau" required>
                                            <option value="" disabled selected>Choisir un niveau...</option>
                                            <option value="Primaire">Primaire</option>
                                            <option value="Secondaire">Secondaire</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="date_embauche">Date d'Embauche</label>
                                        <input type="date" class="form-control" id="date_embauche" name="date_embauche" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="statut">Statut</label>
                                        <select class="form-control" id="statut" name="statut" required>
                                            <option value="Actif">Actif</option>
                                            <option value="En Congé">En Congé</option>
                                            <option value="Inactif">Inactif</option>
                                        </select>
                                    </div>

                                </fieldset>

                                <button type="submit" name="create" class="btn btn-primary">Ajouter Surveillant</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Affichage de la liste des surveillants -->
          <table class="table table-striped table-bordered table-hover">
              <thead class="table-light">
                  <tr>
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
        $surveillants = listSurveillants($db);
        while ($row = $surveillants->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
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
                <td class='d-flex'>
                    <a href='views/modifier_surveillant.php?id={$row['id_surveillant']}' class='btn btn-success mr-2'>
                        <i class='fas fa-edit' style='color: white; font-size: 0.8em;'></i>
                    </a>
                    <a href='delete.php?id={$row['id_surveillant']}' class='btn btn-danger mr-2'>
                        <i class='fas fa-trash-alt' style='color: white; font-size: 0.8em;'></i>
                    </a>
                    <a href='archive.php?id={$row['id_surveillant']}' class='btn btn-warning' title='Archiver'>
                        <i class='fas fa-archive' style='color: white; font-size: 0.8em;'></i>
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
