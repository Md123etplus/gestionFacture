<?php
define('ROOT', str_replace('Traitement\Admin\loadGlobalConsumption.php', '', $_SERVER['SCRIPT_FILENAME']));
require_once ROOT . 'Traitement\Utilisateurs.php';

try {
    $stmt = $db->query("SELECT mois, consommation FROM consommation_globale ORDER BY mois ASC");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $labels = [];
    $values = [];
    foreach ($data as $row) {
        $labels[] = $row['mois'];
        $values[] = $row['consommation'];
    }

    echo json_encode(['labels' => $labels, 'values' => $values]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
