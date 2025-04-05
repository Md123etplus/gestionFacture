<?php
session_start();
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - VoltForce</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
  <!-- Lien pour les icônes FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
              <a class="navbar-brand" href="index.php">
                <img src="images/electricite.png" alt="Logo VoltForce" class="logo">
                <span>VoltForce</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Connexion Section -->
    <section class="login_section layout_padding">
      <div class="container">
        <div class="row">
          <!-- ... (logo à gauche) ... -->
          <div class="col-md-6">
            <div class="login_form">
              <div class="heading_container">
                <h2>Connexion</h2>
                <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
              </div>
              <form action="../../Traitement/Client/login.php" method="post">
                <div class="form-group">
                  <label for="username">Email</label>
                  <input type="email" class="form-control" id="username" name="username"
                         placeholder="Entrez votre email" required>
                </div>
                <div class="form-group">
                  <label for="password">Mot de passe</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Entrez votre mot de passe" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                      <i class="fas fa-eye"></i>
                    </button>
                  </div>
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
                </div>
                <div class="form-group text-center">
                  <a href="forgot_password.php">Mot de passe oublié ?</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Footer Section -->
  <footer class="footer_section">
    <div class="container">
      <p>&copy; <span id="displayDateYear"></span> VoltForce. Tous droits réservés.</p>
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
    // Fonction pour afficher/masquer le mot de passe
    document.getElementById('togglePassword').addEventListener('click', function() {
      const passwordInput = document.getElementById('password');
      const icon = this.querySelector('i');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    });
  </script>
</body>

</html>