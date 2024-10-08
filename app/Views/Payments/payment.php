<?php require '../Components/Header_nav.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salaire</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<h1>Salaire du professeur</h1>
<p>Heures de cours total : <?= $heuresCours ?></p>
<p>Salaire calculé : <?= $salaire ?> FCFA</p>

<a href="index.php?action=payer&id_professeur=<?= $id_professeur ?>">Effectuer le paiement</a>
</body>
</html>