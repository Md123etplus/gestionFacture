<?php
  session_start();

  if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: /IHM/Admin/login.php');
    exit();
  }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AMENDIS - Administration</title>
  <link rel="stylesheet" href="/IHM/css/bootstrap.css">
  <link rel="stylesheet" href="/IHM/css/style.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="hero_area">
    <!-- Header Section -->
    <?php include('header.php') ?>
    <!-- Dashboard Section -->
    <section class="dashboard_section py-5">
      <div class="container">
        <?php include('dashboard/header.php')?>

        <!-- Cards de statistiques -->
        <?php include('dashboard/card-stat.php')?>

        <!-- Graphiques -->
        <?php include('dashboard/graph.php')?>

        <!-- Tableau des anomalies de relevé -->
        <?php include('dashboard/anomalies.php')?>

        <!-- Tableau des réclamations récentes -->
        <?php include('dashboard/recent-reclamation.php')?>
      </div>
      <!-- Modal Upload -->
      <?php include('dashboard/modal-upload.php') ?>
      <?php include('dashboard/modal-reclamations.php') ?>
      <?php include('dashboard/modal-anomalies.php')?>
      <?php include('dashboard/modal-voirReclamation.php') ?>
    </section>
  <!-- Footer Section -->
  <?php include('footer.php') ?>
  <script src="/IHM/js/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/owl.carousel.min.js"></script>
  <script src="/IHM/js/custom.js"></script>

  <script src="/IHM/js/upload.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="/IHM/js/custom.js"></script> -->
  <!-- <script src="/IHM/js/upload.js"></script> -->
  <!-- Bootstrap JS + Popper.js -->
  <script src="/IHM/js/loadData.js"></script>

</body>

</html>
