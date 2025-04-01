<?php 
header('Content-Type: application/json');
define('ROOT', str_replace('Traitement\Admin\loadStatistics.php', '', $_SERVER['SCRIPT_FILENAME']));
require_once ROOT . 'Traitement\Utilisateurs.php'; 

$response = ["success" => false];

try {
    $query = $db->query("
        SELECT 
            (SELECT COUNT(*) FROM clients) AS totalClients,
            (SELECT SUM(consommation) FROM factures) AS totalConsommation,
            (SELECT COUNT(*) FROM factures WHERE statut = 'impayée') AS facturesImpayees,
            (SELECT SUM(montant) FROM factures WHERE statut = 'impayée') AS montantImpayé,
            (SELECT COUNT(*) FROM reclamations WHERE statut = 'non traité') AS reclamationsNonTraitees
    ");

    $data = $query->fetch(PDO::FETCH_ASSOC);

    $response = [
        "success" => true,
        "totalClients" => $data["totalClients"],
        "totalConsommation" => $data["totalConsommation"],
        "facturesImpayees" => $data["facturesImpayees"],
        "montantImpayé" => number_format($data["montantImpayé"], 2, ',', ' '),
        "reclamationsNonTraitees" => $data["reclamationsNonTraitees"]
    ];
} catch (PDOException $e) {
    $response["message"] = "Erreur BD: " . $e->getMessage();
}

echo json_encode($response);
?>
