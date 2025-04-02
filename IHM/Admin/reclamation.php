<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VoltForce - Administration</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link rel="stylesheet" href="css/Reclamation.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
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
                <li class="nav-item active">
                  <a class="nav-link" href="reclamation.php ">Réclamations</a>
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
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
                    </thead>
                    <tbody>
                    <?php
                if ($reclamations->num_rows > 0) {
                    while ($row = $reclamations->fetch_assoc()) {
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
    <script src="js/reclamation.js"></script> 
    <script>
        // Fonctions pour gérer les actions
        function editClient(clientId) {
            // Redirection vers la page de modification avec l'ID du client
            window.location.href = 'edit_client.php?id=' + clientId;
        }

       
    </script> 
</body>

</html>

