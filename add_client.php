<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connexion à la base de données
    $conn = new mysqli("localhost", "root", "", "electricity");

    if ($conn->connect_error) {
        die("Échec de la connexion : " . $conn->connect_error);
    }

    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT); // Hash du mot de passe
    $type = 'client'; // On force le type à 'client'
    $numero_compteur = $_POST['numero_compteur'];
    $adresse_installation = $_POST['adresse_installation'];

    // Insérer dans `utilisateur`
    $sql_utilisateur = "INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, type) 
                        VALUES ('$nom', '$prenom', '$email', '$mot_de_passe', '$type')";
    
    if ($conn->query($sql_utilisateur) === TRUE) {
        // Récupérer l'ID du nouvel utilisateur
        $id_utilisateur = $conn->insert_id;

        // Insérer dans `client`
        $sql_client = "INSERT INTO client (id_client, numero_compteur, adresse_installation) 
                       VALUES ('$id_utilisateur', '$numero_compteur', '$adresse_installation')";
        
        if ($conn->query($sql_client) === TRUE) {
            echo "<script>alert('Client ajouté avec succès !'); window.location.href='clients.php';</script>";
        } else {
            echo "Erreur lors de l'ajout du client : " . $conn->error;
        }
    } else {
        echo "Erreur lors de l'ajout de l'utilisateur : " . $conn->error;
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VoltForce - Administration</title>
    
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
   
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #e6f0ff;
            margin: 0;
            min-height: 100vh;
        }

        .main {
            padding: 30px 20px;
            transition: margin-left 0.3s ease;
            max-width: 1200px;
            margin: 0 auto;
        } 

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 77, 153, 0.1);
            width: 100%;
            max-width: 600px;
            margin: 30px auto;
        }

        h2 {
            color: #004E89;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }

        input, select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border 0.3s;
        }

        input:focus, select:focus {
            border-color: #0077B6;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 119, 182, 0.1);
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 15px;
        }

        .btn-submit {
            background-color: #0077B6;
            color: white;
            border: none;
            padding: 12px;
            margin-top: 20px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #005F99;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
                margin: 20px auto;
            }
            
            .main {
                padding: 15px;
            }
        }
    </style>
</head>
<body class="hero_area">

    <header class="header_section">
      <div class="header_top">
        <div class="container-fluid">
          <div class="brand_nav">
            <!-- Logo et titre de la société en haut à gauche -->
            <div class="logo_container">
              <a class="navbar-brand" href="admin.html">
                <img src="images/electricite.png" alt="Logo VoltForce" class="logo">
                <span>VoltForce - Administration</span>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="header_bottom">
        <div class="container-fluid">
          <nav class="navbar navbar-expand-lg custom_nav-container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav">
                <li class="nav-item ">
                  <a class="nav-link" href="dashboard_Admin.php">Dashboard</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="clients.php">Gestion Clients</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="consommation.php">Factures</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="reclamation.php">Réclamations</a>
                </li>
                
                <li class="nav-item">
                  <a class="nav-link" href="index.php">Déconnexion</a>
                </li>
              </ul>
            </div>
          </nav>
        </div>
      </div>
    </header>

    <div class="main">
        <div class="form-container">
            <h2>Ajouter un Nouveau Client</h2>
            <form action="add_client.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Nom :</label>
                <input type="text" name="nom" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Prénom :</label>
                <input type="text" name="prenom" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email :</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mot de passe :</label>
                <input type="password" name="mot_de_passe" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Numéro de compteur :</label>
                <input type="text" name="numero_compteur" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Adresse d'installation :</label>
                <input type="text" name="adresse_installation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
        </div>
    </div>
</body>
</html>