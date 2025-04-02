<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VoltForce</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
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
      <div class="header_bottom">
        <div class="container-fluid">
          <nav class="navbar navbar-expand-lg custom_nav-container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav">
                <li class="nav-item active">
                  <a class="nav-link" href="index.php">Accueil</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="about.php">À propos</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="service.php">Services</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="contact.php">Contact</a>
                </li>
              </ul>
            </div>
          </nav>
        </div>
      </div>
    </header>

    <!-- Slider Section -->
    <section class="slider_section">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <div class="detail-box">
              <h1>Gérez vos factures d'électricité en toute simplicité</h1>
              <p>Consultez vos factures, saisissez votre consommation, et faites des réclamations en quelques clics.</p>
              <!-- Bouton "Se connecter" qui redirige vers la page de connexion -->
              <a href="login.php" class="btn btn-primary">Se connecter</a>
            </div>
          </div>
          <div class="col-md-6">
            <div class="img-box">
              <img src="images/electiriciet.webp" alt="Électricité">
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
</body>

</html>