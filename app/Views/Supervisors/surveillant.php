
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Simple en PHP</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Gestion des Utilisateurs</h1>

        <!-- Bouton pour ouvrir la modale -->
        <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#addSurveillantModal">
            Ajouter Surveillant
        </button>

        <!-- Modal -->
        <div class="modal fade" id="addSurveillantModal" tabindex="-1" role="dialog" aria-labelledby="addSurveillantModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSurveillantModalLabel">Ajouter un Surveillant</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Formulaire pour ajouter un utilisateur -->
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="num_identite">Numéro d'identité</label>
                                <input type="text" class="form-control" id="num_identite" name="num_identite" required>
                            </div>
                            <div class="form-group">
                                <label for="nom">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                            </div>
                            <div class="form-group">
                                <label for="prenom">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" required>
                            </div>
                            <div class="form-group">
                                <label for="date_naissance">Date de Naissance</label>
                                <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                            </div>
                            <div class="form-group">
                                <label for="adresse">Adresse</label>
                                <input type="text" class="form-control" id="adresse" name="adresse" required>
                            </div>
                            <div class="form-group">
                                <label for="sexe">Sexe</label>
                                <select class="form-control" id="sexe" name="sexe" required>
                                    <option value="">Choisir...</option>
                                    <option value="Homme">Homme</option>
                                    <option value="Femme">Femme</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="telephone">Téléphone</label>
                                <input type="tel" class="form-control" id="telephone" name="telephone" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="mot_de_passe">Mot de Passe</label>
                                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                            </div>
                            <div class="form-group">
                                <label for="matricule">Matricule</label>
                                <input type="text" class="form-control" id="matricule" name="matricule" required>
                            </div>
                            <div class="form-group">
                                <label for="niveau">Niveau</label>
                                <input type="text" class="form-control" id="niveau" name="niveau" required>
                            </div>
                            <div class="form-group">
                                <label for="date_embauche">Date d'Embauche</label>
                                <input type="date" class="form-control" id="date_embauche" name="date_embauche" required>
                            </div>
                            <div class="form-group">
                                <label for="salaire">Salaire</label>
                                <input type="number" class="form-control" id="salaire" name="salaire" required>
                            </div>
                            <div class="form-group">
                                <label for="statut">Statut</label>
                                <input type="text" class="form-control" id="statut" name="statut" required>
                            </div>
                            <div class="form-group">
                                <label for="role">Rôle</label>
                                <input type="text" class="form-control" id="role" name="role" required>
                            </div>
                            <button type="submit" name="create" class="btn btn-primary">Ajouter Utilisateur</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <h3>Liste des Utilisateurs</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Numéro d'identité</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Date de Naissance</th>
                    <th>Adresse</th>
                    <th>Sexe</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Matricule</th>
                    <th>Niveau</th>
                    <th>Date d'Embauche</th>
                    <th>Salaire</th>
                    <th>Statut</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
               
                while ($row = $users->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                        <td>{$row['id_surveillant']}</td>
                        <td>{$row['num_identite']}</td>
                        <td>{$row['nom']}</td>
                        <td>{$row['prenom']}</td>
                        <td>{$row['date_naissance']}</td>
                        <td>{$row['adresse']}</td>
                        <td>{$row['sexe']}</td>
                        <td>{$row['telephone']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['matricule']}</td>
                        <td>{$row['niveau']}</td>
                        <td>{$row['date_embauche']}</td>
                        <td>{$row['salaire']}</td>
                        <td>{$row['statut']}</td>
                        <td>{$row['role']}</td>
                        <td>
                            <form method='post'>
                                <input type='hidden' name='id_surveillant' value='{$row['id_surveillant']}'>
                                <button type='submit' name='delete' class='btn btn-danger'>Supprimer</button>
                            </form>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</html>
