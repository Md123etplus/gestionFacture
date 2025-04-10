<?php
require_once '../../Traitement/Client/mes-factures.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mes Factures - VoltForce</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="hero_area">
    <!-- Header Section -->
    <?php
      include("header.php");
    ?>
    
    <section class="factures_section py-5">
      <div class="container">
        <div class="row mb-4">
          <div class="col-12">
            <h2>Mes Factures</h2>
            <p class="text-muted">Consultez l'historique complet de vos factures.</p>
          </div>
        </div>

        <!-- Filtres -->
        <div class="row mb-4">
          <div class="col-md-8">
            <form method="get" class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label for="annee" class="form-label">Année</label>
                    <select class="form-select" id="annee" name="annee">
                      <option value="all">Toutes</option>
                      <?php
                      $annees = array_unique(array_map(function($f) {
                          return date('Y', strtotime($f['date_emission']));
                      }, getFacturesByClient($client_id)));
                      
                      foreach ($annees as $a): ?>
                      <option value="<?= $a ?>" <?= $annee == $a ? 'selected' : '' ?>><?= $a ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="statut" class="form-label">Statut</label>
                    <select class="form-select" id="statut" name="statut">
                      <option value="all" <?= $statut == 'all' ? 'selected' : '' ?>>Tous</option>
                      <option value="payée" <?= $statut == 'payée' ? 'selected' : '' ?>>Payées</option>
                      <option value="impayée" <?= $statut == 'impayée' ? 'selected' : '' ?>>Impayées</option>
                    </select>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="col-md-4">
            <div class="card bg-light">
              <div class="card-body text-center">
                <h5 class="card-title">Total impayé</h5>
                <h3 class="text-danger">
                  <?= number_format(array_reduce($factures, function($carry, $f) {
                      return $carry + ($f['statut'] == 'impayée' ? $f['montant_ttc'] : 0);
                  }, 0), 2) ?> DH
                </h3>
                <a href="payer-tout.php" class="btn btn-primary">Payer tout</a>
              </div>
            </div>
          </div>
        </div>

        <!-- Liste des factures -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <?php if (empty($factures)): ?>
                <div class="alert alert-info">Aucune facture trouvée</div>
                <?php else: ?>
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Référence</th>
                        <th>Date</th>
                        <th>Période</th>
                        <th>Montant TTC</th>
                        <th>Statut</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($factures as $facture): ?>
                      <tr>
                        <td>F-<?= $facture['id_facture'] ?></td>
                        <td><?= date('d/m/Y', strtotime($facture['date_emission'])) ?></td>
                        <td><?= date('F Y', strtotime($facture['date_emission'])) ?></td>
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
                          <a href="telecharger-facture.php?id=<?= $facture['id_facture'] ?>" class="btn btn-sm btn-outline-secondary">PDF</a>
                          <?php endif; ?>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Footer (identique) -->
</body>
</html>