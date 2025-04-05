<?php
require_once 'Connexion.php';

// CREATE
function createClient($id_client, $numero_compteur, $adresse_installation) {
    $pdo = Connexion();
    $stmt = $pdo->prepare("INSERT INTO client (id_client, numero_compteur, adresse_installation) 
                          VALUES (?, ?, ?)");
    return $stmt->execute([$id_client, $numero_compteur, $adresse_installation]);
}

// READ
function getClientById($id) {
    $pdo = Connexion();
    $stmt = $pdo->prepare("SELECT * FROM client WHERE id_client = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// UPDATE
function updateClient($id, $numero_compteur, $adresse_installation) {
    $pdo = Connexion();
    $stmt = $pdo->prepare("UPDATE client SET numero_compteur = ?, adresse_installation = ? 
                          WHERE id_client = ?");
    return $stmt->execute([$numero_compteur, $adresse_installation, $id]);
}

// DELETE
function deleteClient($id) {
    $pdo = Connexion();
    $stmt = $pdo->prepare("DELETE FROM client WHERE id_client = ?");
    return $stmt->execute([$id]);
}
// READ - Get client by user ID
function getClientByUserId($user_id) {
    $pdo = Connexion();
    $stmt = $pdo->prepare("SELECT * FROM client WHERE id_client = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}