<?php
require_once 'Connexion.php';

// CREATE
function createFacture($client_id, $date_emission, $date_echeance, $montant_ht, $tva, $montant_ttc) {
    $pdo = Connexion();
    $stmt = $pdo->prepare("INSERT INTO facture (client_id, date_emission, date_echeance, montant_ht, tva, montant_ttc) 
                          VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$client_id, $date_emission, $date_echeance, $montant_ht, $tva, $montant_ttc]);
}

// READ
function getFacturesByClient($client_id) {
    $pdo = Connexion();
    $stmt = $pdo->prepare("SELECT * FROM facture WHERE client_id = ? ORDER BY date_emission DESC");
    $stmt->execute([$client_id]);
    return $stmt->fetchAll();
}

// UPDATE
function updateFactureStatus($id, $statut) {
    $pdo = Connexion();
    $stmt = $pdo->prepare("UPDATE facture SET statut = ? WHERE id_facture = ?");
    return $stmt->execute([$statut, $id]);
}

// DELETE
function deleteFacture($id) {
    $pdo = Connexion();
    $stmt = $pdo->prepare("DELETE FROM facture WHERE id_facture = ?");
    return $stmt->execute([$id]);
}
// READ - Get single invoice by ID
function getFactureById($id) {
    $pdo = Connexion();
    $stmt = $pdo->prepare("SELECT * FROM facture WHERE id_facture = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Calculate consumption for an invoice
function getConsommationForFacture($facture_id) {
    $pdo = Connexion();
    $facture = getFactureById($facture_id);
    
    $stmt = $pdo->prepare("SELECT * FROM consommation 
                          WHERE client_id = ? AND MONTH(date_saisie) = MONTH(?) AND YEAR(date_saisie) = YEAR(?)");
    $stmt->execute([$facture['client_id'], $facture['date_emission'], $facture['date_emission']]);
    return $stmt->fetch();
}