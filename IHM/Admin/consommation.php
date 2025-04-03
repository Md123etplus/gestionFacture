<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VoltForce - Administration</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link rel="stylesheet" href="Reclamation.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

    <style>
    .btn-success { background-color: #28a745; border-color: #28a745; }
        .btn-success:hover { background-color: #218838; border-color: #1e7e34; } 
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
                <li class="nav-item active">
                  <a class="nav-link " href="consommation.php">Factures</a>
                </li>
                <li class="nav-item ">
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
                <h2>Validation des Consommations</h2>
                <?php
                    if(isset($errors)&& !empty($errors)){
                    echo "<span style=\"color: red;\"> $errors </span>";
                    }
                ?>
                <table id="reclamationTable">
                    <thead>
                <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Mois</th>
                <th>Année</th>
                <th>Consommation</th>
                <th>Photo</th>
                <th>Correction</th>
                <th>Actions</th>
            </tr>

            <?php foreach ($consommations as $conso): 
                
            ?>
                <tr >
                    <td><?= $conso['id_consommation'] ?></td>
                    <td><?= $conso['client_id'] ?></td>
                    <td><?= $conso['mois'] ?></td>
                    <td><?= $conso['annee'] ?></td>
                    <td><?= $conso['valeur_compteur'] ?> kWh</td>
                    
                    <td>
                        <img src="photos/<?= $conso['photo_compteur'] ?>" width="100">
                    </td>
                    
                   
                    <td>
                                <form method="POST" class="d-flex gap-2">
                                    <input type="hidden" name="id_consommation" value="<?= $conso['id_consommation'] ?>">
                                    <input type="number" name="valeur_compteur" value="<?= $conso['valeur_compteur'] ?>" 
                                           step="0.01" class="form-control" style="width: 100px;">
                                    <button type="submit" name="update" class="btn btn-success">Corriger</button>
                                </form>
                            </td>
                            <td>
                              
                                <a href='/Traitement/Utilisateurs.php?action=generer_facture&id=<?= $conso['client_id'] ?>' 
                                   class='btn btn-success'>Générer Facture</a>
                            </td>
                </tr>
            <?php endforeach; ?>



            
        </table>
    </form>

    <br>
    
</body>
</html>

                       
