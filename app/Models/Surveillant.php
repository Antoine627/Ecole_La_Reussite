<?php
class Surveillant {
    private $conn; // Propriété pour la connexion PDO

    public function __construct($conn) {
        $this->conn = $conn; // Affectez l'objet PDO à la propriété
    }

    public function getUsers() {
        $sql = "SELECT * FROM surveillants";
        $stmt = $this->conn->query($sql);
        return $stmt; // Retourner l'objet PDOStatement
    }

    public function createUser($num_identite, $nom, $prenom, $date_naissance, $adresse, $sexe, $telephone, $email, $matricule, $niveau, $date_embauche, $salaire, $statut, $role) {
    try {
        // Vérification si le numéro de téléphone existe déjà
        $query = "SELECT COUNT(*) FROM surveillants WHERE telephone = :telephone";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            throw new Exception("Le numéro de téléphone existe déjà : " . $telephone);
        }

        // Si le numéro de téléphone est unique, procéder à l'insertion
        $query = "INSERT INTO surveillants (num_identite, nom, prenom, date_naissance, adresse, sexe, telephone, email, matricule, niveau, date_embauche, salaire, statut, role)
                  VALUES (:num_identite, :nom, :prenom, :date_naissance, :adresse, :sexe, :telephone, :email, :matricule, :niveau, :date_embauche, :salaire, :statut, :role)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':num_identite', $num_identite);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':date_naissance', $date_naissance);
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':sexe', $sexe);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':matricule', $matricule);
        $stmt->bindParam(':niveau', $niveau);
        $stmt->bindParam(':date_embauche', $date_embauche);
        $stmt->bindParam(':salaire', $salaire);
        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':role', $role);

        $stmt->execute();
        echo "Surveillant ajouté avec succès.";
    } catch (PDOException $e) {
        echo "Erreur lors de l'insertion : " . $e->getMessage();
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}


    public function updateUser($id_surveillant, $num_identite, $nom, $prenom, $date_naissance, $adresse, $sexe, $telephone, $email, $mot_de_passe, $matricule, $niveau, $date_embauche, $salaire, $statut, $role) {
        $sql = "UPDATE surveillants
                SET num_identite = ?,
                    nom = ?,
                    prenom = ?,
                    date_naissance = ?,
                    adresse = ?,
                    sexe = ?,
                    telephone = ?,
                    email = ?,
                    mot_de_passe = ?,
                    matricule = ?,
                    niveau = ?,
                    date_embauche = ?,
                    salaire = ?,
                    statut = ?,
                    role = ?
                WHERE id_surveillant = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$num_identite, $nom, $prenom, $date_naissance, $adresse, $sexe, $telephone, $email, $mot_de_passe, $matricule, $niveau, $date_embauche, $salaire, $statut, $role, $id_surveillant]);
    }

    public function deleteUser($id) {
        $sql = "DELETE FROM surveillants WHERE id_surveillant = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
