<?php
require_once 'models/Surveillant.php';

class SurveillantController {
    private $surveillant;

    public function __construct($db) {
        $this->surveillant = new Surveillant($db);
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            // Création d'un nouveau surveillant
            if (isset($_POST['create'])) {
                if ($this->validateSurveillantForm($_POST)) {
                    // Si le statut n'est pas fourni, on le définit à un statut par défaut (ex : 'actif')
                    $statut = isset($_POST['statut']) ? $_POST['statut'] : 'actif';

                    $this->surveillant->createUser(
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
                        $statut, // Utilisation du statut défini ou par défaut
                        trim($_POST['role'])
                    );
                    echo "Surveillant créé avec succès.";
                } else {
                    echo "Erreur : Tous les champs obligatoires ne sont pas remplis.";
                }
            }
            // Mise à jour d'un surveillant existant
            elseif (isset($_POST['update'])) {
                if ($this->validateSurveillantForm($_POST, true)) {
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
                        trim($_POST['role'])
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
        }
    }

    // Validation du formulaire
    private function validateSurveillantForm($data, $isUpdate = false) {
        $requiredFields = ['nom', 'prenom', 'date_naissance', 'adresse', 'sexe', 'telephone', 'email', 'mot_de_passe', 'matricule', 'niveau', 'date_embauche', 'role'];

        if ($isUpdate) {
            $requiredFields[] = 'id_surveillant';
            // Pour la mise à jour, le mot de passe peut être ignoré s'il n'est pas fourni
            if (empty(trim($data['mot_de_passe']))) {
                unset($requiredFields[array_search('mot_de_passe', $requiredFields)]);
            }
        }

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                echo "Erreur : Le champ $field est vide.<br>"; // Message d'erreur pour chaque champ manquant
                return false;
            }
        }
        return true;
    }

    // Liste des surveillants
    public function listUsers() {
        return $this->surveillant->getUsers();
    }
}
