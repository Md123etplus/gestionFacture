$(document).ready(function () {
    // alert("Hey");

    // Charger les statistiques du tableau de bord
    function loadStatistics() {
        $.ajax({
            url: '/Traitement/Utilisateurs.php',
            type: 'GET',
            data: { action: "loadStatistics" },
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    console.log("Statistiques chargées avec succès:", data);
                    // console.log(data);
    
                    // Mise à jour du total des clients
                    var totalClients = data.total_clients !== null && data.total_clients !== undefined ? data.total_clients : 0;
                    $(".card:has(.card-title:contains('Total Clients')) .display-4").text(totalClients);
    
                    // Mise à jour de la consommation totale
                    var totalConsommation = data.total_consommation_mensuelle !== null && data.total_consommation_mensuelle !== undefined ? data.total_consommation_mensuelle: 0;
                    $(".card:has(.card-title:contains('Consommation Totale')) .display-4").text(totalConsommation + " kWh");
    
                    // Mise à jour des factures impayées
                    var facturesImpayees = data.factures_impayees !== null && data.factures_impayees !== undefined ? data.factures_impayees : 0;
                    $(".card:has(.card-title:contains('Factures Impayées')) .display-4").text(facturesImpayees);
    
                    var montantImpaye = data.montant_impaye !== null && data.montant_impaye !== undefined ? data.montant_impaye : 0;
                    $(".card:has(.card-title:contains('Factures Impayées')) p:nth-child(3)").text(montantImpaye + " DH");
    
                    // Mise à jour des réclamations non traitées
                    var reclamationsNonTraitees = data.reclamations_non_traitees !== null && data.reclamations_non_traitees !== undefined ? data.reclamations_non_traitees : 0;
                    $(".card:has(.card-title:contains('Réclamations')) .display-4").text(reclamationsNonTraitees);
    
                } else {
                    console.error("Erreur lors du chargement des statistiques:", data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("Erreur AJAX pour loadStatistics:", status, error);
            }
        });
    }
    

    // Charger les anomalies de relevé
    function loadAnomalies() {
        $.ajax({
            url: '/Traitement/Utilisateurs.php',
            type: 'GET',
            data: { action: "loadAnomalies" },
            dataType: 'json',
            success: function (response) {
                if (response.html.trim() === "") {
                    // Show a message when the HTML content is empty
                    $("#anomaliesTable tbody").html("<tr><td colspan='6'>Aucune anomalie trouvée.</td></tr>");
                } else {
                    // Insert the response HTML into the table
                    $("#anomaliesTable tbody").html(response.html);
                }
            },
            error: function (xhr, status, error) {
                
                console.error("Erreur AJAX pour loadAnomalies:", status, error);
            }
        });
    }

    // Charger les réclamations récentes
    function loadRecentReclamations() {
        $.ajax({
            url: '/Traitement/Utilisateurs.php',
            type: 'GET',
            data: { action: "loadRecentReclamations" },
            dataType: 'json',
            success: function (response) {
                // console.log("Réclamations récentes chargées avec succès:", response);
                if (response.html.trim() === "") {
                    // Show a message when the HTML content is empty
                    $("#recentReclamationsTable tbody").html("<tr><td colspan='6'>Aucune réclamation trouvée.</td></tr>");
                }else{
                    // Insert the response HTML into the table
                    $("#recentReclamationsTable tbody").html(response.html);
                }
                // $("#recentReclamationsTable tbody").html(response.html);
            },
            error: function (xhr, status, error) {
                console.error("Erreur AJAX pour loadRecentReclamations:", status, error);
            }
        });
    }

    // Charger les données de consommation globale
    function loadGlobalConsumption() {
        $.ajax({
            url: '/Traitement/Utilisateurs.php',
            type: 'GET',
            data: { action: "loadGlobalConsumption" },
            dataType: 'json',
            success: function (data) {
                // console.log("Global Consumption ",data);
                if (data.success && data.total_consumption) {
                    const ctx = document.getElementById('globalConsumptionChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Total'],
                            datasets: [{
                                label: 'Consommation Totale (kWh)',
                                data: [parseFloat(data.total_consumption)],
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 2,
                                tension: 0.3
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: { beginAtZero: false, title: { display: true, text: 'kWh' } }
                            }
                        }
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error("Erreur AJAX pour loadGlobalConsumption:", status, error);
            }
        });
    }

    // Charger les données du graphique de répartition des réclamations
    function loadClaimsDistribution() {
        $.ajax({
            url: '/Traitement/Utilisateurs.php',
            type: 'GET',
            data: { action: "loadClaimsDistribution" },
            dataType: 'json',
            success: function (data) {
                // console.log("Répartition des réclamations chargée avec succès:", data);
    
                if (data && typeof data === 'object') {
                    // Extract values while ignoring non-reclamation properties
                    const claimsArray = Object.values(data).filter(item => typeof item === 'object' && item.type_reclamation);
    
                    // Extract labels and values from the cleaned array
                    const labels = claimsArray.map(item => item.type_reclamation);
                    const values = claimsArray.map(item => item.count);
    
                    if (labels.length && values.length) {
                        const ctx = document.getElementById('claimsChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: labels,
                                datasets: [{
                                    data: values,
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.7)',
                                        'rgba(54, 162, 235, 0.7)',
                                        'rgba(255, 206, 86, 0.7)',
                                        'rgba(75, 192, 192, 0.7)',
                                        'rgba(153, 102, 255, 0.7)',
                                        'rgba(255, 159, 64, 0.7)',
                                        'rgba(201, 203, 207, 0.7)',
                                        'rgba(140, 20, 252, 0.7)' // Add more colors if needed
                                    ],
                                    borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)',
                                        'rgba(255, 159, 64, 1)',
                                        'rgba(201, 203, 207, 1)',
                                        'rgba(140, 20, 252, 1)' // Matching border color
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: { 
                                    legend: { position: 'bottom' }
                                }
                            }
                        });
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error("Erreur AJAX pour loadClaimsDistribution:", status, error);
            }
        });
    }
    
    

    // Appel des fonctions au chargement de la page
    loadStatistics();
    loadAnomalies();
    loadRecentReclamations();
    loadClaimsDistribution();
    loadGlobalConsumption();


    $('#reclamationsModal').on('shown.bs.modal', function () {
        $.ajax({
            url: '/Traitement/Utilisateurs.php',
            type: 'GET',
            data: { action: "loadAllReclamations" }, // à adapter selon ton backend
            dataType: 'json',
            success: function (data) {
                const tbody = $('#allReclamationsTable tbody');
                tbody.empty();
            
                if (data.success) {
                    tbody.html(data.html);
                } else {
                    tbody.html('<tr><td colspan="7">Aucune réclamation trouvée</td></tr>');
                }
            },
            
            error: function (xhr, status, error) {
                console.error("Erreur AJAX:", error);
            }
        });
    });
    
    // Fonctions utilitaires pour style et texte
    function getBadgeClass(statut) {
        switch (statut.toLowerCase()) {
            case 'soumise': return 'bg-danger';
            case 'en cours': return 'bg-warning';
            case 'resolue': return 'bg-success';
            default: return 'bg-secondary';
        }
    }
    
    function getActionLabel(statut) {
        switch (statut.toLowerCase()) {
            case 'soumise': return 'Traiter';
            case 'en cours': return 'Finaliser';
            case 'resolue': return 'Voir';
            default: return 'Voir';
        }
    }
    
    //pour les traitements dans reclamations
    $(document).ready(function () {
        // Voir
        $(document).on('click', '.btn-voir', function () {
            const id = $(this).data('id');
            $.post('/Traitement/Utilisateurs.php', {
                action: 'voirReclamation',
                id_reclamation: id
            }, function (response) {
                if (response.success) {
                    $('#modalVoirBody').html(response.html);
                    $('#modalVoir').modal('show');
                } else {
                    alert("Erreur lors du chargement des détails.");
                }
            }, 'json');
        });
    
        // Traiter
        // $(document).on('click', '.btn-traiter', function () {
        //     const id = $(this).data('id');
        //     $.post('/Traitement/Utilisateurs.php', {
        //         action: 'traiterReclamation',
        //         id_reclamation: id
        //     }, function (response) {
        //         if (response.success) {
        //             alert("Réclamation mise en cours de traitement.");
        //             location.reload();
        //         } else {
        //             alert("Erreur lors du traitement.");
        //         }
        //     }, 'json');
        // });
    
        // Finaliser
        $(document).on('click', '.btn-finaliser', function () {
            const id = $(this).data('id');
            if (confirm("Es-tu sûr de vouloir finaliser cette réclamation ?")) {
                $.post('/Traitement/Utilisateurs.php', {
                    action: 'finaliserReclamation',
                    id_reclamation: id
                }, function (response) {
                    if (response.success) {
                        alert("Réclamation finalisée avec succès.");
                        location.reload();
                    } else {
                        alert("Erreur lors de la finalisation.");
                    }
                }, 'json');
            }
        });
    });
    /////////////////////
    // document.getElementById('uploadForm').addEventListener('submit', function(event) {
    //     event.preventDefault();

    //     const fileInput = document.getElementById('fileUpload');
    //     const statusDiv = document.getElementById('uploadStatus');

    //     if (fileInput.files.length === 0) {
    //         statusDiv.innerHTML = '<p class="text-danger">Veuillez sélectionner un fichier.</p>';
    //         return;
    //     }

    //     const file = fileInput.files[0];

    //     if (!file.name.endsWith('.txt')) {
    //         statusDiv.innerHTML = '<p class="text-danger">Seuls les fichiers .txt sont acceptés.</p>';
    //         return;
    //     }

    //     const formData = new FormData();
    //     formData.append('fileUpload', file);

    //     // Use jQuery.ajax() to send the file
    //     $.ajax({
    //         url: '/Traitement/Utilisateurs.php',
    //         type: 'POST',
    //         data: formData,
    //         processData: false, // Prevent jQuery from processing the data
    //         contentType: false, // Prevent jQuery from setting contentType header
    //         success: function(response) {
    //             const data = JSON.parse(response); // Parse the JSON response
    //             if (data.success) {
    //                 statusDiv.innerHTML = `<p class="text-success">${data.message}</p>`;
    //             } else {
    //                 statusDiv.innerHTML = `<p class="text-danger">${data.message}</p>`;
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('Error during file upload:', status, error);
    //             statusDiv.innerHTML = '<p class="text-danger">Erreur lors du téléversement du fichier.</p>';
    //         }
    //     });
    // });
});
   