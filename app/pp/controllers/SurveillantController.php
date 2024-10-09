<?php
require_once '../models/Surveillant.php';

class SurveillantController {
    private $surveillant;

    // Constructeur : initialise le modèle Surveillant avec la connexion à la base de données
    public function __construct($db) {
        $this->surveillant = new Surveillant($db);
    }

    // Getter pour accéder à l'instance de Surveillant
    public function getSurveillant() {
        return $this->surveillant;
    }


    // Fonction principale pour gérer les requêtes POST
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Création d'un nouveau surveillant
                if (isset($_POST['create'])) {
                    if ($this->validateSurveillantForm($_POST)) {
                        $photo = $this->uploadPhoto($_FILES['photo']);
                        if ($photo === false) {
                            echo "Erreur lors de l'upload de la photo.";
                            return;
                        }

                        $statut = isset($_POST['statut']) ? $_POST['statut'] : 'actif';

                        // Vérification si l'email ou le téléphone existe déjà
                        if ($this->surveillant->emailExists($_POST['email']) || $this->surveillant->telephoneExists($_POST['telephone'])) {
                            echo "Erreur : L'email ou le numéro de téléphone est déjà utilisé.";
                            return;
                        }

                        // Création du surveillant
                        $this->surveillant->createUser(
                            trim($_POST['nom']),
                            trim($_POST['prenom']),
                            trim($_POST['date_naissance']),
                            trim($_POST['adresse']),
                            trim($_POST['sexe']),
                            trim($_POST['telephone']),
                            trim($_POST['email']),
                            trim($_POST['niveau']),
                            trim($_POST['date_embauche']),
                            $statut,
                            trim($_POST['role']),
                            $photo
                        );
                        echo "Surveillant créé avec succès.";
                    } else {
                        echo "Erreur : Tous les champs obligatoires ne sont pas remplis.";
                    }
                }
                // Mise à jour d'un surveillant existant
                elseif (isset($_POST['update'])) {
                    if ($this->validateSurveillantForm($_POST, true)) {
                        $photo = $this->uploadPhoto($_FILES['photo']);
                        if ($photo === false) {
                            echo "Erreur lors de l'upload de la photo.";
                            return;
                        }

                        $statut = isset($_POST['statut']) ? $_POST['statut'] : 'actif';

                        $this->surveillant->updateUser(
                            trim($_POST['id_surveillant']),
                            trim($_POST['nom']),
                            trim($_POST['prenom']),
                            trim($_POST['date_naissance']),
                            trim($_POST['adresse']),
                            trim($_POST['sexe']),
                            trim($_POST['telephone']),
                            trim($_POST['email']),
                            trim($_POST['mot_de_passe']),
                            trim($_POST['matricule']),
                            trim($_POST['niveau']),
                            trim($_POST['date_embauche']),
                            $statut,
                            trim($_POST['role']),
                            $photo
                        );
                        echo "Surveillant mis à jour avec succès.";
                    } else {
                        echo "Erreur : Tous les champs obligatoires ne sont pas remplis.";
                    }
                }
                // Suppression d'un surveillant
                elseif (isset($_POST['delete'])) {
                    if (isset($_POST['id_surveillant']) && !empty(trim($_POST['id_surveillant']))) {
                        $this->surveillant->deleteUser(trim($_POST['id_surveillant']));
                        echo "Surveillant supprimé avec succès.";
                    } else {
                        echo "Erreur : ID du surveillant manquant.";
                    }
                }
            } catch (Exception $e) {
                echo "Erreur : " . $e->getMessage();
            }
        }
    }

    // Fonction pour uploader la photo
    private function uploadPhoto($file) {
        if (!isset($file['error']) || $file['error'] != UPLOAD_ERR_OK) {
            return false; // Erreur lors de l'upload
        }

        // Vérification du type de fichier et de la taille
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes) || $file['size'] > 2 * 1024 * 1024) {
            return false; // Type ou taille de fichier non autorisé
        }

        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = basename($file['name']);
        $filePath = $uploadDir . uniqid() . '-' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return $filePath;
        } else {
            return false;
        }
    }

    // Validation du formulaire
    private function validateSurveillantForm($data, $isUpdate = false) {
        $requiredFields = ['nom', 'prenom', 'date_naissance', 'adresse', 'sexe', 'email', 'niveau', 'date_embauche', 'role'];
        $errors = [];

        if ($isUpdate) {
            $requiredFields[] = 'id_surveillant';
            if (empty(trim($data['mot_de_passe']))) {
                unset($requiredFields[array_search('mot_de_passe', $requiredFields)]);
            }
        }

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                $errors[] = "Le champ $field est vide.";
            }
        }

        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Le champ email n'est pas valide.";
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "Erreur : $error<br>";
            }
            return false;
        }

        return true;
    }

    // Liste des surveillants
    public function listUsers() {
        return $this->surveillant->getUsers();
    }
}
