<?php require '../Components/Header_nav.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Surveillants</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container{
            position: absolute;
            top: 270px;
            left: 350px;
        }
        .btn{
            position: relative;
            left: 900px;
        }
    
        /* CSS pour augmenter la largeur du modal */
        .modal-dialog {
            max-width: 800px; /* Ajustez cette valeur selon vos besoins */
            width: 100%; /* S'assure que le modal occupe toute la largeur disponible */
        }

        .form-row {
            display: flex; /* Utiliser Flexbox pour aligner les éléments */
            justify-content: space-between; /* Distribue l'espace entre les éléments */
        }

        fieldset {
            border: 1px solid #ccc; /* Ajouter une bordure pour mieux visualiser les fieldsets */
            padding: 10px;
            border-radius: 5px; /* Arrondir les coins du fieldset */
        }

    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Liste des Surveillants</h1>
        <a href="#" class="btn btn-primary mb-3" data-toggle="modal" data-target="#supervisorModal">Ajouter un Surveillant</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Sexe</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Matricule</th>
                    <th>Niveau</th>
                    <th>Date d'embauche</th>
                    <th>Salaire</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (isset($surveillants) && !empty($surveillants)) {
                        foreach ($surveillants as $surveillant) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($surveillant['id_surveillant']) . "</td>";
                            echo "<td>" . htmlspecialchars($surveillant['nom']) . "</td>";
                            echo "<td>" . htmlspecialchars($surveillant['prenom']) . "</td>";
                            echo "<td>" . htmlspecialchars($surveillant['sexe']) . "</td>";
                            echo "<td>" . htmlspecialchars($surveillant['telephone']) . "</td>";
                            echo "<td>" . htmlspecialchars($surveillant['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($surveillant['matricule']) . "</td>";
                            echo "<td>" . htmlspecialchars($surveillant['niveau']) . "</td>";
                            echo "<td>" . htmlspecialchars($surveillant['date_embauche']) . "</td>";
                            echo "<td>" . htmlspecialchars($surveillant['salaire']) . "</td>";
                            echo "<td>" . htmlspecialchars($surveillant['statut']) . "</td>";
                            echo "<td>";
                            echo "<a href='index.php?action=edit&id=" . htmlspecialchars($surveillant['id_surveillant']) . "' class='btn btn-warning btn-sm'>Modifier</a> ";
                            echo "<a href='index.php?action=delete&id=" . htmlspecialchars($surveillant['id_surveillant']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Êtes-vous sûr ?\")'>Supprimer</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                ?>
            </tbody>
        </table>
    </div>




<!-- Modal -->
<div class="modal fade" id="supervisorModal" tabindex="-1" role="dialog" aria-labelledby="supervisorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="supervisorModalLabel"><?= isset($surveillant) ? 'Modifier' : 'Ajouter' ?> un Surveillant</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Formulaire de surveillant -->
        <form method="POST">
          <input type="hidden" name="id" value="<?= $surveillant['id_surveillant'] ?? '' ?>">

          <div class="form-row">
            <!-- Coordonnées -->
            <fieldset class="mb-3 col-md-6">
              <legend>Coordonnées</legend>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="telephone">Téléphone:</label>
                  <input type="tel" id="telephone" name="telephone" class="form-control" value="<?= $surveillant['telephone'] ?? '' ?>" required>
                </div>
                <div class="form-group col-md-6">
                  <label for="email">Email:</label>
                  <input type="email" id="email" name="email" class="form-control" value="<?= $surveillant['email'] ?? '' ?>" required>
                </div>
              </div>
            </fieldset>

            <!-- Informations Professionnelles -->
            <fieldset class="mb-3 col-md-6">
              <legend>Informations Professionnelles</legend>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="matricule">Matricule:</label>
                  <input type="text" id="matricule" name="matricule" class="form-control" value="<?= $surveillant['matricule'] ?? '' ?>" required>
                </div>
                <div class="form-group col-md-6">
                  <label for="niveau">Niveau:</label>
                  <select id="niveau" name="niveau" class="form-control" required>
                    <option value="" disabled <?= !isset($surveillant) ? 'selected' : '' ?>>Choisissez...</option>
                    <option value="Primaire" <?= (isset($surveillant) && $surveillant['niveau'] === 'Primaire') ? 'selected' : '' ?>>Primaire</option>
                    <option value="Secondaire" <?= (isset($surveillant) && $surveillant['niveau'] === 'Secondaire') ? 'selected' : '' ?>>Secondaire</option>
                  </select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="date_embauche">Date d'embauche:</label>
                  <input type="date" id="date_embauche" name="date_embauche" class="form-control" value="<?= $surveillant['date_embauche'] ?? '' ?>" required>
                </div>
                <div class="form-group col-md-6">
                  <label for="salaire">Salaire:</label>
                  <input type="number" id="salaire" name="salaire" class="form-control" value="<?= $surveillant['salaire'] ?? '' ?>" required>
                </div>
              </div>
              <div class="form-group">
                <label for="statut">Statut:</label>
                <select id="statut" name="statut" class="form-control" required>
                  <option value="Actif" <?= (isset($surveillant) && $surveillant['statut'] === 'Actif') ? 'selected' : '' ?>>Actif</option>
                  <option value="Inactif" <?= (isset($surveillant) && $surveillant['statut'] === 'Inactif') ? 'selected' : '' ?>>Inactif</option>
                </select>
              </div>
            </fieldset>
          </div>

          <!-- Informations Personnelles en bas -->
          <fieldset class="mb-3">
            <legend>Informations Personnelles</legend>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" class="form-control" value="<?= $surveillant['nom'] ?? '' ?>" required>
              </div>
              <div class="form-group col-md-6">
                <label for="prenom">Prénom:</label>
                <input type="text" id="prenom" name="prenom" class="form-control" value="<?= $surveillant['prenom'] ?? '' ?>" required>
              </div>
            </div>
            <div class="form-group">
              <label for="sexe">Sexe:</label>
              <select id="sexe" name="sexe" class="form-control" required>
                <option value="" disabled <?= !isset($surveillant) ? 'selected' : '' ?>>Choisissez...</option>
                <option value="Masculin" <?= (isset($surveillant) && $surveillant['sexe'] === 'Masculin') ? 'selected' : '' ?>>Masculin</option>
                <option value="Féminin" <?= (isset($surveillant) && $surveillant['sexe'] === 'Féminin') ? 'selected' : '' ?>>Féminin</option>
              </select>
            </div>
          </fieldset>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>







</body>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</html>
