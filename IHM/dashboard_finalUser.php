<?php
session_start();

require_once '../BD/Connexion.php';
require_once '../BD/utilisateur.php';
require_once '../BD/client.php';
require_once '../BD/facture.php';
require_once '../BD/consommation.php';
require_once '../BD/reclamation.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

// Récupération des données via les fonctions CRUD
$user = getUserById($_SESSION['user_id']);
$client = getClientById($_SESSION['user_id']);
$factures = getFacturesByClient($_SESSION['user_id']);
$consommations = getConsommationByClient($_SESSION['user_id']);
$reclamations = getReclamationsByClient($_SESSION['user_id']);

// Calculs des totaux pour les factures
$total_impaye = 0;
$factures_impayees = 0;
$factures_recentes = array_slice($factures, 0, 3);

foreach ($factures as $facture) {
  if ($facture['statut'] == 'impayée') {
      $total_impaye += $facture['montant_ttc'];
      $factures_impayees++;
  }
}

// Calcul des statistiques de consommation
$current_month_consumption = 0;
$average_consumption = 0;
$monthly_consumptions = array_fill(0, 6, 0); // Pour les 6 derniers mois
$monthly_labels = [];
$current_month = date('Y-m');

if (!empty($consommations)) {
  // Calcul de la consommation du mois courant et moyenne
  $total_consumption = 0;
  $month_counts = [];
  
  foreach ($consommations as $conso) {
      $conso_month = date('Y-m', strtotime($conso['date_saisie'] ?? $conso['date_consommation']));
      
      if ($conso_month == $current_month) {
          $current_month_consumption += $conso['valeur_compteur'];
      }
      
      $total_consumption += $conso['valeur_compteur'];
      $month_counts[$conso_month] = ($month_counts[$conso_month] ?? 0) + $conso['valeur_compteur'];
  }
  
  $average_consumption = count($month_counts) > 0 ? $total_consumption / count($month_counts) : 0;
  
  // Préparation des données pour le graphique (6 derniers mois)
  $now = new DateTime();
  for ($i = 5; $i >= 0; $i--) {
      $month = clone $now;
      $month->modify("-$i months");
      $month_key = $month->format('Y-m');
      $monthly_labels[] = $month->format('M Y');
      
      if (isset($month_counts[$month_key])) {
          $monthly_consumptions[5-$i] = $month_counts[$month_key];
      }
  }
}

// Statistiques des réclamations
$reclamations_en_attente = 0;
$reclamations_en_cours = 0;
$reclamations_resolues = 0;

foreach ($reclamations as $reclamation) {
  switch ($reclamation['statut']) {
      case 'en attente':
          $reclamations_en_attente++;
          break;
      case 'en cours':
          $reclamations_en_cours++;
          break;
      case 'résolue':
          $reclamations_resolues++;
          break;
  }
}

// Récupération des photos du compteur (supposons que c'est stocké dans la table consommation)
$photos_compteur = array_filter($consommations, function($conso) {
    return !empty($conso['photo_compteur']);
});
usort($photos_compteur, function($a, $b) {
    return strtotime($b['date_saisie'] ?? $b['date_consommation']) <=> strtotime($a['date_saisie'] ?? $a['date_consommation']);
});
$recent_photos = array_slice($photos_compteur, 0, 3);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Espace Client - VoltForce</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</head>
<body>
  <div class="hero_area">
    <!-- Header Section -->
    <header class="header_section">
      <div class="header_top">
        <div class="container-fluid">
          <div class="brand_nav">
            <div class="logo_container">
              <a class="navbar-brand" href="dashboard_finalUser.php">
                <img src="images/electricite.png" alt="Logo VoltForce" class="logo">
                <span>VoltForce</span>
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
                <li class="nav-item active">
                  <a class="nav-link" href="dashboard_finalUser.php">Mon Tableau de Bord</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="mes-factures.php">Mes Factures</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="ma-consommation.php">Ma Consommation</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="mes-reclamations.php">Mes Réclamations</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="mon-profil.php">Mon Profil</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="logout.php">Déconnexion</a>
                </li>
              </ul>
            </div>
          </nav>
        </div>
      </div>
    </header>

    <!-- Dashboard Section -->
    <section class="dashboard_section py-5">
      <div class="container">
        <div class="row mb-4">
          <div class="col-12">
            <h2>Bienvenue, <span id="clientName"><?= htmlspecialchars($client['nom'] ?? $user['nom'] ?? 'Client') ?></span></h2>
            <p class="text-muted">Voici un aperçu de votre consommation et de vos factures.</p>
          </div>
        </div>

        <!-- Résumé des informations -->
        <div class="row mb-4">
          <!-- Carte Consommation -->
          <div class="col-md-4 mb-3">
            <div class="card border-primary h-100">
              <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Ma Consommation</h5>
              </div>
              <div class="card-body">
                <div class="text-center mb-3">
                  <h1 id="currentConsumption"><?= $current_month_consumption ?></h1>
                  <p>kWh ce mois-ci</p>
                </div>
                <div class="progress mb-3" style="height: 20px;">
                  <?php 
                  $percent = $average_consumption > 0 ? min(100, ($current_month_consumption / $average_consumption) * 100) : 0;
                  ?>
                  <div class="progress-bar bg-success" role="progressbar" style="width: <?= $percent ?>%;" 
                       aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100">
                    <?= round($percent) ?>%
                  </div>
                </div>
                <p class="text-muted">Par rapport à votre moyenne habituelle (<?= round($average_consumption) ?> kWh)</p>
                <a href="saisir-consommation.php" class="btn btn-primary btn-block mt-3">Saisir ma consommation</a>
              </div>
            </div>
          </div>
          
          <!-- Carte Factures -->
          <div class="col-md-4 mb-3">
            <div class="card border-success h-100">
              <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Mes Factures</h5>
              </div>
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <span>Factures non payées:</span>
                  <span class="badge bg-danger"><?= $factures_impayees ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <span>Montant total dû:</span>
                  <span class="font-weight-bold"><?= number_format($total_impaye, 2) ?> DH</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <span>Prochaine échéance:</span>
                  <span>
                    <?= !empty($factures) ? date('d/m/Y', strtotime($factures[0]['date_echeance'])) : 'Aucune' ?>
                  </span>
                </div>
                <a href="mes-factures.php" class="btn btn-success btn-block mt-3">Voir mes factures</a>
              </div>
            </div>
          </div>
          
          <!-- Carte Réclamations -->
          <div class="col-md-4 mb-3">
            <div class="card border-warning h-100">
              <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">Mes Réclamations</h5>
              </div>
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <span>En attente:</span>
                  <span class="badge bg-secondary"><?= $reclamations_en_attente ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <span>En cours de traitement:</span>
                  <span class="badge bg-info"><?= $reclamations_en_cours ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <span>Résolues:</span>
                  <span class="badge bg-success"><?= $reclamations_resolues ?></span>
                </div>
                <a href="nouvelle-reclamation.php" class="btn btn-warning btn-block mt-3">Faire une réclamation</a>
              </div>
            </div>
          </div>
        </div>

        <!-- Graphique de consommation -->
        <div class="row mb-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Ma Consommation Mensuelle</h5>
                <div class="btn-group">
                  <button class="btn btn-sm btn-outline-secondary active">6 mois</button>
                  <button class="btn btn-sm btn-outline-secondary">1 an</button>
                </div>
              </div>
              <div class="card-body">
                <canvas id="consumptionChart" height="250"></canvas>
              </div>
            </div>
          </div>
        </div>

        <!-- Photos du compteur -->
        <div class="row mb-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-light">
                <h5 class="card-title mb-0">Photos Récentes du Compteur</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <?php foreach ($recent_photos as $photo): ?>
                  <div class="col-md-3 col-6 mb-3">
                    <div class="card">
                      <img src="<?= htmlspecialchars($photo['photo_compteur']) ?>" alt="Compteur - <?= date('d M Y', strtotime($photo['date_saisie'] ?? $photo['date_consommation'])) ?>" class="card-img-top">
                      <div class="card-footer text-center">
                        <small class="text-muted">Compteur - <?= date('d M Y', strtotime($photo['date_saisie'] ?? $photo['date_consommation'])) ?></small>
                      </div>
                    </div>
                  </div>
                  <?php endforeach; ?>
                  
                  <?php if (count($recent_photos) < 3): ?>
                  <div class="col-md-3 col-6 mb-3">
                    <div class="card text-center d-flex align-items-center justify-content-center h-100" style="background-color: #f8f9fa;">
                      <a href="saisir-consommation.php" class="text-decoration-none">
                        <div class="py-4">
                          <div class="mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-camera" viewBox="0 0 16 16">
                              <path d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1v6zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2z"/>
                              <path d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5zm0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zM3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                            </svg>
                          </div>
                          <div>Ajouter photo</div>
                        </div>
                      </a>
                    </div>
                  </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Factures récentes -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Mes Dernières Factures</h5>
                <a href="mes-factures.php" class="btn btn-sm btn-primary">Voir toutes mes factures</a>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Référence</th>
                        <th>Période</th>
                        <th>Consommation</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($factures_recentes as $facture): ?>
                      <tr>
                        <td>F-<?= $facture['id_facture'] ?></td>
                        <td><?= date('F Y', strtotime($facture['date_emission'])) ?></td>
                        <td><?= getConsommationForFacture($facture['id_facture'])['valeur_compteur'] ?? 'N/A' ?> kWh</td>
                        <td><?= number_format($facture['montant_ttc'], 2) ?> DH</td>
                        <td>
                          <span class="badge <?= $facture['statut'] == 'payée' ? 'bg-success' : 'bg-danger' ?>">
                            <?= ucfirst($facture['statut']) ?>
                          </span>
                        </td>
                        <td>
                          <a href="voir-facture.php?id=<?= $facture['id_facture'] ?>" class="btn btn-sm btn-outline-primary">Voir</a>
                          <?php if ($facture['statut'] == 'impayée'): ?>
                          <a href="payer-facture.php?id=<?= $facture['id_facture'] ?>" class="btn btn-sm btn-success">Payer</a>
                          <?php else: ?>
                          <a href="telecharger-facture.php?id=<?= $facture['id_facture'] ?>" class="btn btn-sm btn-outline-secondary">Télécharger</a>
                          <?php endif; ?>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Footer Section -->
  <footer class="footer_section">
    <div class="container">
      <p>&copy; <span id="displayDateYear">2025</span> VoltForce. Tous droits réservés.</p>
      <p>
        <a href="privacy.php">Politique de confidentialité</a> | 
        <a href="terms.php">Mentions légales</a> | 
        <a href="contact.php">Contact</a>
      </p>
    </div>
  </footer>

  <script src="js/jquery-3.4.1.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <script src="js/custom.js"></script>
  
  <script>
    // Configuration des graphiques
    document.addEventListener('DOMContentLoaded', function() {
      // Graphique de consommation mensuelle
      const consumptionCtx = document.getElementById('consumptionChart').getContext('2d');
      const consumptionChart = new Chart(consumptionCtx, {
        type: 'bar',
        data: {
          labels: <?= json_encode($monthly_labels) ?>,
          datasets: [{
            label: 'Consommation (kWh)',
            data: <?= json_encode($monthly_consumptions) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'kWh'
              }
            }
          },
          plugins: {
            tooltip: {
              callbacks: {
                label: function(context) {
                  return context.raw + ' kWh';
                }
              }
            }
          }
        }
      });
      
      // Afficher l'année actuelle dans le footer
      document.getElementById('displayDateYear').textContent = new Date().getFullYear();
    });
  </script>
</body>
</html>