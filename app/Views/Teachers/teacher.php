<?php
// Connexion à la base de données
require_once '../../Config/config.php'; // Assurez-vous que le fichier config contient les bonnes informations pour la connexion

// Vérifier si la connexion à la base de données est établie
if (!isset($db) || $db === null) {
    die("Erreur : La connexion à la base de données n'a pas été établie. Vérifiez le fichier config.php.");
}

// Fonction pour générer un matricule unique
function generateUniqueMatricule($db) {
    $month = date('m');
    $year = date('y');
    $attempts = 0;
    do {
        $randomNumber = rand(1000, 9999);
        $matricule = 'MR' . $month . $year . $randomNumber;
        $sql = "SELECT COUNT(*) FROM professeurs WHERE matricule = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$matricule]);
        $count = $stmt->fetchColumn();
        $attempts++;
    } while ($count > 0 && $attempts < 10);

    return $matricule;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) {
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

    // Traitement de la photo
    $photo = 'upload/profil_defaut.jpg';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["photo"]["name"];
        $filetype = $_FILES["photo"]["type"];
        $filesize = $_FILES["photo"]["size"];

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) die("Erreur : Veuillez sélectionner un format de fichier valide.");

        $maxsize = 5 * 1024 * 1024;
        if ($filesize > $maxsize) die("Erreur: La taille du fichier est supérieure à la limite autorisée.");

        if (in_array($filetype, $allowed)) {
            $photo = "upload/" . uniqid() . "." . $ext;
            move_uploaded_file($_FILES["photo"]["tmp_name"], $photo);
        } else {
            echo "Erreur: Il y a eu un problème de téléchargement de votre fichier. Veuillez réessayer."; 
        }
    }

    $matricule = generateUniqueMatricule($db);

    $sql = "INSERT INTO professeurs (matricule, nom, prenom, date_naissance, adresse, sexe, telephone, email, mot_de_passe, niveau, date_embauche, statut, photo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $db->prepare($sql);

    if ($stmt->execute([$matricule, $nom, $prenom, $date_naissance, $adresse, $sexe, $telephone, $email, $mot_de_passe, $niveau, $date_embauche, $statut, $photo])) {
        // Rediriger vers la même page pour éviter la duplication lors du rafraîchissement
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de l'ajout du professeur.</div>";
    }
}

// Récupérer la liste des professeurs
function listProfesseurs($db, $niveau = null, $search = null) {
    $sql = "SELECT * FROM professeurs WHERE archive = 0";
    $params = [];

    if ($niveau) {
        $sql .= " AND niveau = :niveau";
        $params[':niveau'] = $niveau;
    }

    if ($search) {
        $sql .= " AND (email LIKE :search OR nom LIKE :search OR matricule LIKE :search)";
        $params[':search'] = "%$search%";
    }

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Afficher un message de succès si l'ajout a réussi
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "";
}

// Variable pour stocker le résultat de la recherche
$searchResult = null;

// Vérifier si une recherche a été effectuée
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    $searchResult = listProfesseurs($db, null, $search);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Professeurs</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .teacher-photo {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
        }
        .form-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .form-section legend {
            font-weight: bold;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }
        .filter-btn {
            background-color: white;
            color: #007bff;
            border: 1px solid #007bff;
            transition: all 0.3s ease;
        }
        .filter-btn:hover, .filter-btn.active {
            background-color: #007bff;
            color: white;
            transform: scale(1.05);
        }
        @media (max-width: 768px) {
            .container-fluid {
                padding: 10px;
            }
            .table-responsive {
                overflow-x: auto;
            }
            .filter-btn {
                margin-bottom: 10px;
                font-size: 0.9rem;
            }
            .table th, .table td {
                font-size: 0.9rem;
            }
            .btn-sm {
                padding: .25rem .4rem;
                font-size: .875rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="row mb-3">
            <div class="col-12 col-md-6">
                <h3 class="title">Liste des Professeurs</h3>
            </div>
            <div class="col-12 col-md-6 text-md-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProfesseurModal">
                    <i class="fas fa-plus"></i> Ajouter un Professeur
                </button>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12 col-md-6 mb-2 mb-md-0">
                <button class="btn filter-btn mr-2 mb-2" data-filter="all">Tous</button>
                <button class="btn filter-btn mr-2 mb-2" data-filter="Primaire">Primaire</button>
                <button class="btn filter-btn mb-2" data-filter="Secondaire">Secondaire</button>
            </div>
            <div class="col-12 col-md-6">
                <form action="" method="GET" class="form-inline justify-content-md-end">
                    <input type="text" name="search" class="form-control mb-2 mr-sm-2" placeholder="Rechercher...">
                    <button type="submit" class="btn btn-outline-primary mb-2">Rechercher</button>
                </form>
            </div>
        </div>

        <!-- Modal pour ajouter un professeur -->
        <div class="modal fade" id="addProfesseurModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter un Professeur</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nom">Nom</label>
                                    <input type="text" class="form-control" id="nom" name="nom" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="prenom">Prénom</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="date_naissance">Date de naissance</label>
                                    <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="sexe">Sexe</label>
                                    <select class="form-control" id="sexe" name="sexe" required>
                                        <option value="">Choisir...</option>
                                        <option value="Homme">Homme</option>
                                        <option value="Femme">Femme</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="adresse">Adresse</label>
                                <input type="text" class="form-control" id="adresse" name="adresse" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="telephone">Téléphone</label>
                                    <input type="tel" class="form-control" id="telephone" name="telephone" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="mot_de_passe">Mot de passe</label>
                                    <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="niveau">Niveau</label>
                                    <select class="form-control" id="niveau" name="niveau" required>
                                        <option value="">Choisir...</option>
                                        <option value="Primaire">Primaire</option>
                                        <option value="Secondaire">Secondaire</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="date_embauche">Date d'embauche</label>
                                    <input type="date" class="form-control" id="date_embauche" name="date_embauche" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="statut">Statut</label>
                                    <select class="form-control" id="statut" name="statut" required>
                                        <option value="Actif" selected>Actif</option>
                                        <option value="Inactif">Inactif</option>
                                        <option value="En congé">En congé</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="photo">Photo</label>
                                <input type="file" class="form-control-file" id="photo" name="photo">
                            </div>
                            <button type="submit" name="create" class="btn btn-primary">Ajouter le professeur</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal pour signaler qu'aucun professeur n'a été trouvé -->
        <div class="modal fade" id="noProfesseurModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Aucun résultat</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Aucun professeur ne correspond à votre recherche.</p>
                    </div>
                    <div class="modal-footer">
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-secondary">Retour à la liste</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Affichage de la liste des professeurs -->
        <div class="table-responsive mt-4">
            <table class="table table-striped table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <?php
                        $entetes = ["Photo", "Matricule", "Nom", "Prénom", "Téléphone", "Email", "Statut", "Actions"];
                        foreach ($entetes as $entete) {
                            echo "<th>$entete</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody id="professeurs-list">
                    <?php
                    $professeurs = isset($searchResult) ? $searchResult : listProfesseurs($db);
                    foreach ($professeurs as $row) {
                        echo "<tr data-niveau='{$row['niveau']}'>
                            <td><img src='{$row['photo']}' alt='Photo de {$row['nom']}' class='teacher-photo'></td>
                            <td>{$row['matricule']}</td>
                            <td>{$row['nom']}</td>
                            <td>{$row['prenom']}</td>
                            <td>{$row['telephone']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['statut']}</td>
                            <td class='d-flex flex-wrap justify-content-center'>
                                <a href='view.php?id={$row['id_professeur']}' class='btn btn-info btn-sm m-1' title='Consulter'>
                                    <i class='fas fa-eye'></i>
                                </a>
                                <a href='edit.php?id={$row['id_professeur']}' class='btn btn-success btn-sm m-1' title='Modifier'>
                                    <i class='fas fa-edit'></i>
                                </a>
                                <a href='delete.php?id={$row['id_professeur']}' class='btn btn-danger btn-sm m-1' title='Supprimer'>
                                    <i class='fas fa-trash-alt'></i>
                                </a>
                                <a href='?action=archive&id={$row['id_professeur']}' class='btn btn-warning btn-sm m-1' title='Archiver' onclick='return confirm(\"Êtes-vous sûr de vouloir archiver ce professeur ?\");'>
                                    <i class='fas fa-archive'></i>
                                </a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.filter-btn').click(function() {
                $('.filter-btn').removeClass('active');
                $(this).addClass('active');
                var filter = $(this).data('filter');
                if (filter === 'all') {
                    $('#professeurs-list tr').show();
                } else {
                    $('#professeurs-list tr').hide();
                    $('#professeurs-list tr[data-niveau="' + filter + '"]').show();
                }
            });

            <?php if (isset($searchResult) && empty($searchResult)): ?>
            $('#noProfesseurModal').modal('show');
            <?php endif; ?>
        });
    </script>
</body>
</html>