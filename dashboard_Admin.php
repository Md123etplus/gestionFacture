<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VoltForce - Administration</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</head>

<body>
  <div class="hero_area">
    <!-- Header Section -->
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
                <li class="nav-item active">
                  <a class="nav-link" href="admin.html">Dashboard</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="admin-clients.html">Gestion Clients</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="admin-factures.html">Factures</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="reclamation.php">Réclamations</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="admin-releves.html">Relevés</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="index.html">Déconnexion</a>
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
            <h2>Tableau de Bord Administrateur</h2>
            <p class="text-muted">Vue d'ensemble des activités, factures et consommations des clients.</p>
          </div>
        </div>

        <!-- Cards de statistiques -->
        <div class="row mb-5">
          <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary">
              <div class="card-body">
                <h5 class="card-title">Total Clients</h5>
                <p class="card-text display-4">458</p>
                <p class="card-text">+12 ce mois-ci</p>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
              <div class="card-body">
                <h5 class="card-title">Consommation Totale</h5>
                <p class="card-text display-4">62 450</p>
                <p class="card-text">kWh ce mois-ci</p>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="card text-white bg-danger">
              <div class="card-body">
                <h5 class="card-title">Factures Impayées</h5>
                <p class="card-text display-4">87</p>
                <p class="card-text">68 345,20 DH</p>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning">
              <div class="card-body">
                <h5 class="card-title">Réclamations</h5>
                <p class="card-text display-4">23</p>
                <p class="card-text">non traitées</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Graphiques -->
        <div class="row mb-4">
          <div class="col-md-8 mb-4">
            <div class="card">
              <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Consommation Globale Mensuelle (kWh)</h5>
                <div class="btn-group">
                  <button class="btn btn-sm btn-outline-secondary">Par Mois</button>
                  <button class="btn btn-sm btn-outline-secondary">Par Trimestre</button>
                  <button class="btn btn-sm btn-outline-secondary">Par Année</button>
                </div>
              </div>
              <div class="card-body">
                <canvas id="globalConsumptionChart" height="300"></canvas>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="card">
              <div class="card-header bg-light">
                <h5 class="card-title mb-0">Répartition des Réclamations</h5>
              </div>
              <div class="card-body">
                <canvas id="claimsChart" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>

        <!-- Tableau des anomalies de relevé -->
        <div class="row mb-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Anomalies de Relevé à Traiter</h5>
                <a href="admin-releves.html" class="btn btn-sm btn-primary">Voir toutes les anomalies</a>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Client ID</th>
                        <th>Nom</th>
                        <th>Date du relevé</th>
                        <th>Consommation</th>
                        <th>Écart</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>CL-2345</td>
                        <td>Ahmed Bennani</td>
                        <td>05/03/2025</td>
                        <td>350 kWh</td>
                        <td><span class="badge bg-danger">+128%</span></td>
                        <td>
                          <button class="btn btn-sm btn-primary">Vérifier</button>
                          <button class="btn btn-sm btn-outline-success">Valider</button>
                        </td>
                      </tr>
                      <tr>
                        <td>CL-4127</td>
                        <td>Sara Alaoui</td>
                        <td>07/03/2025</td>
                        <td>12 kWh</td>
                        <td><span class="badge bg-warning">-85%</span></td>
                        <td>
                          <button class="btn btn-sm btn-primary">Vérifier</button>
                          <button class="btn btn-sm btn-outline-success">Valider</button>
                        </td>
                      </tr>
                      <tr>
                        <td>CL-3089</td>
                        <td>Karim Tazi</td>
                        <td>08/03/2025</td>
                        <td>420 kWh</td>
                        <td><span class="badge bg-danger">+145%</span></td>
                        <td>
                          <button class="btn btn-sm btn-primary">Vérifier</button>
                          <button class="btn btn-sm btn-outline-success">Valider</button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tableau des réclamations récentes -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Réclamations Récentes</h5>
                <a href="reclamation.php" class="btn btn-sm btn-primary">Gérer toutes les réclamations</a>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Facture</th>
                        <th>Statut</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>R-4582</td>
                        <td>Mohammed El Amrani</td>
                        <td>Fuite externe</td>
                        <td>10/03/2025</td>
                        <td>F-2025-0356</td>
                        <td><span class="badge bg-danger">Non traitée</span></td>
                        <td>
                          <button class="btn btn-sm btn-primary">Voir</button>
                          <button class="btn btn-sm btn-success">Traiter</button>
                        </td>
                      </tr>
                      <tr>
                        <td>R-4580</td>
                        <td>Fatima Zahra</td>
                        <td>Fuite interne</td>
                        <td>09/03/2025</td>
                        <td>F-2025-0341</td>
                        <td><span class="badge bg-warning">En cours</span></td>
                        <td>
                          <button class="btn btn-sm btn-primary">Voir</button>
                          <button class="btn btn-sm btn-success">Finaliser</button>
                        </td>
                      </tr>
                      <tr>
                        <td>R-4575</td>
                        <td>Youssef Nabil</td>
                        <td>Erreur de facturation</td>
                        <td>08/03/2025</td>
                        <td>F-2025-0329</td>
                        <td><span class="badge bg-danger">Non traitée</span></td>
                        <td>
                          <button class="btn btn-sm btn-primary">Voir</button>
                          <button class="btn btn-sm btn-success">Traiter</button>
                        </td>
                      </tr>
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
        <a href="privacy.html">Politique de confidentialité</a> | 
        <a href="terms.html">Mentions légales</a> | 
        <a href="contact.html">Contact</a>
      </p>
    </div>
  </footer>

  <script src="js/jquery-3.4.1.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <script src="js/custom.js"></script>
  
  <script>
    // Configuration des graphiques
    document.addEventListener('DOMContentLoaded', function() {
      // Graphique de consommation globale
      const consumptionCtx = document.getElementById('globalConsumptionChart').getContext('2d');
      const consumptionChart = new Chart(consumptionCtx, {
        type: 'line',
        data: {
          labels: ['Sep 2024', 'Oct 2024', 'Nov 2024', 'Dec 2024', 'Jan 2025', 'Fév 2025', 'Mar 2025'],
          datasets: [{
            label: 'Consommation Totale (kWh)',
            data: [48520, 52180, 58740, 65920, 61450, 59870, 62450],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2,
            tension: 0.3
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: false,
              title: {
                display: true,
                text: 'kWh'
              }
            }
          }
        }
      });
      
      // Graphique de répartition des réclamations
      const claimsCtx = document.getElementById('claimsChart').getContext('2d');
      const claimsChart = new Chart(claimsCtx, {
        type: 'pie',
        data: {
          labels: ['Fuite externe', 'Fuite interne', 'Erreur facture', 'Autre'],
          datasets: [{
            data: [12, 8, 15, 5],
            backgroundColor: [
              'rgba(255, 99, 132, 0.7)',
              'rgba(54, 162, 235, 0.7)',
              'rgba(255, 206, 86, 0.7)',
              'rgba(75, 192, 192, 0.7)'
            ],
            borderColor: [
              'rgba(255, 99, 132, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'bottom'
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
