<?php
// controllers/LogoutController.php

class LogoutController 
{
    public function logout() 
    {
        // Démarrer la session si elle n'est pas déjà démarrée
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Détruire toutes les données de la session
        session_unset();  // Supprimer les variables de session
        session_destroy();  // Détruire la session

        // Rediriger vers la page de connexion ou la page d'accueil
        header('Location: index.php?action=login');
        exit(); // Assurer que le script s'arrête ici
    }
}
