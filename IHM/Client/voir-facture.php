<?php
require_once '../../Traitement/Client/voir-facture.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Facture #<?= $facture_id ?> - VoltForce</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <style>
    .facture-header { background-color: #f8f9fa; padding: 20px; border-radius: 5px; }
    .facture-total { font-weight: bold; font-size: 1.2rem; }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="row mb-4">
      <div class="col-12 text-center">
        <img src="images/electricite.png" alt="VoltForce" style="height: 80px;">
        <h1 class="mt-3">Facture #<?= $facture_id ?></h1>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-md-6">
        <div class="facture-header">
          <h4>VoltForce</h4>
          <p>Fournisseur d'électricité<br>
          Casablanca, Maroc<br>
          contact@voltforce.ma</p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="facture-header">
          <h4><?php echo $user['nom']; ?></h4>
          <p>Compteur: <?= htmlspecialchars($client['numero_compteur']) ?><br>
          <?= htmlspecialchars($client['adresse_installation']) ?><br>
          Client depuis: <?= date('Y') ?></p>
        </div>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-md-4">
                <strong>Date facture:</strong> <?= date('d/m/Y', strtotime($facture['date_emission'])) ?>
              </div>
              <div class="col-md-4">
                <strong>Date échéance:</strong> <?= date('d/m/Y', strtotime($facture['date_echeance'])) ?>
              </div>
              <div class="col-md-4 text-end">
                <span class="badge <?= $facture['statut'] == 'payée' ? 'bg-success' : 'bg-danger' ?>">
                  <?= ucfirst($facture['statut']) ?>
                </span>
              </div>
            </div>
          </div>
          <div class="card-body">
              <?php if (!empty($consommation['photo_compteur'])): ?>
                  <h4>Photo du compteur :</h4>
                  <img src="../../<?= htmlspecialchars($consommation['photo_compteur']) ?>" alt="Photo compteur" style="max-width: 300px;">
              <?php else: ?>
                  <p>Aucune photo disponible</p>
              <?php endif; ?>


            <div class="row">
              <div class="col-12">
                <h5>Détails de la consommation</h5>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Période</th>
                      <th>Consommation</th>
                      <th>Prix unitaire</th>
                      <th>Montant</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Détails des tranches de consommation -->
                    <tr>
                      <td>0-100 kWh</td>
                      <td>100 kWh</td>
                      <td>0.82 DH</td>
                      <td>82.00 DH</td>
                    </tr>
                    <!-- Ajoutez d'autres tranches si nécessaire -->
                  </tbody>
                </table>
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-md-6 offset-md-6">
                <table class="table">
                  <tr>
                    <td><strong>Total HT:</strong></td>
                    <td class="text-end"><?= number_format($facture['montant_ht'], 2) ?> DH</td>
                  </tr>
                  <tr>
                    <td><strong>TVA (18.95%):</strong></td>
                    <td class="text-end"><?= number_format($facture['montant_ttc'] - $facture['montant_ht'], 2) ?> DH</td>
                  </tr>
                  <tr class="facture-total">
                    <td><strong>Total TTC:</strong></td>
                    <td class="text-end"><?= number_format($facture['montant_ttc'], 2) ?> DH</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          <div class="card-footer text-end">
            <a href="mes-factures.php" class="btn btn-secondary">Retour</a>
            <?php if ($facture['statut'] == 'impayée'): ?>
            <a href="payer-facture.php?id=<?= $facture_id ?>" class="btn btn-success">Payer maintenant</a>
            <?php endif; ?>
            <a href="telecharger-facture.php?id=<?= $facture_id ?>" class="btn btn-primary">Télécharger PDF</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>