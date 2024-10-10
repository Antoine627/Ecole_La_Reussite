<?php
session_start(); // Démarrer la session

// Connexion à la base de données
try {
    $db = new PDO('mysql:host=localhost;dbname=school_success;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM surveillants";
    $stmt = $db->query($query);
    $surveillants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupération des données d'un surveillant pour modification
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $query = "SELECT * FROM surveillants WHERE id_surveillant = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        $surveillant = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifiez si le surveillant existe
        if (!$surveillant) {
            echo "Surveillant non trouvé.";
            exit;
        }
    }
} catch (PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
}

// Récupérer le message de succès
$successMessage = isset($_SESSION['success']) ? $_SESSION['success'] : '';
unset($_SESSION['success']); // Effacer le message après l'avoir affiché

// Fonction pour générer un matricule unique
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

// Fonction pour générer un mot de passe aléatoire
function generateRandomPassword($length = 6) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
    $charactersLength = strlen($characters);
    $randomPassword = '';

    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomPassword;
}

// Vérification de la méthode de requête pour l'ajout d'un nouveau surveillant
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) {
    try {
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $date_naissance = trim($_POST['date_naissance']);
        $adresse = trim($_POST['adresse']);
        $sexe = trim($_POST['sexe']);
        $telephone = trim($_POST['telephone']);
        $email = trim($_POST['email']);
        $niveau = trim($_POST['niveau']);
        $date_embauche = trim($_POST['date_embauche']);
        $statut = trim($_POST['statut']);

        // Vérification de l'unicité du numéro de téléphone
        $sql = "SELECT COUNT(*) FROM surveillants WHERE telephone = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$telephone]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $_SESSION['error'] = "Erreur : Ce numéro de téléphone est déjà utilisé.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        // Gestion de l'upload de la photo
        $photo = '';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
            $filename = $_FILES["photo"]["name"];
            $filetype = $_FILES["photo"]["type"];
            $filesize = $_FILES["photo"]["size"];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            $upload_dir = "uploads/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Validation de l'extension du fichier
            if (!array_key_exists($ext, $allowed)) {
                die("Erreur : Veuillez sélectionner un format de fichier valide (jpg, jpeg, gif, png).");
            }

            // Validation de la taille du fichier
            $maxsize = 5 * 1024 * 1024; // 5 Mo
            if ($filesize > $maxsize) {
                die("Erreur: La taille du fichier dépasse la limite autorisée de 5 Mo.");
            }

            // Validation du type MIME du fichier
            if (!in_array($filetype, $allowed)) {
                die("Erreur : Le type de fichier n'est pas autorisé.");
            }

            $new_filename = uniqid() . "." . $ext;

            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $upload_dir . $new_filename)) {
                $photo = $new_filename;
            } else {
                echo "Erreur: Problème lors du téléchargement de votre fichier. Veuillez réessayer.";
            }
        }

        // Génération du mot de passe et du matricule
        $mot_de_passe = generateRandomPassword();
        $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $matricule = generateUniqueMatricule($db);

        // Insertion dans la base de données
        $sql = 'INSERT INTO surveillants (nom, prenom, date_naissance, adresse, sexe, telephone, email, mot_de_passe, niveau, date_embauche, statut, matricule, photo)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $db->prepare($sql);

        if ($stmt->execute([$nom, $prenom, $date_naissance, $adresse, $sexe, $telephone, $email, $mot_de_passe_hash, $niveau, $date_embauche, $statut, $matricule, $photo])) {
            // Rediriger avec un message de succès
            $_SESSION['success'] = "Surveillant ajouté avec succès ! Mot de passe généré : " . htmlspecialchars($mot_de_passe);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['error'] = "Erreur lors de l'insertion.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur : " . htmlspecialchars($e->getMessage());
    }
}

// Mise à jour des informations d'un surveillant
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id_surveillant = intval($_POST['id_surveillant']);
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $date_naissance = htmlspecialchars($_POST['date_naissance']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $sexe = htmlspecialchars($_POST['sexe']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $email = htmlspecialchars($_POST['email']);
    $niveau = htmlspecialchars($_POST['niveau']);
    $date_embauche = htmlspecialchars($_POST['date_embauche']);
    $statut = htmlspecialchars($_POST['statut']);

    // Mettez à jour le surveillant dans la base de données
    $sql = "UPDATE surveillants SET nom = :nom, prenom = :prenom, date_naissance = :date_naissance, 
            adresse = :adresse, sexe = :sexe, telephone = :telephone, email = :email, niveau = :niveau, 
            date_embauche = :date_embauche, statut = :statut WHERE id_surveillant = :id_surveillant";
    $stmt = $db->prepare($sql);
    $result = $stmt->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'date_naissance' => $date_naissance,
        'adresse' => $adresse,
        'sexe' => $sexe,
        'telephone' => $telephone,
        'email' => $email,
        'niveau' => $niveau,
        'date_embauche' => $date_embauche,
        'statut' => $statut,
        'id_surveillant' => $id_surveillant
    ]);

    if ($result) {
        $_SESSION['success'] = "Le surveillant a été mis à jour avec succès.";
    } else {
        $_SESSION['error'] = "Erreur lors de la mise à jour du surveillant.";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Affichage des messages de succès ou d'erreur
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']); // Supprimer le message après l'affichage
}

if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']); // Supprimer le message après l'affichage
}

// Vérifier si le formulaire de confirmation a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['archive_id'])) {
    $id_surveillant = $_POST['archive_id'];

    try {
        // Démarrer une transaction
        $db->beginTransaction();

        // Mettre à jour le statut d'archivage du surveillant dans la table 'surveillants'
        $queryUpdate = "UPDATE surveillants SET statut = 'Archivé' WHERE id_surveillant = ?";
        $stmtUpdate = $db->prepare($queryUpdate);
        $stmtUpdate->execute([$id_surveillant]);

        // Valider la transaction
        $db->commit();

        // Redirection vers la même page pour éviter le double envoi
        header('Location: surveillants.php');
        exit();
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $db->rollBack();
        echo "Erreur lors de l'archivage du surveillant : " . $e->getMessage();
    }
}

// Récupérer les surveillants (ajoutez votre logique de récupération ici)
$surveillants = $db->query("SELECT * FROM surveillants WHERE statut != 'Archivé'")->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Surveillants</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
          height: 100%; /* Assurez-vous que le body prend la pleine hauteur de l'écran */
          margin: 0;
          padding: 0;
          overflow-y: scroll;
          background: #f4f4f4;
      }

      .container {
          min-height: 100vh; /* 100% de la hauteur de l'écran */
          padding-bottom: 20px; /* Espace en bas pour éviter que le dernier élément ne touche le bas */
      }
        /* Réduire la taille de la police du tableau et des boutons */
        table {
            font-size: 0.9rem;
        }
        .btn {
            font-size: 0.8rem;
            padding: 0.3rem 0.5rem;
        }
        .img-fluid {
            width: 40px;
            height: 40px;
        }
        td, th {
            padding: 0.2rem;
        }
        /* Centrer le tableau */
        .table-container {
          margin: 20px auto; /* Centre le conteneur */
          max-width: 1200px; /* Définit une largeur maximale pour le tableau */
          padding: 20px; /* Ajoute un peu de padding autour du tableau */
          background-color: #ffffff; /* Couleur de fond blanche pour le tableau */
          border-radius: 8px; /* Coins arrondis */
          box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Ombre légère pour le tableau */
        }

        .table {
            width: 100%; /* Le tableau prend toute la largeur du conteneur */
            border-collapse: collapse; /* Supprime les espaces entre les cellules */
        }
        .icon-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 30px;
            border: 1px solid #d9d9d9;
            border-radius: 5px;
            color: #696969;
            text-decoration: none;
            transition: background-color 0.3s;
            margin-right: 5px;
        }
        .icon-link i {
            font-size: 15px;
        }
        .icon-link:hover {
            background-color: #f0f0f0;
        }

        .table th, .table td {
            text-align: center; /* Centre le texte dans les cellules */
            padding: 12px; /* Ajoute un peu de padding aux cellules */
        }

        .table th {
            background-color: #007bff; /* Couleur de fond pour les en-têtes */
            color: white; /* Couleur du texte pour les en-têtes */
        }

        .table img {
            width: 50px; /* Largeur fixe pour les images */
            height: auto; /* Hauteurs automatiques pour garder le ratio */
        }

        .overlay {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Couleur de fond sombre semi-transparente */
            z-index: 999; /* Assure que l'overlay est au-dessus du contenu */
        }


        .overlay.show {
            display: block;
        }

    .popup {
      display: none;
      position: fixed;
      left: 50%;
      top: 10%;
      transform: translate(-50%, -50%);
      background: #ccc;
      padding: 20px;
      border: 1px solid #fff;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
      z-index: 1000; /* Assure que le popup est au-dessus de l'overlay */
      width: 800px;
      max-width: 90%;
      border-radius: 8px;
      text-align: center;
      font-size: 20px;
    }

    .popup .close {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
    font-size: 20px;
    color: #333;
    transition: color 0.3s;
}

.popup p {
    margin: 0;
    padding: 10px 0;
    color: #333;
}
    </style>
</head>
<body>
    <div class="container">
        <h3 class="my-4">Liste des Surveillants</h3>
        <button class="btn btn-primary my-4" data-toggle="modal" data-target="#createSurveillantModal">Ajouter un Surveillant</button>


        <!-- Overlay pour griser la page -->
          <div id="popup-overlay" class="overlay" style="display: none;" onclick="closePopup()"></div>

          <!-- Popup message -->
          <?php
          // Combine messages into an array for easier handling
          $messages = [];
          if (!empty($successMessage)) {
              $messages[] = htmlspecialchars($successMessage);
          }
          ?>

          <?php if (!empty($messages)): ?>
              <div id="success-popup" class="popup" style="display: flex;">
                  <span class="close" onclick="closePopup()">&times;</span>
                  <p><?php echo implode('<br>', $messages); // Combine messages with a line break ?></p>
              </div>
          <?php endif; ?>



        <div class="modal fade" id="createSurveillantModal" tabindex="-1" role="dialog" aria-labelledby="createSurveillantModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createSurveillantModalLabel">Ajouter un Surveillant</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nom">Nom</label>
                                <input type="text" class="form-control" name="nom" required>
                            </div>
                            <div class="form-group">
                                <label for="prenom">Prénom</label>
                                <input type="text" class="form-control" name="prenom" required>
                            </div>
                            <div class="form-group">
                                <label for="date_naissance">Date de Naissance</label>
                                <input type="date" class="form-control" name="date_naissance" max="2000-12-31" required>
                            </div>
                            <div class="form-group">
                                <label for="adresse">Adresse</label>
                                <input type="text" class="form-control" name="adresse">
                            </div>
                            <div class="form-group">
                                <label for="sexe">Sexe</label>
                                <select class="form-control" name="sexe" required>
                                    <option value="Homme">Homme</option>
                                    <option value="Femme">Femme</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="telephone">Téléphone</label>
                                <input type="text" class="form-control" name="telephone" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="niveau">Niveau</label>
                                <select class="form-control" name="niveau" required>
                                    <option value="primaire">Primaire</option>
                                    <option value="secondaire">Secondaire</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="date_embauche">Date d'Embauche</label>
                                <input type="date" class="form-control" name="date_embauche" required>
                            </div>
                            <div class="form-group">
                                <label for="statut">Statut</label>
                                <select class="form-control" name="statut" required>
                                    <option value="actif">Actif</option>
                                    <option value="inactif">Inactif</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="photo">Photo</label>
                                <input type="file" class="form-control" name="photo" accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary" name="create">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Modal pour modifier un Surveillant -->
<div class="modal fade" id="editSurveillantModal" tabindex="-1" role="dialog" aria-labelledby="editSurveillantModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSurveillantModalLabel">Modifier un Surveillant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_surveillant" id="editSurveillantId">
                    <div class="form-group">
                        <label for="editNom">Nom</label>
                        <input type="text" class="form-control" name="nom" id="editNom" required>
                    </div>
                    <div class="form-group">
                        <label for="editPrenom">Prénom</label>
                        <input type="text" class="form-control" name="prenom" id="editPrenom" required>
                    </div>
                    <div class="form-group">
                        <label for="editDateNaissance">Date de Naissance</label>
                        <input type="date" class="form-control" name="date_naissance" id="editDateNaissance" max="2000-12-31" required>
                    </div>
                    <div class="form-group">
                        <label for="editAdresse">Adresse</label>
                        <input type="text" class="form-control" name="adresse" id="editAdresse">
                    </div>
                    <div class="form-group">
                        <label for="editSexe">Sexe</label>
                        <select class="form-control" name="sexe" id="editSexe" required>
                            <option value="Homme">Homme</option>
                            <option value="Femme">Femme</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editTelephone">Téléphone</label>
                        <input type="text" class="form-control" name="telephone" id="editTelephone" required>
                    </div>
                    <div class="form-group">
                        <label for="editEmail">Email</label>
                        <input type="email" class="form-control" name="email" id="editEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="editNiveau">Niveau</label>
                        <select class="form-control" name="niveau" id="editNiveau" required>
                            <option value="primaire">Primaire</option>
                            <option value="secondaire">Secondaire</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editDateEmbauche">Date d'Embauche</label>
                        <input type="date" class="form-control" name="date_embauche" id="editDateEmbauche" required>
                    </div>
                    <div class="form-group">
                        <label for="editStatut">Statut</label>
                        <select class="form-control" name="statut" id="editStatut" required>
                            <option value="Actif">Actif</option>
                            <option value="En Congé">En Congé</option>
                            <option value="Inactif">Inactif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary" name="update">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>


        <div>
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                      <th>Matricule</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Date de Naissance</th>
                        <th>Adresse</th>
                        <th>Sexe</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Statut</th>
                        <th>Niveau</th>
                        <th>Date d'Embauche</th>
                        <th>Photo</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($surveillants as $surveillant): ?>
                        <tr>
                          <td><?php echo htmlspecialchars($surveillant['matricule']); ?></td>
                            <td><?php echo htmlspecialchars($surveillant['nom']); ?></td>
                            <td><?php echo htmlspecialchars($surveillant['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($surveillant['date_naissance']); ?></td>
                            <td><?php echo htmlspecialchars($surveillant['adresse']); ?></td>
                            <td><?php echo htmlspecialchars($surveillant['sexe']); ?></td>
                            <td><?php echo htmlspecialchars($surveillant['telephone']); ?></td>
                            <td><?php echo htmlspecialchars($surveillant['email']); ?></td>
                            <td><?php echo htmlspecialchars($surveillant['statut']); ?></td>
                            <td><?php echo htmlspecialchars($surveillant['niveau']); ?></td>
                            <td><?php echo htmlspecialchars($surveillant['date_embauche']); ?></td>
                            <td>
                                <img src="uploads/<?php echo htmlspecialchars($surveillant['photo']); ?>" class="img-fluid" alt="Photo" style="width: 70px; height:50px;">
                            </td>
                            <td>
                                <button class="btn btn-warning" onclick="editSurveillant(<?php echo htmlspecialchars(json_encode($surveillant)); ?>)" data-toggle="modal" data-target="#editSurveillantModal">Modifier</button>
                                <button class="btn btn-danger" onclick="openPopup(<?php echo htmlspecialchars($surveillant['id_surveillant']); ?>)">Archiver</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>


    <!-- Overlay pour griser la page -->
<div id="popup-overlay" class="overlay" onclick="closePopup()"></div>

<!-- Pop-up de confirmation -->
<div id="confirmation-popup" class="popup">
   <h4>Confirmation d'Archivage</h4>
   <p>Êtes-vous sûr de vouloir archiver le surveillant ?</p>
   <form method="POST" id="archiveForm">
       <input type="hidden" name="archive_id" id="archive_id">
       <button type="submit" class="btn btn-danger">Oui, archiver</button>
       <button type="button" class="btn btn-secondary" onclick="closePopup()">Non, retourner</button>
   </form>
</div>


</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script>
document.getElementById('datePicker').addEventListener('change', function() {
    const selectedDate = new Date(this.value);
    const year = selectedDate.getFullYear();

    if (year >= 2000) {
        document.getElementById('message').innerText = 'Veuillez sélectionner une date antérieure à l\'année 2001.';
        this.value = ''; // Réinitialise le champ si la date est invalide
    } else {
        document.getElementById('message').innerText = 'Date valide : ' + this.value;
    }
});


    document.addEventListener("DOMContentLoaded", function() {
const form = document.querySelector("form");

form.addEventListener("submit", function(event) {
    let valid = true;
    const telInput = document.getElementById("telephone");
    const emailInput = document.getElementById("email");

    // Validation du numéro de téléphone
    const phoneRegex = /^[0-9]{9}$/; // Modifié pour 9 chiffres
    if (!phoneRegex.test(telInput.value)) {
        alert("Le numéro de téléphone doit contenir 9 chiffres.");
        valid = false;
    }

    // Validation de l'email
    if (emailInput.value.trim() === "") {
        alert("L'email ne peut pas être vide.");
        valid = false;
    }

    // Si le formulaire n'est pas valide, empêcher l'envoi
    if (!valid) {
        event.preventDefault();
    }
});
});


    function closePopup() {
        document.getElementById('popup-overlay').style.display = 'none';
        document.getElementById('success-popup').style.display = 'none';
    }

    function showPopup() {
        document.getElementById('popup-overlay').style.display = 'block'; // Affiche l'overlay
        document.getElementById('success-popup').style.display = 'flex'; // Affiche le popup

        // Ferme le popup après 5 secondes (5000 ms)
        setTimeout(closePopup, 5000);
    }

    // Affiche le popup si des messages existent
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (!empty($messages)): ?>
            showPopup(); // Appelle la fonction pour afficher le popup
        <?php endif; ?>
    });


    function openPopup(id) {
                document.getElementById('archive_id').value = id; // Stocker l'ID dans le champ caché
                document.getElementById('popup-overlay').style.display = 'block';
                document.getElementById('confirmation-popup').style.display = 'block';
            }

            function closePopup() {
                document.getElementById('popup-overlay').style.display = 'none';
                document.getElementById('confirmation-popup').style.display = 'none';
            }
</script>
</html>