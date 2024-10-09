<?php

class Eleve {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAll() {
        try {
            $stmt = $this->conn->prepare("SELECT nom, prenom, date_naissance, email, telephone FROM eleves");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM eleves WHERE id_eleve = :id_eleve");
        $stmt->bindParam(':id_eleve', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nom, $prenom, $date_naissance, $email, $telephone) {
        $stmt = $this->conn->prepare("INSERT INTO eleves (nom, prenom, date_naissance, email, telephone) VALUES (:nom, :prenom, :date_naissance, :email, :telephone)");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':date_naissance', $date_naissance);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telephone', $telephone);
        return $stmt->execute();
    }

    public function update($id, $nom, $prenom, $date_naissance, $email, $telephone) {
        $stmt = $this->conn->prepare("UPDATE eleves SET nom = :nom, prenom = :prenom, date_naissance = :date_naissance, email = :email, telephone = :telephone WHERE id_eleve = :id_eleve");
        $stmt->bindParam(':id_eleve', $id);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':date_naissance', $date_naissance);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telephone', $telephone);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM eleves WHERE id_eleve = :id_eleve");
        $stmt->bindParam(':id_eleve', $id);
        return $stmt->execute();
    }
}
