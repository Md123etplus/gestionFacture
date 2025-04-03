<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Modifier Client</h2>
        <form action="/Traitement/Utilisateurs.php" method="POST">
            <input type="hidden" name="id_client" value="<?= htmlspecialchars($client['id_client']) ?>">
            <div class="mb-3">
                <label class="form-label">Nom :</label>
                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($client['nom']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Prénom :</label>
                <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($client['prenom']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email :</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($client['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Numéro de compteur :</label>
                <input type="text" name="numero_compteur" class="form-control" value="<?= htmlspecialchars($client['numero_compteur']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Adresse d'installation :</label>
                <input type="text" name="adresse_installation" class="form-control" value="<?= htmlspecialchars($client['adresse_installation']) ?>" required>
            </div>
            <button type="submit" name="submit_editClient" class="btn btn-primary">Modifier</button>
            <a href="clients.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>
</html>
