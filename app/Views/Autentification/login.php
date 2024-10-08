<?php
// views/login.php
//connexion à 
$errorMessage = isset($errorMessage) ? $errorMessage : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecole de LA REUSSITE</title>
    <link rel="stylesheet" href="Views/Autentification/css/style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <img src="Views/Autentification/logo.PNG" class="logo" alt="Logo">
        <h1>Connexion</h1>
        <form id="loginForm" method="POST">
            <div class="form-control">
                <input type="email" name="email"  id="email" placeholder="Email"  />
            </div>
            <div class="form-control">
                <input type="password" name="password" id="password" placeholder="Mot de passe" maxlength="6"  />
                <i class="fas fa-eye" id="togglePassword" style="cursor: pointer; position: absolute; right: 10px; top: 35%;"></i>
            </div>
            <button type="submit" class="btn">Se connecter</button>
            
           <!-- Affichage du message d'erreur -->
            <?php if (!empty($errorMessage)): ?>
                <p id="errorMessage" class="error-message"><?php echo $errorMessage; ?></p>
            <?php endif; ?>
      </form>
    </div>
</body>
<script src="Views/Autentification/js/script.js"></script>

</html>