<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "electricity");

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Vérifier si un ID client est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID client non valide.");
}

$id_client = intval($_GET['id']); // Sécuriser l'ID

// Récupérer les données du client
$sql = "SELECT u.nom, u.prenom, u.email, c.numero_compteur, c.adresse_installation 
        FROM utilisateur u
        JOIN client c ON u.id_utilisateur = c.id_client
        WHERE u.id_utilisateur = $id_client";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Client non trouvé.");
}

$client = $result->fetch_assoc();

// Traitement du formulaire de mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $numero_compteur = $_POST['numero_compteur'];
    $adresse_installation = $_POST['adresse_installation'];

    // Mise à jour des données dans `utilisateur`
    $sql_update_utilisateur = "UPDATE utilisateur SET 
                                nom = '$nom', 
                                prenom = '$prenom', 
                                email = '$email'
                                WHERE id_utilisateur = $id_client";

    // Mise à jour des données dans `client`
    $sql_update_client = "UPDATE client SET 
                            numero_compteur = '$numero_compteur', 
                            adresse_installation = '$adresse_installation'
                            WHERE id_client = $id_client";

    if ($conn->query($sql_update_utilisateur) === TRUE && $conn->query($sql_update_client) === TRUE) {
        echo "<script>alert('Client modifié avec succès !'); window.location.href='clients.php';</script>";
    } else {
        echo "Erreur lors de la mise à jour : " . $conn->error;
    }
}

$conn->close();
?>

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
        <form action="" method="POST">
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
            <button type="submit" class="btn btn-primary">Modifier</button>
            <a href="clients.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>
</html>
