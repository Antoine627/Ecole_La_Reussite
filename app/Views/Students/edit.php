<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Élève</title>
</head>
<body>
    <h1>Modifier un Élève</h1>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($eleve['id']); ?>">
        <label for="nom">Nom:</label>
        <input type="text" name="nom" value="<?php echo htmlspecialchars($eleve['nom']); ?>" required>
        <br>
        <label for="prenom">Prénom:</label>
        <input type="text" name="prenom" value="<?php echo htmlspecialchars($eleve['prenom']); ?>" required>
        <br>
        <label for="date_naissance">Date de Naissance:</label>
        <input type="date" name="date_naissance" value="<?php echo htmlspecialchars($eleve['date_naissance']); ?>">
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($eleve['email']); ?>">
        <br>
        <label for="telephone">Téléphone:</label>
        <input type="text" name="telephone" value="<?php echo htmlspecialchars($eleve['telephone']); ?>">
        <br>
        <input type="submit" value="Modifier">
    </form>
</body>
</html>
