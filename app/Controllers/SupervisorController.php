<?php
require_once 'models/SupervisorModel.php'; // Inclure le modèle

class SupervisorController {
    private $model;

    public function __construct($pdo) {
        // Instancier le modèle en passant l'objet PDO
        $this->model = new SupervisorModel($pdo);
    }

    // Afficher tous les surveillants
    public function index() {
        $surveillants = $this->model->getAll();
        include 'views/supervisor_list.php'; // Charger la vue pour afficher la liste des surveillants
    }

    // Créer un nouveau surveillant
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $data = [
                'nom' => $_POST['nom'] ?? null,
                'prenom' => $_POST['prenom'] ?? null,
                'id_admin' => $_POST['id_admin'] ?? null,
                'sexe' => $_POST['sexe'] ?? null,
                'telephone' => $_POST['telephone'] ?? null,
                'email' => $_POST['email'] ?? null,
                'matricule' => $_POST['matricule'] ?? null,
                'niveau' => $_POST['niveau'] ?? null,
                'date_embauche' => $_POST['date_embauche'] ?? null,
                'salaire' => $_POST['salaire'] ?? null,
                'statut' => $_POST['statut'] ?? 'actif' // Par défaut, le statut est 'actif'
            ];

            try {
                // Validation des données (optionnelle)
                foreach ($data as $key => $value) {
                    if (empty($value)) {
                        throw new InvalidArgumentException("Le champ $key est obligatoire.");
                    }
                }

                // Créer un nouveau surveillant via le modèle
                $this->model->create($data);
                header('Location: index.php'); // Redirection après la création
            } catch (Exception $e) {
                // Gérer les erreurs et afficher un message
                echo "Erreur : " . $e->getMessage();
            }
        } else {
            include 'views/supervisor_form.php'; // Charger la vue pour le formulaire de création
        }
    }

    // Modifier un surveillant existant
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données mises à jour du formulaire
            $data = [
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'sexe' => $_POST['sexe'],
                'telephone' => $_POST['telephone'],
                'email' => $_POST['email'],
                'matricule' => $_POST['matricule'],
                'niveau' => $_POST['niveau'],
                'date_embauche' => $_POST['date_embauche'],
                'salaire' => $_POST['salaire'],
                'statut' => $_POST['statut']
            ];

            try {
                // Appeler la méthode de mise à jour dans le modèle
                $this->model->update($id, $data);
                header('Location: index.php'); // Redirection après la mise à jour
            } catch (Exception $e) {
                echo "Erreur : " . $e->getMessage();
            }
        } else {
            // Récupérer les données actuelles du surveillant
            $surveillant = $this->model->getById($id);
            include 'views/supervisor_form.php'; // Charger la vue pour modifier les données
        }
    }

    // Supprimer un surveillant
    public function delete($id) {
        try {
            $this->model->delete($id);
            header('Location: index.php'); // Redirection après suppression
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
}
