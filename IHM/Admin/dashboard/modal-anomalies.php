<div class="modal fade" id="anomaliesModal" tabindex="-1" aria-labelledby="anomaliesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="anomaliesModalLabel">Toutes les Anomalies</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-striped" id="allAnomaliesTable">
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
              <!-- Rempli dynamiquement par JS -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
