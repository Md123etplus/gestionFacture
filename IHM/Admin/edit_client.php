<?php
session_start();
  if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: ../../IHM/Admin/login.php');
    exit();
  }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Client</title>
    <link rel="stylesheet" href="/IHM/css/bootstrap.css">
    <link rel="stylesheet" href="/IHM/css/style.css">
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
<body>
    <!-- Header Section -->
    <?php include('header.php') ?>
    <div class="container">
        <h2 class="mt-5">Modifier Client</h2>
        <form action="/Traitement/Utilisateurs.php" method="POST">
            <input type="hidden" name="id_client" value="<?= htmlspecialchars($client['id_utilisateur']) ?>">
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

    <!-- Footer Section -->
  <?php include('footer.php') ?>
  <script src="/IHM/js/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/owl.carousel.min.js"></script>
  <script src="/IHM/js/custom.js"></script>

  <script src="/IHM/js/upload.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
