<?php
// Définition de la constante ROOT pour les inclusions de fichiers
define('ROOT', str_replace('Traitement\Admin\loadRecentReclamations.php', '', $_SERVER['SCRIPT_FILENAME']));

require_once ROOT . 'Traitement\Utilisateurs.php';

try {
    // Connexion à la base de données déjà définie dans Utilisateurs.php
    $query = $db->query("
        SELECT r.id, c.nom, r.type, r.date_reclamation, r.facture_id, r.statut 
        FROM Reclamations r
        JOIN Clients c ON r.client_id = c.id
        ORDER BY r.date_reclamation DESC
        LIMIT 5
    ");

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        // Définition de la classe de badge en fonction du statut
        $badgeClass = ($row['statut'] == 'Non traitée') ? 'bg-danger' : 
                      (($row['statut'] == 'En cours') ? 'bg-warning' : 'bg-success');

        echo "<tr>
                <td>R-{$row['id']}</td>
                <td>{$row['nom']}</td>
                <td>{$row['type']}</td>
                <td>{$row['date_reclamation']}</td>
                <td>F-{$row['facture_id']}</td>
                <td><span class='badge $badgeClass'>{$row['statut']}</span></td>
                <td>
                    <button class='btn btn-sm btn-primary'>Voir</button>
                    <button class='btn btn-sm btn-success'>Traiter</button>
                </td>
              </tr>";
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='7'>Erreur : " . $e->getMessage() . "</td></tr>";
}
?>
