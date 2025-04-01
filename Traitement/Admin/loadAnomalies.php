<?php 
header('Content-Type: application/json');
define('ROOT', str_replace('Traitement\Admin\loadAnomalies.php', '', $_SERVER['SCRIPT_FILENAME']));
require_once ROOT . 'Traitement\Utilisateurs.php'; // Déjà inclut BD\connexion.php

try {
    // Requête pour récupérer les anomalies de relevé
    $query = $db->query("
        SELECT 
            c.id AS client_id,
            c.nom AS client_nom,
            r.date_releve,
            r.consommation,
            (r.consommation - r.consommation_moyenne) / r.consommation_moyenne * 100 AS ecart
        FROM releves r
        JOIN clients c ON r.client_id = c.id
        WHERE ABS((r.consommation - r.consommation_moyenne) / r.consommation_moyenne) > 0.5
        ORDER BY r.date_releve DESC
    ");

    $anomalies = $query->fetchAll(PDO::FETCH_ASSOC);

    $html = "";
    foreach ($anomalies as $anomalie) {
        $ecart = round($anomalie['ecart']);
        $badgeClass = $ecart > 0 ? "bg-danger" : "bg-warning";
        $sign = $ecart > 0 ? "+" : "";

        $html .= "
            <tr>
                <td>CL-{$anomalie['client_id']}</td>
                <td>{$anomalie['client_nom']}</td>
                <td>" . date("d/m/Y", strtotime($anomalie['date_releve'])) . "</td>
                <td>{$anomalie['consommation']} kWh</td>
                <td><span class='badge $badgeClass'>{$sign}{$ecart}%</span></td>
                <td>
                    <button class='btn btn-sm btn-primary'>Vérifier</button>
                    <button class='btn btn-sm btn-outline-success'>Valider</button>
                </td>
            </tr>
        ";
    }

    echo json_encode(["success" => true, "html" => $html]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erreur BD: " . $e->getMessage()]);
}
?>
