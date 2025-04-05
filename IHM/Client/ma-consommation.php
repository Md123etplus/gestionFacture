<?php

require_once '../../Traitement/Client/ma-consomation.php';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Consommation - VoltForce</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</head>
<body>
    <div class="hero_area">
        <!-- Header Section -->
        <header class="header_section">
            <!-- ... Votre code header existant ... -->
        </header>

        <!-- Main Content -->
        <section class="dashboard_section py-5">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-12">
                        <h2>Ma Consommation</h2>
                        <p class="text-muted">Historique complet de votre consommation électrique</p>
                    </div>
                </div>

                <!-- Cartes de synthèse -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card border-primary h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Consommation ce mois</h5>
                                <h1 class="display-4">
                                    <?= $stats_mensuelles[date('n')] ?? '0' ?> kWh
                                </h1>
                                <p class="text-muted">
                                    <?= date('F Y') ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card border-success h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Moyenne mensuelle</h5>
                                <h1 class="display-4">
                                    <?= count(array_filter($stats_mensuelles)) > 0 ? round(array_sum($stats_mensuelles)/count(array_filter($stats_mensuelles))) : '0' ?> kWh
                                </h1>
                                <p class="text-muted">
                                    Sur <?= count(array_filter($stats_mensuelles)) ?> mois
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card border-info h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Total annuel</h5>
                                <h1 class="display-4">
                                    <?= $stats_annuelles[date('Y')] ?? '0' ?> kWh
                                </h1>
                                <p class="text-muted">
                                    Année <?= date('Y') ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphiques -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">Évolution mensuelle (<?= date('Y') ?>)</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="monthlyChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">Comparaison annuelle</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="annualChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tableau détaillé -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Historique complet</h5>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-secondary active">Tout</button>
                                    <button class="btn btn-sm btn-outline-secondary">12 mois</button>
                                    <button class="btn btn-sm btn-outline-secondary">Par année</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Valeur (kWh)</th>
                                                <th>Photo</th>
                                                <th>Facture associée</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($consommations)): ?>
                                                <?php foreach ($consommations as $conso): 
                                                    $facture_associee = null;
                                                    if (is_array($factures)) {
                                                        foreach ($factures as $facture) {
                                                            if (date('Y-m', strtotime($facture['date_emission'])) == $conso['annee'].'-'.str_pad($conso['mois'], 2, '0', STR_PAD_LEFT)) {
                                                                $facture_associee = $facture;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                ?>
                                                <tr>
                                                    <td><?= date('d/m/Y', strtotime($conso['date_saisie'])) ?></td>
                                                    <td><?= $conso['valeur_compteur'] ?></td>
                                                    <td>
                                                        <?php if (!empty($conso['photo_compteur'])): ?>
                                                        <a href="../../<?= htmlspecialchars($conso['photo_compteur']) ?>" target="_blank">
                                                            <i class="fas fa-camera"></i> Voir
                                                        </a>
                                                        <?php else: ?>
                                                        <span class="text-muted">Aucune</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($facture_associee): ?>
                                                        <a href="voir-facture.php?id=<?= $facture_associee['id_facture'] ?>">
                                                            F-<?= $facture_associee['id_facture'] ?>
                                                        </a>
                                                        <?php else: ?>
                                                        <span class="text-muted">Non facturé</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary">Détails</button>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">Aucune consommation enregistrée</td>
                                                </tr>
                                            <?php endif; ?>
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

    <!-- Footer -->
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Graphique mensuel
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Consommation (kWh)',
                        data: <?= json_encode(array_values($stats_mensuelles)) ?>,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: ctx => ctx.raw + ' kWh'
                            }
                        }
                    }
                }
            });

            // Graphique annuel
            const annualCtx = document.getElementById('annualChart').getContext('2d');
            new Chart(annualCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_keys($stats_annuelles)) ?>,
                    datasets: [{
                        label: 'Consommation annuelle (kWh)',
                        data: <?= json_encode(array_values($stats_annuelles)) ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: ctx => ctx.raw + ' kWh'
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