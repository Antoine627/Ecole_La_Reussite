<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Élève</title>
</head>
<body>
    <h1>Ajouter un Élève</h1>
    <form method="POST" action="">
        <label for="nom">Nom:</label>
        <input type="text" name="nom" required>
        <br>
        <label for="prenom">Prénom:</label>
        <input type="text" name="prenom" required>
        <br>
        <label for="date_naissance">Date de Naissance:</label>
        <input type="date" name="date_naissance">
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email">
        <br>
        <label for="telephone">Téléphone:</label>
        <input type="text" name="telephone">
        <br>
        <input type="submit" value="Ajouter">
    </form>
</body>
</html>
