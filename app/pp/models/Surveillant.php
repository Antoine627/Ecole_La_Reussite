<?php
class Surveillant {
    private $db; // Propriété pour la connexion PDO

    public function __construct($db) {
        $this->db = $db; // Affectez l'objet PDO à la propriété
    }

    // Récupérer tous les surveillants
    public function getUserById($id) {
        $query = "SELECT * FROM surveillants WHERE id_surveillant = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Récupérer les données sous forme de tableau associatif
    }

    // Fonction pour générer un matricule unique
  private function genererMatricule() {
      // Obtenez la date d'aujourd'hui au format MMYYYY
      $dateInscription = date("mY"); // Format MMYYYY

      // Générez un matricule unique avec le préfixe 'SUP' et la date d'inscription
      $matricule = "SUP-" . $dateInscription . "-" . strtoupper(bin2hex(random_bytes(2))); // Utilisation de 2 octets pour un identifiant

      // Vérifier si le matricule existe déjà dans la base de données
      while ($this->verifierMatriculeExist($matricule)) {
          // Regénérer si le matricule existe déjà
          $matricule = "SUP-" . $dateInscription . "-" . strtoupper(bin2hex(random_bytes(2)));
      }

      return $matricule;
  }

  // Fonction pour vérifier si le matricule existe déjà
  private function verifierMatriculeExist($matricule) {
      $stmt = $this->conn->prepare("SELECT COUNT(*) FROM surveillants WHERE matricule = :matricule");
      $stmt->bindParam(':matricule', $matricule);
      $stmt->execute();

      return $stmt->fetchColumn() > 0; // Retourne true si le matricule existe
  }



    // Création d'un surveillant sans la vérification du téléphone
    public function createUser($nom, $prenom, $date_naissance, $adresse, $sexe, $telephone, $email, $niveau, $date_embauche, $salaire, $statut, $role) {
        try {
            $matricule = $this->genererMatricule(); // Générer un matricule unique

            // Insertion du surveillant
            $query = "INSERT INTO surveillants (nom, prenom, date_naissance, adresse, sexe, telephone, email, matricule, niveau, date_embauche, salaire, statut, role)
                      VALUES (:nom, :prenom, :date_naissance, :adresse, :sexe, :telephone, :email, :matricule, :niveau, :date_embauche, :salaire, :statut, :role)";

            $stmt = $this->conn->prepare($query);
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

            if ($stmt->execute()) {
                echo "Surveillant ajouté avec succès. Matricule : " . $matricule;
            }
        } catch (PDOException $e) {
            // Gestion spécifique des doublons de clé unique
            if ($e->getCode() == 23000) {
                echo "Erreur : Le numéro de téléphone ou l'email existe déjà.";
            } else {
                echo "Erreur lors de l'insertion : " . $e->getMessage();
            }
        }
    }

    // Mise à jour d'un surveillant
    public function updateUser($id_surveillant, $nom, $prenom, $date_naissance, $adresse, $sexe, $telephone, $email, $mot_de_passe, $matricule, $niveau, $date_embauche, $salaire, $statut, $role) {
        try {
            $sql = "UPDATE surveillants
                    SET  nom = :nom,
                         prenom = :prenom,
                         date_naissance = :date_naissance,
                         adresse = :adresse,
                         sexe = :sexe,
                         telephone = :telephone,
                         email = :email,
                         mot_de_passe = :mot_de_passe,
                         matricule = :matricule,
                         niveau = :niveau,
                         date_embauche = :date_embauche,
                         salaire = :salaire,
                         statut = :statut,
                         role = :role
                    WHERE id_surveillant = :id_surveillant";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':date_naissance', $date_naissance);
            $stmt->bindParam(':adresse', $adresse);
            $stmt->bindParam(':sexe', $sexe);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mot_de_passe', $mot_de_passe);
            $stmt->bindParam(':matricule', $matricule);
            $stmt->bindParam(':niveau', $niveau);
            $stmt->bindParam(':date_embauche', $date_embauche);
            $stmt->bindParam(':salaire', $salaire);
            $stmt->bindParam(':statut', $statut);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':id_surveillant', $id_surveillant);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour : " . $e->getMessage();
        }
    }

    // Suppression d'un surveillant
    public function deleteUser($id) {
        try {
            $sql = "DELETE FROM surveillants WHERE id_surveillant = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression : " . $e->getMessage();
        }
    }
}
?>
