<?php

require_once '../../Traitement/Client/mes-reclamations.php';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réclamations - VoltForce</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                                <li class="nav-item">
                                    <a class="nav-link" href="dashboard_finalUser.php">Mon Tableau de Bord</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="saisir-consommation.php">Saisir Consommation</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="mes-factures.php">Mes Factures</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="ma-consommation.php">Ma Consommation</a>
                                </li>
                                <li class="nav-item active">
                                    <a class="nav-link" href="mes-reclamations.php">Mes Réclamations</a>
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

        <!-- Main Content -->
        <section class="dashboard_section py-5">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-12">
                        <h2>Mes Réclamations</h2>
                        <p class="text-muted">Gérez vos réclamations en cours et consultez l'historique</p>
                    </div>
                </div>

                <!-- Messages d'alerte -->
                <?php if (!empty($errors)): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php elseif ($success): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-success">
                            Opération effectuée avec succès !
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Nouvelle réclamation -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">Nouvelle réclamation</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="type" class="form-label">Type de réclamation *</label>
                                            <select class="form-select" id="type" name="type" required>
                                                <option value="">Sélectionnez...</option>
                                                <option value="Facturation">Problème de facturation</option>
                                                <option value="Compteur">Dysfonctionnement du compteur</option>
                                                <option value="Service">Service client</option>
                                                <option value="Technique">Problème technique</option>
                                                <option value="Autre">Autre</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="description" class="form-label">Description détaillée *</label>
                                            <textarea class="form-control" id="description" name="description" rows="4" 
                                                      placeholder="Décrivez votre problème en détail..." required></textarea>
                                            <div class="form-text">Minimum 20 caractères</div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" name="submit_reclamation" class="btn btn-primary">
                                                <i class="fas fa-paper-plane me-2"></i>Envoyer la réclamation
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste des réclamations -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Historique des réclamations</h5>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-secondary active">Toutes</button>
                                    <button class="btn btn-sm btn-outline-secondary">En cours</button>
                                    <button class="btn btn-sm btn-outline-secondary">Résolues</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Réf.</th>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Statut</th>
                                                <th>Description</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($reclamations)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    Aucune réclamation enregistrée
                                                </td>
                                            </tr>
                                            <?php else: ?>
                                            <?php foreach ($reclamations as $reclamation): ?>
                                            <tr>
                                                <td>R-<?= $reclamation['id_reclamation'] ?></td>
                                                <td><?= date('d/m/Y', strtotime($reclamation['date_soumission'])) ?></td>
                                                <td><?= htmlspecialchars($reclamation['type_reclamation']) ?></td>
                                                <td>
                                                    <?php 
                                                    $badge_class = [
                                                        'en attente' => 'bg-secondary',
                                                        'en cours' => 'bg-info',
                                                        'résolue' => 'bg-success'
                                                    ][$reclamation['statut']] ?? 'bg-light text-dark';
                                                    ?>
                                                    <span class="badge <?= $badge_class ?>">
                                                        <?= ucfirst($reclamation['statut']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 250px;">
                                                        <?= htmlspecialchars($reclamation['description']) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" 
                                                            data-bs-target="#reclamationModal<?= $reclamation['id_reclamation'] ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <?php if ($reclamation['statut'] === 'en attente'): ?>
                                                    <form method="POST" style="display: inline-block;">
                                                        <input type="hidden" name="id_reclamation" value="<?= $reclamation['id_reclamation'] ?>">
                                                        <button type="submit" name="delete_reclamation" class="btn btn-sm btn-outline-danger"
                                                                onclick="return confirm('Supprimer cette réclamation ?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>

                                            <!-- Modal pour voir les détails -->
                                            <div class="modal fade" id="reclamationModal<?= $reclamation['id_reclamation'] ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">
                                                                Réclamation R-<?= $reclamation['id_reclamation'] ?>
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row mb-3">
                                                                <div class="col-md-4">
                                                                    <strong>Date :</strong>
                                                                    <p><?= date('d/m/Y H:i', strtotime($reclamation['date_soumission'])) ?></p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <strong>Type :</strong>
                                                                    <p><?= htmlspecialchars($reclamation['type_reclamation']) ?></p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <strong>Statut :</strong>
                                                                    <p><span class="badge <?= $badge_class ?>">
                                                                        <?= ucfirst($reclamation['statut']) ?>
                                                                    </span></p>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <strong>Description :</strong>
                                                                <p class="p-3 bg-light rounded">
                                                                    <?= nl2br(htmlspecialchars($reclamation['description'])) ?>
                                                                </p>
                                                            </div>
                                                            <?php if (!empty($reclamation['reponse'])): ?>
                                                            <div class="mb-3">
                                                                <strong>Réponse du service client :</strong>
                                                                <p class="p-3 bg-light rounded">
                                                                    <?= nl2br(htmlspecialchars($reclamation['reponse'])) ?>
                                                                </p>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
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
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        // Afficher l'année actuelle dans le footer
        document.getElementById('displayDateYear').textContent = new Date().getFullYear();
        
        // Filtrer les réclamations
        document.querySelectorAll('.btn-group .btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.textContent.trim();
                const rows = document.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    if (filter === 'Toutes') {
                        row.style.display = '';
                    } else {
                        const status = row.querySelector('td:nth-child(4)').textContent.trim();
                        row.style.display = status.includes(filter) ? '' : 'none';
                    }
                });
                
                // Mettre à jour l'état actif des boutons
                document.querySelectorAll('.btn-group .btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>