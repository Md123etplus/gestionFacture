<?php
require_once 'Connexion.php';
// CREATE
function createTarif($plage_min, $plage_max, $prix_unitaire, $date_application) {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("INSERT INTO tarifelectricite (plage_min, plage_max, prix_unitaire, date_application) 
                          VALUES (?, ?, ?, ?)");
    return $stmt->execute([$plage_min, $plage_max, $prix_unitaire, $date_application]);
}

// READ
function getCurrentTarifs() {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("SELECT * FROM tarifelectricite 
                          WHERE date_application <= CURDATE() 
                          ORDER BY date_application DESC LIMIT 3");
    $stmt->execute();
    return $stmt->fetchAll();
}

// UPDATE
function updateTarif($id, $prix_unitaire) {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("UPDATE tarifelectricite SET prix_unitaire = ? WHERE id = ?");
    return $stmt->execute([$prix_unitaire, $id]);
}

// DELETE
function deleteTarif($id) {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("DELETE FROM tarifelectricite WHERE id = ?");
    return $stmt->execute([$id]);
}