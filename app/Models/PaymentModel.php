<?php
// models/PaymentModel.php

class PaymentModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Obtenir les heures de cours d'un enseignant
    public function getHeuresCours($id_professeur) {
        $query = $this->db->prepare("SELECT SUM(heures) as total_heures FROM cours WHERE id_professeur = ?");
        $query->execute([$id_professeur]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // Calcul du salaire
    public function calculerSalaire($id_professeur) {
        $heuresCours = $this->getHeuresCours($id_professeur);
        $tauxHoraire = $this->getTauxHoraire($id_professeur); // Fonction pour obtenir le taux par matière
        $salaire = $heuresCours['total_heures'] * $tauxHoraire;
        return $salaire;
    }

    // Effectuer le paiement
    public function effectuerPaiement($id_professeur, $montant) {
        $query = $this->db->prepare("INSERT INTO paiements (id_professeur, montant, date_paiement) VALUES (?, ?, NOW())");
        return $query->execute([$id_professeur, $montant]);
    }

    // Obtenir le taux horaire de l'enseignant (selon la matière enseignée)
    public function getTauxHoraire($id_professeur) {
        $query = $this->db->prepare("SELECT taux_horaire FROM id_professeur WHERE id = ?");
        $query->execute([$id_professeur]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['taux_horaire'];
    }

    // Récupérer l'historique des paiements
    public function getPaiements($id_professeur) {
        $query = $this->db->prepare("SELECT * FROM paiements WHERE id_professeur = ?");
        $query->execute([$id_professeur]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
