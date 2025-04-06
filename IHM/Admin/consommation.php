<?php
session_start();
  if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: ../../IHM/Admin/login.php');
    exit();
  }
?>

<!DOCTYPE html>
<html>
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

    <style>
    .btn-success { background-color: #28a745; border-color: #28a745; }
        .btn-success:hover { background-color: #218838; border-color: #1e7e34; } 
        </style>
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

            <?php 
            
            if(isset($consommations)){
              foreach ($consommations as $conso): 
                
            ?>
                <tr >
                    <td><?= $conso['id_consommation'] ?></td>
                    <td><?= $conso['client_id'] ?></td>
                    <td><?= $conso['mois'] ?></td>
                    <td><?= $conso['annee'] ?></td>
                    <td><?= $conso['valeur_compteur'] ?> kWh</td>
                    
                    <td>
                        <img src="/IHM/Admin/photos/<?= $conso['photo_compteur'] ?>" width="100">
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

            <?php
                  }
                  else{
                    echo "<tr style=\"color: red;\"> Aucune donnée de consommation </tr>";
                  }

            ?>


            
        </table>
    </form>

    <br>
    <!-- Footer Section -->
  <?php include('footer.php') ?>
  <script src="/IHM/js/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/owl.carousel.min.js"></script>
  <script src="/IHM/js/custom.js"></script>

  <script src="/IHM/js/upload.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

                       
