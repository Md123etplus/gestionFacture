$(document).ready(function () {
    // Charger les statistiques du tableau de bord
    function loadStatistics() {
        $.ajax({
            url: 'Traitement/Admin/loadStatistics.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    $(".card:has(.card-title:contains('Total Clients')) .display-4").text(data.totalClients);
                    $(".card:has(.card-title:contains('Consommation Totale')) .display-4").text(data.totalConsommation + " kWh");
                    $(".card:has(.card-title:contains('Factures Impayées')) .display-4").text(data.facturesImpayees);
                    $(".card:has(.card-title:contains('Factures Impayées')) p:nth-child(3)").text(data.montantImpayé + " DH");
                    $(".card:has(.card-title:contains('Réclamations')) .display-4").text(data.reclamationsNonTraitees);
                } else {
                    console.error("Erreur de chargement des statistiques:", data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("Erreur AJAX:", status, error);
            }
        });
    }

    // Charger les anomalies de relevé
    function loadAnomalies() {
        $.ajax({
            url: 'Traitement/Admin/loadAnomalies.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $("#anomaliesTable tbody").html(response.html);
                } else {
                    console.error("Erreur: " + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("Erreur AJAX:", status, error);
            }
        });
    }
    

    // Charger les réclamations récentes
    function loadRecentReclamations() {
        $.ajax({
            url: 'Traitement/Admin/loadRecentReclamations.php',
            type: 'GET',
            success: function (response) {
                $("#recentReclamationsTable tbody").html(response);
            },
            error: function (xhr, status, error) {
                console.error("Erreur AJAX:", status, error);
            }
        });
    }
    function loadGlobalConsumption() {
        $.ajax({
            url: 'Traitement/Admin/loadGlobalConsumption.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.labels && data.values) {
                    const ctx = document.getElementById('globalConsumptionChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Consommation Totale (kWh)',
                                data: data.values,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 2,
                                tension: 0.3
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: false,
                                    title: { display: true, text: 'kWh' }
                                }
                            }
                        }
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error("Erreur de chargement des données de consommation:", status, error);
            }
        });
    }

    // Charger les données du graphique de répartition des réclamations
    function loadClaimsDistribution() {
        $.ajax({
            url: 'Traitement/Admin/loadClaimsDistribution.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.labels && data.values) {
                    const ctx = document.getElementById('claimsChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: data.values,
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.7)',
                                    'rgba(54, 162, 235, 0.7)',
                                    'rgba(255, 206, 86, 0.7)',
                                    'rgba(75, 192, 192, 0.7)'
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { position: 'bottom' } }
                        }
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error("Erreur de chargement des données de réclamations:", status, error);
            }
        });
    }

    // Appel des fonctions au chargement de la page
    loadStatistics();
    loadAnomalies();
    loadRecentReclamations();
    loadClaimsDistribution();
    loadGlobalConsumption();
});
