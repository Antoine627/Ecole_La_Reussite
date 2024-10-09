<?php
require_once '../config/config.php'; // Assurez-vous que ce fichier contient les bonnes informations pour la connexion
require_once 'dashboard.php'; // Assurez-vous que ce fichier contient les bonnes informations pour la connexion

// Fonction pour récupérer la liste des surveillants
function listSurveillants($db) {
    $sql = "SELECT * FROM surveillants";
    return $db->query($sql);
}

// Connexion à la base de données
try {
    $db = new PDO('mysql:host=localhost;dbname=school_success;charset=utf8', 'root', ''); // Remplacez par vos informations de connexion
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $surveillants = listSurveillants($db);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . htmlspecialchars($e->getMessage());
    exit();
}

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

// Vérification de la méthode de requête
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

        // Gestion de l'upload de la photo
        $photo = '';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
            $filename = $_FILES["photo"]["name"];
            $filetype = $_FILES["photo"]["type"];
            $filesize = $_FILES["photo"]["size"];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);

            // Validation des extensions et de la taille
            if (!array_key_exists($ext, $allowed)) {
                die("Erreur : Veuillez sélectionner un format de fichier valide.");
            }
            $maxsize = 5 * 1024 * 1024; // 5 Mo
            if ($filesize > $maxsize) {
                die("Erreur: La taille du fichier est supérieure à la limite autorisée.");
            }

            // Déplacement du fichier téléchargé
            if (in_array($filetype, $allowed)) {
                if (file_exists("uploads/" . $filename)) {
                    echo "$filename existe déjà.";
                } else {
                    move_uploaded_file($_FILES["photo"]["tmp_name"], "uploads/" . $filename);
                    $photo = $filename;
                }
            } else {
                echo "Erreur: Problème de téléchargement de votre fichier. Veuillez réessayer.";
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
            echo "<script>alert('Données enregistrées avec succès. Mot de passe généré : $mot_de_passe');</script>";
            echo "<script type='text/javascript'>document.location ='../index.php';</script>";
            header('Location: surveillants.php?success=1&password=' . urlencode($mot_de_passe));
            exit();
        } else {
            echo "<script>alert('Erreur lors de l\'insertion.');</script>";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . htmlspecialchars($e->getMessage());
        exit;
    }
}

// Modification d'un surveillant
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['modify'])) {
    // Ajoutez ici le code pour modifier un surveillant
    // Récupérer les données du formulaire et mettre à jour la base de données
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
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($surveillant['nom']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo htmlspecialchars($surveillant['prenom']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="date_naissance">Date de Naissance</label>
                    <input type="date" class="form-control" id="date_naissance" name="date_naissance" value="<?php echo htmlspecialchars($surveillant['date_naissance']); ?>" max="2000-12-31" required>
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
                const phoneRegex = /^[0-9]{10}$/; // Exemple : 10 chiffres
                if (!phoneRegex.test(telInput.value)) {
                    alert("Le numéro de téléphone doit contenir 10 chiffres.");
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
</script>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
