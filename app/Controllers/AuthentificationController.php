<?php
// controllers/AuthenticationController.php

session_start();
require 'Models/AuthentificationModel.php';

class AuthentificationController 
{
    private $model;

    public function __construct($conn) 
    {
        $this->model = new AuthentificationModel($conn);
    }

    public function login() 
    {
        $errorMessage = ""; // Initialiser le message d'erreur
    
        // Vérifiez si la méthode de requête est POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            // Vérifiez que les champs existent
            if (isset($_POST['email']) && isset($_POST['password'])) 
            {
                $email = $_POST['email'];
                $password = $_POST['password'];
    
                $admin = $this->model->getAdminByEmail($email);
    
                if ($admin) {
                    // Vérification du mot de passe
                    if (password_verify($password, $admin['password'])) 
                    {
                        // Connexion réussie, stocker les informations dans la session
                        $_SESSION['id_admin'] = $admin['id_admin'];
                        $_SESSION['email'] = $email;
    
                        // Redirection vers la page d'accueil
                        header("Location: Views/Dashboard/index.php");
                        exit();
                    } 
                    else 
                    {
                        $errorMessage = "Email ou mot de passe incorrect.";
                    }
                } 
                else 
                {
                   $errorMessage = "Veuillez remplir tous les champs";
                }
                
               
            } 
        }
    
        // Retourner le message d'erreur, s'il y en a
        return $errorMessage;
    }
    
    public function logout() 
    {
        // Vérifier si une session est active
        if (session_status() == PHP_SESSION_ACTIVE) 
        {
            // Supprimer toutes les variables de session
            $_SESSION = array();

            // Si vous souhaitez aussi détruire la session côté serveur
            session_destroy();
        }

        // Redirection vers la page de connexion
        header("Location: ../Views/Autentification/login.php");
        exit();
    }
}
