<?php
require_once '../config/config.php';

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

    // Générer un mot de passe aléatoire
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomPassword;
}

// Vérification de la méthode de requête
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) {
    try {
        // Connexion à la base de données
        $db = new PDO('mysql:host=localhost;dbname=school_success;charset=utf8', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Gestion des erreurs

        // Récupérer les données du formulaire
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

        // Gestion de la photo
          $photo = ''; // Photo par défaut
          if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
              $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
              $filename = $_FILES["photo"]["name"];
              $filetype = $_FILES["photo"]["type"];
              $filesize = $_FILES["photo"]["size"];

              // Vérifie l'extension du fichier
              $ext = pathinfo($filename, PATHINFO_EXTENSION);
              if(!array_key_exists($ext, $allowed)) die("Erreur : Veuillez sélectionner un format de fichier valide.");

              // Vérifie la taille du fichier - 5MB maximum
              $maxsize = 5 * 1024 * 1024;
              if($filesize > $maxsize) die("Erreur: La taille du fichier est supérieure à la limite autorisée.");

              // Vérifie le type MIME du fichier
              if(in_array($filetype, $allowed)){
                  // Vérifie si le fichier existe avant de le télécharger.
                  if(file_exists("uploads/" . $_FILES["photo"]["name"])){
                      echo $_FILES["photo"]["name"] . " existe déjà.";
                  } else{
                      move_uploaded_file($_FILES["photo"]["tmp_name"], "uploads/" . $_FILES["photo"]["name"]);
                      $photo = $_FILES["photo"]["name"];
                  }
              } else{
                  echo "Erreur: Il y a eu un problème de téléchargement de votre fichier. Veuillez réessayer.";
              }
          }


        // Générer un mot de passe aléatoire
        $mot_de_passe = generateRandomPassword();
        $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT); // Hachage du mot de passe

        // Générer le matricule unique
        $matricule = generateUniqueMatricule($db);

        // Préparer la requête SQL pour insérer les données
        $sql = 'INSERT INTO surveillants (nom, prenom, date_naissance, adresse, sexe, telephone, email, mot_de_passe, niveau, date_embauche, statut, matricule, photo)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $db->prepare($sql);



        // Exécuter la requête
        if ($stmt->execute([$nom, $prenom, $date_naissance, $adresse, $sexe, $telephone, $email, $mot_de_passe_hash, $niveau, $date_embauche, $statut, $matricule, $photo])) {
            echo "<script>alert('Données enregistrées avec succès. Mot de passe généré : $mot_de_passe');</script>";
            echo "<script type='text/javascript'>document.location ='surveillants.php';</script>"; // Redirection vers la page des surveillants
        } else {
            echo "<script>alert('Erreur lors de l\'insertion.');</script>";
        }
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Surveillant</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #343a40;
            text-align: center;
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        input[type="text"],
        input[type="date"],
        input[type="email"],
        input[type="tel"],
        input[type="password"] {
            border-radius: 5px;
        }

        input[type="file"] {
            padding: 10px;
        }

        .form-control-file {
            border: 1px solid #ced4da;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Ajouter un Surveillant</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" class="form-control" name="nom" required>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" class="form-control" name="prenom" required>
            </div>

            <div class="form-group">
                <label for="date_naissance">Date de Naissance :</label>
                <input type="date" class="form-control" name="date_naissance" max="2000-12-31" required>
            </div>

            <div class="form-group">
                <label for="adresse">Adresse :</label>
                <input type="text" class="form-control" name="adresse" required>
            </div>

            <div class="form-group">
                <label for="sexe">Sexe :</label>
                <select class="form-control" name="sexe" required>
                    <option value="">Sélectionner</option>
                    <option value="Homme">Homme</option>
                    <option value="Femme">Femme</option>
                </select>
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone :</label>
                <input type="tel" class="form-control" name="telephone" required>
            </div>

            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" class="form-control" name="email" required>
            </div>

            <div class="form-group">
                <label for="niveau">Niveau :</label>
                <select class="form-control" name="niveau" required>
                    <option value="">Sélectionner</option>
                    <option value="primaire">Primaire</option>
                    <option value="secondaire">Secondaire</option>
                </select>
            </div>

            <div class="form-group">
                <label for="date_embauche">Date d'Embauche :</label>
                <input type="date" class="form-control" name="date_embauche" required>
            </div>

            <div class="form-group">
                <label for="statut">Statut :</label>
                <select class="form-control" name="statut" required>
                    <option value="actif">Actif</option>
                    <option value="inactif">Inactif</option>
                </select>
            </div>

            <div class="form-group">
                <label for="photo">Photo :</label>
                <input type="file" class="form-control-file" name="photo">
            </div>

            <button type="submit" name="create" class="btn btn-primary">Ajouter</button>
            <a href="surveillants.php" class="btn btn-secondary">Retour</a>
        </form>
    </div>

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
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
