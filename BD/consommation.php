<?php
require_once 'Connexion.php';

// CREATE
function createConsommation($client_id, $mois, $annee, $valeur_compteur, $photo_compteur = null) {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("INSERT INTO consommation 
                          (client_id, mois, annee, valeur_compteur, photo_compteur, date_saisie) 
                          VALUES (?, ?, ?, ?, ?, NOW())");
    return $stmt->execute([$client_id, $mois, $annee, $valeur_compteur, $photo_compteur]);
}

// READ
function getConsommationByClient($client_id) {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("SELECT * FROM consommation WHERE client_id = ? ORDER BY annee DESC, mois DESC");
    $stmt->execute([$client_id]);
    return $stmt->fetchAll();
}

// UPDATE
function validateConsommation($id, $validee) {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("UPDATE consommation SET validee = ? WHERE id_consommation = ?");
    return $stmt->execute([$validee, $id]);
}

// DELETE
function deleteConsommation($id) {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("DELETE FROM consommation WHERE id_consommation = ?");
    return $stmt->execute([$id]);
}