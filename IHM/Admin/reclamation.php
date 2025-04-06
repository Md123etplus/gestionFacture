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
    <title>VoltForce - Administration</title>
  <link rel="stylesheet" href="/IHM/css/bootstrap.css">
  <link rel="stylesheet" href="/IHM/css/style.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link rel="stylesheet" href="/IHM/css/Reclamation.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
</head>
<body class="hero_area">

    <!-- Header Section -->
    <?php include('header.php') ?>

    <?php
        if(isset($message)&& !empty($message)){
            echo "<script>alert('".$message."');</script>";
        }
    ?>
    <main>
        <div class="main">
            <div class="main-container">
                <h2>Liste des Réclamations</h2>
                <?php
                    if(isset($errors)&& !empty($errors)){
                    echo "<span style=\"color: red;\"> $errors </span>";
                    }
                ?>
                <table id="reclamationTable">
                    <thead>
                    <tr>
                    <th>ID Réclamation</th>
                    <th>ID Client</th>
                    <th>Date</th>
                    <th>Motif de la Réclamation</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                    </thead>
                    <tbody>
                    <?php
                if (isset($reclamations)) {
                    foreach ($reclamations as $row) {
                        echo "<tr>
                                <td>{$row['id_reclamation']}</td>
                                <td>{$row['client_id']}</td>
                                <td>{$row['date_soumission']}</td>
                                <td>{$row['type_reclamation']}</td>
                                <td>{$row['statut']}</td>
                                <td>
                                    <a href='/Traitement/Utilisateurs.php?action=traiter_reclamation&id={$row['id_reclamation']}' class='btn btn-warning'>Traiter</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>Aucune réclamation trouvée.</td></tr>";
                }
                ?>
                                
                       
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <!-- Footer Section -->
  <?php include('footer.php') ?>
  <script src="/IHM/js/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/owl.carousel.min.js"></script>
  <script src="/IHM/js/custom.js"></script>

  <script src="/IHM/js/upload.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/IHM/js/reclamation.js"></script> 
    <script>
        // Fonctions pour gérer les actions
        function editClient(clientId) {
            // Redirection vers la page de modification avec l'ID du client
            window.location.href = 'edit_client.php?id=' + clientId;
        }

       
    </script> 
</body>

</html>

