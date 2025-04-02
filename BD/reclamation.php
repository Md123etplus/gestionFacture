<?php
require_once 'Connexion.php';

// CREATE
function createReclamation($client_id, $type_reclamation, $description) {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("INSERT INTO reclamation (client_id, type_reclamation, description) 
                          VALUES (?, ?, ?)");
    return $stmt->execute([$client_id, $type_reclamation, $description]);
}

// READ
function getReclamationsByClient($client_id) {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("SELECT * FROM reclamation WHERE client_id = ? ORDER BY date_soumission DESC");
    $stmt->execute([$client_id]);
    return $stmt->fetchAll();
}

// UPDATE
function updateReclamationStatus($id, $statut) {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("UPDATE reclamation SET statut = ? WHERE id_reclamation = ?");
    return $stmt->execute([$statut, $id]);
}

// DELETE
function deleteReclamation($id) {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("DELETE FROM reclamation WHERE id_reclamation = ?");
    return $stmt->execute([$id]);
}