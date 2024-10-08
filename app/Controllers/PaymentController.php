<?php
// controllers/PaymentController.php

require_once 'Models/PaymentModel.php';

class PaymentController {
    private $model;

    public function __construct($db) {
        $this->model = new PaymentModel($db);
    }

    // Afficher les heures et calculer le salaire
    public function afficherSalaire($id_professeur) {
        $salaire = $this->model->calculerSalaire($id_professeur);
        include 'Views/Payments/Payment.php'; // Vue pour afficher le salaire
    }

    // Traiter le paiement
    public function payerProfesseur($id_professeur) {
        $salaire = $this->model->calculerSalaire($id_professeur);
        if ($this->model->effectuerPaiement($id_professeur, $salaire)) {
            $this->envoyerNotification($id_professeur, $salaire);
            echo "Paiement effectué avec succès.";
        } else {
            echo "Erreur lors du paiement.";
        }
    }

    // Envoyer une notification au paiement
    private function envoyerNotification($id_professeur, $montant) {
        $professeurEmail = $this->getEmailProfesseur($id_professeur);
        // Logique pour envoyer un email
        mail($professeurEmail, "Paiement reçu", "Vous avez reçu un paiement de $montant FCFA.");
    }

    private function getEmailProfesseur($id_professeur) {
        // Récupérer l'email de l'enseignant
        // Cette fonction pourrait être dans le modèle également
    }
}
