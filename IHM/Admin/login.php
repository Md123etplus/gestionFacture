
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - VoltForce</title>
  <link rel="stylesheet" href="/IHM/css/bootstrap.css">
  <link rel="stylesheet" href="/IHM/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <div class="hero_area">
    <header class="header_section">
      <div class="header_top">
        <div class="container-fluid">
          <div class="brand_nav">
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

    <section class="login_section layout_padding">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <div class="login_logo">
              <img src="images/electricite.png" alt="Logo VoltForce" class="logo_large">
              <h2>VoltForce</h2>
              <p>Gérez vos factures d'électricité en toute simplicité.</p>
            </div>
          </div>

          <div class="col-md-6">
            <div class="login_form">
              <div class="heading_container">
                <h2>Connexion</h2>
              </div>

              <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                  <?php echo $error; ?>
                </div>
              <?php endif; ?>

              <form action="/Traitement/Utilisateurs.php" method="post">
                <div class="form-group">
                  <label for="username">Email</label>
                  <input type="email" class="form-control" id="username" name="username" placeholder="Entrez votre email" required>
                </div>
                <div class="form-group">
                  <label for="password">Mot de passe</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Entrez votre mot de passe" required>
                </div>
                <div class="form-group">
                  <button type="submit" name="handle_login" class="btn btn-primary btn-block">Se connecter</button>
                </div>
                <div class="form-group text-center">
                  <a href="forgot_password.html">Mot de passe oublié ?</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <footer class="footer_section">
    <div class="container">
      <p>&copy; <span id="displayDateYear"></span> VoltForce. Tous droits réservés.</p>
      <p>
        <a href="privacy.html">Politique de confidentialité</a> | 
        <a href="terms.html">Mentions légales</a> | 
        <a href="contact.html">Contact</a>
      </p>
    </div>
  </footer>

  <script src="/IHM/js/jquery-3.4.1.min.js"></script>
  <script src="/IHM/js/bootstrap.js"></script>
  <script src="/IHM/js/custom.js"></script>
</body>
</html>