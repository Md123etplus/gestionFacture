<?php
require_once 'Connexion.php';

// CREATE - Add annual consumption record
function createConsommationAnnuelle($client_id, $annee, $consommation_totale, $id_agent) {
    $pdo = Connexion();
    $stmt = $pdo->prepare("INSERT INTO consommationannuelle 
                          (client_id, annee, consommation_totale, date_generation, id_agent) 
                          VALUES (?, ?, ?, CURDATE(), ?)");
    return $stmt->execute([$client_id, $annee, $consommation_totale, $id_agent]);
}

// READ - Get annual consumption by client ID
function getConsommationAnnuelleByClient($client_id) {
    $pdo = Connexion();
    $stmt = $pdo->prepare("SELECT * FROM consommationannuelle 
                          WHERE client_id = ? ORDER BY annee DESC");
    $stmt->execute([$client_id]);
    return $stmt->fetchAll();
}

// READ - Get annual consumption by year
function getConsommationAnnuelleByYear($annee) {
    $pdo = Connexion();
    $stmt = $pdo->prepare("SELECT * FROM consommationannuelle 
                          WHERE annee = ? ORDER BY client_id");
    $stmt->execute([$annee]);
    return $stmt->fetchAll();
}

// UPDATE - Update annual consumption record
function updateConsommationAnnuelle($id, $consommation_totale) {
    $pdo = Connexion();
    $stmt = $pdo->prepare("UPDATE consommationannuelle 
                          SET consommation_totale = ?, date_generation = CURDATE() 
                          WHERE id_conso_annuelle = ?");
    return $stmt->execute([$consommation_totale, $id]);
}

// DELETE - Remove annual consumption record
function deleteConsommationAnnuelle($id) {
    $pdo = Connexion();
    $stmt = $pdo->prepare("DELETE FROM consommationannuelle 
                          WHERE id_conso_annuelle = ?");
    return $stmt->execute([$id]);
}