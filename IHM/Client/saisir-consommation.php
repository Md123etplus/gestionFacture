<?php
require_once '../../Traitement/Client/saisir-consomation.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisir Consommation - VoltForce</title>
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
                                <li class="nav-item active">
                                    <a class="nav-link" href="saisir-consommation.php">Saisir Consommation</a>
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
                        <h2>Saisir ma consommation</h2>
                        <p class="text-muted">Veuillez entrer la valeur actuelle de votre compteur et prendre une photo si possible.</p>
                    </div>
                </div>

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
                            Votre consommation a bien été enregistrée !
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">Nouvelle lecture du compteur</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="valeur_compteur" class="form-label">Valeur du compteur (kWh)</label>
                                        <input type="number" step="0.01" class="form-control" id="valeur_compteur" 
                                               name="valeur_compteur" required
                                               value="<?= $last_consumption ? htmlspecialchars($last_consumption['valeur_compteur'] + 1) : '' ?>">
                                        <?php if ($last_consumption): ?>
                                        <div class="form-text">Dernière valeur enregistrée (Du dernier mois) : <?= htmlspecialchars($last_consumption['valeur_compteur']) ?> kWh</div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="photo_compteur" class="form-label">Photo du compteur</label>
                                        <input type="file" class="form-control" id="photo_compteur" name="photo_compteur" accept="image/*" required>
                                        <div class="form-text">Formats acceptés : JPG, PNG, GIF (max 2MB)</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Date et heure de la lecture</label>
                                        <input type="text" class="form-control" value="<?= date('d/m/Y H:i') ?>" readonly>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    <a href="dashboard_finalUser.php" class="btn btn-secondary">Annuler</a>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">Conseils pour la photo</h5>
                            </div>
                            <div class="card-body">
                                <ol class="list-group list-group-numbered">
                                    <li class="list-group-item">Assurez-vous que le compteur est bien visible</li>
                                    <li class="list-group-item">Prenez la photo de face, sans reflet</li>
                                    <li class="list-group-item">Vérifiez que les chiffres sont lisibles</li>
                                    <li class="list-group-item">Évitez les ombres sur le compteur</li>
                                </ol>
                                
                                <?php if ($last_consumption && !empty($last_consumption['photo_compteur'])): ?>
                                <div class="mt-3">
                                    <h6>Dernière photo envoyée (Du dernier mois) :</h6>
                                    <img src="<?= '../../'.htmlspecialchars($last_consumption['photo_compteur']) ?>" 
                                         alt="Dernière photo du compteur (Du dernier mois)" 
                                         class="img-thumbnail mt-2" style="max-height: 150px;">
                                </div>
                                <?php endif; ?>
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
    <script>
        // Afficher l'année actuelle dans le footer
        document.getElementById('displayDateYear').textContent = new Date().getFullYear();
        
        // const day = new Date().getDate();
        // if (day < 18) {
        //     document.querySelector('button[type="submit"]').disabled = true;
        // }

        document.addEventListener('DOMContentLoaded', function () {
        const today = new Date();
        const day = today.getDate();

        if (day < 18) {
            // Sélectionner tous les champs du formulaire
            const formElements = document.querySelectorAll('form input, form button, form select, form textarea');
            formElements.forEach(el => {
                el.disabled = true;
            });

            // Ajouter un message d'information
            const message = document.createElement('div');
            message.className = 'alert alert-warning mt-3';
            message.innerHTML = `
                <strong>Saisie temporairement désactivée.</strong><br>
                Vous pourrez saisir votre consommation à partir du <strong>18</strong> de ce mois.
            `;

            const form = document.querySelector('form');
            form.parentNode.insertBefore(message, form);
        }
    });
    </script>
</body>
</html>