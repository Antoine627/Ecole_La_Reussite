<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Administration</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
/* styles.css */

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    height: 100vh;
    overflow: hidden;
}

/* Style pour le conteneur du logo et du hamburger */
.logo-container {
    display: flex;
    align-items: center;
    padding: 10px;
    position: relative; /* Pour positionner l'icône hamburger en absolu */
}

/* Style pour l'icône hamburger */
.hamburger {
    font-size: 24px;
    color: #fff;
    cursor: pointer;
    position: absolute;
    margin-bottom: 120px;
    top: 10px;
    left: 140px;
    z-index: 10; /* Assure que l'icône hamburger reste au-dessus du menu */
}

/* Style pour l'icône hamburger lorsque le menu est replié */
.sidebar.collapsed .hamburger i {
    color: #fff; /* Couleur de l'icône hamburger lorsque le menu est replié */
    position: absolute;
    right: 50px;
}

/* Style pour le logo lorsque le sidebar est replié */
.sidebar.collapsed .logo img {
    position: relative;
    margin: 3px;
    top: 25px;
    width: 90px; /* Taille du logo lorsque le sidebar est replié */
}

/* Style pour le logo */
.logo img {
    max-width: 150px; /* Ajustez la taille du logo */
    height: auto;
    display: block;
    margin-right: 60px;
}

.sidebar {
    width: 230px;
    background-color: #136ad5;
    color: #ecf0f1;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    position: fixed;
    height: 100%;
    transition: width 0.7s; /* Transition douce pour la rétraction */
}

/*.sidebar .logo {
    display: flex;
    justify-content: center;
    width: 100%;
    margin-bottom: 25px;
}

.sidebar .logo img {
    max-width: 150px; /* Ajustez la taille du logo ici */
    /*height: auto; /* Garder les proportions du logo */
    /*display: block;
    margin-right: 120px;
}*/

.sidebar .nav-text {
    margin-left: 10px;
}

/* Style pour le menu de navigation replié */
.sidebar.collapsed {
    width: 60px; /* Largeur du sidebar replié */
}

.sidebar.collapsed .nav-text {
    display: none; /* Masquer le texte dans le menu replié */
}

.sidebar nav ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
    width: 100%;
}

.sidebar nav ul li {
    margin: 25px 0;
}

.sidebar nav ul li a {
    color: #ecf0f1;
    text-decoration: none;
    display: flex;
    align-items: center;
    padding: 10px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.sidebar nav ul li a i {
    margin-right: 10px;
    font-size: 18px;
}

.sidebar nav ul li a:hover {
    background-color: #4f81c7;
}

.main-content {
    margin-left: 250px;
    padding: 20px;
    width: calc(100% - 250px);
    transition: margin-left 0.3s, width 0.3s;
}

header {
    color: #136ad5;
    padding: 10px;
    border-radius: 5px;
    position: relative;
}


.search-bar {
    display: flex;
    align-items: center;
    margin: 10px;
    padding: 10px;
    background-color: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: width 0.3s;
    width: calc(100% - 350px); /* Largeur par défaut en tenant compte du padding et du sidebar */
}

.search-bar input {
    border: none;
    padding: 10px;
    font-size: 16px;
    width: 100%;
    border-radius: 4px;
    outline: none;
}

.search-bar i {
    margin-right: 10px;
    color: #3498db;
    cursor: pointer;
}

/* Style lorsque le sidebar est replié */
.sidebar.collapsed + .main-content .search-bar {
    width: calc(100% - 180px); /* Largeur réduite lorsque le sidebar est replié */
}

header .header-icons {
    position: absolute;
    right: 20px;
    top: 10px;
    display: flex;
    align-items: center;
    gap: 15px;
}

header .header-icons i {
    font-size: 20px;
    cursor: pointer;
    margin: 15px;
    color: #000000;
    transition: color 0.3s;
}


.content {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

/* styles.css */

/* styles.css */

/* Style pour le conteneur des boutons */
.button-container {
    display: flex;
    justify-content: space-between; /* Répartit les boutons uniformément */
    margin: 5px; /* Marges autour du conteneur des boutons */
    padding: 20px 10px; /* Espacement horizontal à l'intérieur du conteneur */
    background-color: #fff; /* Fond blanc pour le conteneur */
    border-radius: 8px; /* Bordure arrondie du conteneur */
    /*box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Ombre légère autour du conteneur */
    transition: width 0.3s;
    width: calc(100% - 100px); /* Largeur par défaut en tenant compte du padding et du sidebar */
}

/* Style lorsque le sidebar est replié */
.sidebar.collapsed + .main-content .button-container {
    width: calc(100% - 30px); /* Largeur réduite lorsque le sidebar est replié */
}

.big-button {
    background-color: #136ad5; /* Couleur de fond bleue */
    color: #fff; /* Couleur du texte */
    text-align: center; /* Centre le texte horizontalement */
    padding: 20px;
    font-size: 18px;
    font-weight: bold;
    border-radius: 5px;
    flex: 1; /* Les boutons prennent une largeur égale */
    margin: 0 10px; /* Espacement horizontal entre les boutons */
    width: 200px; /* Largeur des boutons */
    height: 80px; /* Hauteur des boutons */
    text-decoration: none; /* Supprime la décoration du texte */
    display: flex; /* Utilise flexbox pour centrer le contenu */
    align-items: center; /* Centre verticalement le texte */
    justify-content: center; /* Centre horizontalement le texte */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    transition: background-color 0.3s, box-shadow 0.3s;
}

.big-button:hover {
    background-color: #4f81c7;
    transform: translateY(-5px); /* Effet de push : bouton monte légèrement */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* Ombre accentuée au survol */
}

.big-button:active {
    transform: translateY(2px); /* Effet de clic : bouton descend légèrement */
    background-color: #1a4a9b;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); /* Réduction de l'ombre au clic */
}

/* Section gestion surveillants*/
#surveillants-container {
    margin-bottom: 30px;
}

.form-control {
    margin-bottom: 15px;
}

.form-control label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.form-control input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}


    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="logo-container">
            <div class="logo">
                <img src="../Authentification/images/logo.png" alt="logo">
            </div>
            <div class="hamburger" id="hamburger">
                <i class="fas fa-bars"></i>
            </div>
        </div>
        <nav>
            <ul>
                <h3><li><a href="index.php"><i class="fas fa-tachometer-alt"></i><span class="nav-text"> Tableau de bord</span></a></li></h3>
                <li><a href="#professors"><i class="fas fa-chalkboard-teacher"></i><span class="nav-text"> Professeurs</span></a></li>
                <li><a href="#payments"><i class="fas fa-credit-card"></i><span class="nav-text"> Paiements</span></a></li>
                <li><a href="#supervisors"><i class="fas fa-user-tie"></i><span class="nav-text"> Surveillants</span></a></li>
                <li><a href="#students"><i class="fas fa-users"></i><span class="nav-text"> Élèves</span></a></li>
                <li><a href="#settings"><i class="fas fa-cogs"></i><span class="nav-text"> Paramètres</span></a></li>
                <li><a href="index.php?action=logout"><i class="fas fa-sign-out-alt"></i><span class="nav-text"> Déconnexion</span></a></li>
            </ul>
        </nav>
    </div>
    <div class="main-content">
        <header>
        
            <div class="search-bar">
                <input type="text" placeholder="Recherche...">
                <i class="fas fa-search"></i>
            </div>

            <h1>Tableau de bord</h1>

            <div class="header-icons">
                <a href="#"><i class="fas fa-bell"></i></a>
                <a href="#"><i class="fas fa-user"></i></a>
            </div>
        </header>
       
    
    </div>

    <script>
        
    document.getElementById('hamburger').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('collapsed'); // Ajoute ou enlève la classe 'collapsed'
    });

    </script>
</body>
</html>
