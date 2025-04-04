<div class="row">
    <div class="col-12">
    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Réclamations Récentes</h5>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#reclamationsModal">
            Gérer toutes les réclamations
        </button>
        </div>
        <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="recentReclamationsTable">
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
                <tr>
                <td>R-4582</td>
                <td>Mohammed El Amrani</td>
                <td>Fuite externe</td>
                <td>10/03/2025</td>
                <td>F-2025-0356</td>
                <td><span class="badge bg-danger">Non traitée</span></td>
                <td>
                    <button class="btn btn-sm btn-primary">Voir</button>
                    <button class="btn btn-sm btn-success">Traiter</button>
                </td>
                </tr>
                <tr>
                <td>R-4580</td>
                <td>Fatima Zahra</td>
                <td>Fuite interne</td>
                <td>09/03/2025</td>
                <td>F-2025-0341</td>
                <td><span class="badge bg-warning">En cours</span></td>
                <td>
                    <button class="btn btn-sm btn-primary">Voir</button>
                    <button class="btn btn-sm btn-success">Finaliser</button>
                </td>
                </tr>
                <tr>
                <td>R-4575</td>
                <td>Youssef Nabil</td>
                <td>Erreur de facturation</td>
                <td>08/03/2025</td>
                <td>F-2025-0329</td>
                <td><span class="badge bg-danger">Non traitée</span></td>
                <td>
                    <button class="btn btn-sm btn-primary">Voir</button>
                    <button class="btn btn-sm btn-success">Traiter</button>
                </td>
                </tr>
            </tbody>
            </table>
        </div>
        </div>
    </div>
    </div>
</div>