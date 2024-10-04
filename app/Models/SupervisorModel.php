<?php

class SupervisorModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Méthode pour récupérer tous les surveillants avec les informations des employés
    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT surveillants.*, employers.nom, employers.prenom
            FROM surveillants
            JOIN employers ON surveillants.id_surveillant = employers.id_surveillant
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour récupérer un surveillant par son ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT surveillants.*, employers.nom, employers.prenom
            FROM surveillants
            JOIN employers ON surveillants.id_surveillant = employers.id_surveillant
            WHERE surveillants.id_surveillant = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Méthode pour créer un nouveau surveillant
    public function create(array $data) {
        try {
            // Démarrer une transaction
            $this->pdo->beginTransaction();

            // Insertion dans la table employers
            $stmtEmployer = $this->pdo->prepare("
                INSERT INTO employers (num_carte_identite, nom, prenom, date_naissance, adresse, id_professeur, id_surveillant, id_comptable, id_admin, date_creation, date_modification, date_suppression)
                VALUES (:num_carte_identite, :nom, :prenom, :date_naissance, :adresse, :id_professeur, :id_surveillant, :id_comptable, :id_admin, NOW(), NOW(), NULL)
            ");
            $stmtEmployer->execute([
                'num_carte_identite' => $data['num_carte_identite'],
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'date_naissance' => $data['date_naissance'],
                'adresse' => $data['adresse'],
                'id_professeur' => null, // Si nécessaire, sinon à compléter
                'id_surveillant' => null, // Sera généré après
                'id_comptable' => null, // Si nécessaire
                'id_admin' => $data['id_admin'],
            ]);

            // Récupérer l'ID généré pour employer
            $idEmployer = $this->pdo->lastInsertId();

            // Insertion dans la table surveillants avec l'ID de l'employer
            $stmtSurveillant = $this->pdo->prepare("
                INSERT INTO surveillants (id_employer, sexe, telephone, email, mot_de_passe, matricule, niveau, date_embauche, salaire, photo, statut, role, id_admin, id_notification)
                VALUES (:id_employer, :sexe, :telephone, :email, :mot_de_passe, :matricule, :niveau, :date_embauche, :salaire, :photo, :statut, :role, :id_admin, NULL)
            ");
            $stmtSurveillant->execute([
                'id_employer' => $idEmployer,
                'sexe' => $data['sexe'],
                'telephone' => $data['telephone'],
                'email' => $data['email'],
                'mot_de_passe' => password_hash($data['mot_de_passe'], PASSWORD_BCRYPT),
                'matricule' => $data['matricule'],
                'niveau' => $data['niveau'],
                'date_embauche' => $data['date_embauche'],
                'salaire' => $data['salaire'],
                'photo' => $data['photo'],
                'statut' => $data['statut'],
                'role' => $data['role'],
                'id_admin' => $data['id_admin'],
            ]);

            // Valider la transaction
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $this->pdo->rollBack();
            throw new Exception("Erreur lors de l'insertion : " . $e->getMessage());
        }
    }

    // Méthode pour mettre à jour un surveillant existant
    public function update($id, $data) {
        try {
            // Démarrer une transaction
            $this->pdo->beginTransaction();

            // Mettre à jour la table employers
            $stmtEmployer = $this->pdo->prepare("
                UPDATE employers
                SET nom = :nom, prenom = :prenom, adresse = :adresse, date_modification = NOW()
                WHERE num_carte_identite = :num_carte_identite
            ");
            $stmtEmployer->execute([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'adresse' => $data['adresse'],
                'num_carte_identite' => $id,
            ]);

            // Mettre à jour la table surveillants
            $stmtSurveillant = $this->pdo->prepare("
                UPDATE surveillants
                SET sexe = :sexe, telephone = :telephone, email = :email, matricule = :matricule, 
                    niveau = :niveau, date_embauche = :date_embauche, salaire = :salaire, 
                    statut = :statut, photo = :photo, role = :role
                WHERE id_surveillant = (SELECT id_surveillant FROM employers WHERE num_carte_identite = :num_carte_identite)
            ");
            $stmtSurveillant->execute([
                'sexe' => $data['sexe'],
                'telephone' => $data['telephone'],
                'email' => $data['email'],
                'matricule' => $data['matricule'],
                'niveau' => $data['niveau'],
                'date_embauche' => $data['date_embauche'],
                'salaire' => $data['salaire'],
                'statut' => $data['statut'],
                'photo' => $data['photo'],
                'role' => $data['role'],
                'num_carte_identite' => $id,
            ]);

            // Valider la transaction
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // Méthode pour supprimer un surveillant
    public function delete($id) {
        try {
            // Démarrer une transaction
            $this->pdo->beginTransaction();

            // Supprimer le surveillant
            $stmtSurveillant = $this->pdo->prepare("
                DELETE FROM surveillants
                WHERE id_surveillant = (SELECT id_surveillant FROM employers WHERE num_carte_identite = ?)
            ");
            $stmtSurveillant->execute([$id]);

            // Supprimer l'employeur
            $stmtEmployer = $this->pdo->prepare("
                DELETE FROM employers WHERE num_carte_identite = ?
            ");
            $stmtEmployer->execute([$id]);

            // Valider la transaction
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $this->pdo->rollBack();
            throw $e;
        }
    }
}

