<?php
require_once 'Connexion.php';

// CREATE - Add new supplier
function createFournisseur($id_fournisseur, $departement) {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("INSERT INTO fournisseur 
                          (id_fournisseur, departement) 
                          VALUES (?, ?)");
    return $stmt->execute([$id_fournisseur, $departement]);
}

// READ - Get supplier by ID
function getFournisseurById($id) {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("SELECT * FROM fournisseur 
                          WHERE id_fournisseur = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// READ - Get all suppliers
function getAllFournisseurs() {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("SELECT * FROM fournisseur ORDER BY id_fournisseur");
    $stmt->execute();
    return $stmt->fetchAll();
}

// UPDATE - Update supplier information
function updateFournisseur($id, $departement) {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("UPDATE fournisseur 
                          SET departement = ? 
                          WHERE id_fournisseur = ?");
    return $stmt->execute([$departement, $id]);
}

// DELETE - Remove supplier
function deleteFournisseur($id) {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("DELETE FROM fournisseur 
                          WHERE id_fournisseur = ?");
    return $stmt->execute([$id]);
}