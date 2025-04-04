<div class="row mb-4">
    <div class="col-12">
    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Anomalies de Relevé à Traiter</h5>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#anomaliesModal">
            Voir toutes les anomalies
        </button>
        </div>
        <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="anomaliesTable">
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
                <tr>
                <td>CL-2345</td>
                <td>Ahmed Bennani</td>
                <td>05/03/2025</td>
                <td>350 kWh</td>
                <td><span class="badge bg-danger">+128%</span></td>
                <td>
                    <button class="btn btn-sm btn-primary">Vérifier</button>
                    <button class="btn btn-sm btn-outline-success">Valider</button>
                </td>
                </tr>
                <tr>
                <td>CL-4127</td>
                <td>Sara Alaoui</td>
                <td>07/03/2025</td>
                <td>12 kWh</td>
                <td><span class="badge bg-warning">-85%</span></td>
                <td>
                    <button class="btn btn-sm btn-primary">Vérifier</button>
                    <button class="btn btn-sm btn-outline-success">Valider</button>
                </td>
                </tr>
                <tr>
                <td>CL-3089</td>
                <td>Karim Tazi</td>
                <td>08/03/2025</td>
                <td>420 kWh</td>
                <td><span class="badge bg-danger">+145%</span></td>
                <td>
                    <button class="btn btn-sm btn-primary">Vérifier</button>
                    <button class="btn btn-sm btn-outline-success">Valider</button>
                </td>
                </tr>
            </tbody>
            </table>
        </div>
        </div>
    </div>
    </div>
</div>