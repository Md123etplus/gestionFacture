<div class="modal fade" id="reclamationsModal" tabindex="-1" aria-labelledby="reclamationsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reclamationsModalLabel">Toutes les Réclamations</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-striped" id="allReclamationsTable">
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
              <!-- Rempli dynamiquement par JS -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
