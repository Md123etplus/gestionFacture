<?php
define('ROOT', str_replace('Traitement\Admin\loadClaimsDistribution.php', '', $_SERVER['SCRIPT_FILENAME']));
require_once ROOT . 'Traitement\Utilisateurs.php';

try {
    $stmt = $db->query("SELECT type, COUNT(*) AS total FROM Reclamations GROUP BY type");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $labels = [];
    $values = [];
    foreach ($data as $row) {
        $labels[] = $row['type'];
        $values[] = $row['total'];
    }

    echo json_encode(['labels' => $labels, 'values' => $values]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
