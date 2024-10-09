<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Élève</title>
    <!-- Lien vers Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Liste des Élèves</h1>
        <!-- Bouton pour ouvrir la modale -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addStudentModal">
            Ajouter un Élève
        </button>

        <!-- Modale pour ajouter un élève -->
        <div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog" aria-labelledby="addStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStudentModalLabel">Ajouter un Élève</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="nom">Nom:</label>
                                <input type="text" class="form-control" name="nom" required>
                            </div>
                            <div class="form-group">
                                <label for="prenom">Prénom:</label>
                                <input type="text" class="form-control" name="prenom" required>
                            </div>
                            <div class="form-group">
                                <label for="date_naissance">Date de Naissance:</label>
                                <input type="date" class="form-control" name="date_naissance">
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                            <div class="form-group">
                                <label for="telephone">Téléphone:</label>
                                <input type="text" class="form-control" name="telephone">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                <input type="submit" class="btn btn-primary" value="Ajouter">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des élèves -->
        <table class="table table-striped mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Date de Naissance</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($eleves) && !empty($eleves)): ?>
                    <?php foreach ($eleves as $eleve): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($eleve['id_eleve']); ?></td>
                            <td><?php echo htmlspecialchars($eleve['nom']); ?></td>
                            <td><?php echo htmlspecialchars($eleve['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($eleve['date_naissance']); ?></td>
                            <td><?php echo htmlspecialchars($eleve['email']); ?></td>
                            <td><?php echo htmlspecialchars($eleve['telephone']); ?></td>
                            <td>
                                <a href="/index.php?action=edit&id=<?php echo $eleve['id_eleve']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                                <a href="/index.php?action=delete&id=<?php echo $eleve['id_eleve']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet élève ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Aucun élève trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Lien vers jQuery, Popper.js et Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
