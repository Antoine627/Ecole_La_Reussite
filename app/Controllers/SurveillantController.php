<?php
require 'Models/Surveillant.php';

class SurveillantController {
    private $surveillant;

    public function __construct($db) {
        $this->surveillant = new Surveillant($db);
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['create'])) {
                $this->surveillant->createUser($_POST['num_identite'], $_POST['nom'], $_POST['prenom'], $_POST['date_naissance'], $_POST['adresse'], $_POST['sexe'], $_POST['telephone'], $_POST['email'], $_POST['mot_de_passe'], $_POST['matricule'],
                $_POST['niveau'], $_POST['date_embauche'], $_POST['salaire'], $_POST['statut'], $_POST['role']);
            } elseif (isset($_POST['update'])) {
                $this->surveillant->updateUser($_POST['id_surveillant'], $_POST['num_identite'], $_POST['nom'], $_POST['prenom'], $_POST['date_naissance'], $_POST['adresse'], $_POST['sexe'], $_POST['telephone'], $_POST['email'], $_POST['mot_de_passe'],
                $_POST['matricule'], $_POST['niveau'], $_POST['date_embauche'], $_POST['salaire'], $_POST['statut'], $_POST['role']);
            } elseif (isset($_POST['delete'])) {
                $this->surveillant->deleteUser($_POST['id_surveillant']);
            }
        }
    }

    public function listUsers() {
        return $this->surveillant->getUsers();
    }
}
?>
