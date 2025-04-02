<?php
require_once 'Connexion.php';

// CREATe
function createUser($nom, $prenom, $email, $motDePasse, $type) {
    $pdo = getConnexion();
    $hash = password_hash($motDePasse, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, type) 
                          VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$nom, $prenom, $email, $hash, $type]);
}

// READ
function getUserById($id) {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// UPDATE
function updateUser($id, $nom, $prenom, $email) {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, email = ? 
                          WHERE id_utilisateur = ?");
    return $stmt->execute([$nom, $prenom, $email, $id]);
}

// DELETE
function deleteUser($id) {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id_utilisateur = ?");
    return $stmt->execute([$id]);
}
// Nouvelle fonction à ajouter pour compléter le CRUD
function getUserByEmail($email) {
    $pdo = getConnexion();
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}