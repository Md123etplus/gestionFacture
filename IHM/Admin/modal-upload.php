<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadModalLabel">Téléverser un fichier de consommation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="uploadForm" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="fileUpload" class="form-label">Sélectionnez un fichier (CSV, Excel) :</label>
            <input type="file" class="form-control" id="fileUpload" name="fileUpload" accept=".csv,.xlsx,.xls">
          </div>
          <button type="submit" class="btn btn-primary">Envoyer</button>
          <div id="uploadStatus" class="mt-3"></div>
        </form>
      </div>
    </div>
  </div>
</div>