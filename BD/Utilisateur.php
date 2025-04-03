<?php
require_once 'Connexion.php';

function add_client($nom, $prenom, $email, $mot_de_passe, $type, $numero_compteur, $adresse_installation){
    $conn = connexion();
    $sql_utilisateur = "INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, type) 
                            VALUES ('$nom', '$prenom', '$email', '$mot_de_passe', '$type')";

    if ($conn->query($sql_utilisateur) === TRUE) {
        // Récupérer l'ID du nouvel utilisateur
        $id_utilisateur = $conn->insert_id;

        // Insérer dans `client`
        $sql_client = "INSERT INTO client (id_client, numero_compteur, adresse_installation) 
                    VALUES ('$id_utilisateur', '$numero_compteur', '$adresse_installation')";
        
        if ($conn->query($sql_client) === TRUE) {
            // echo "<script>alert('Client ajouté avec succès !'); window.location.href='clients.php';</script>";
            return true;
        } else {
            // echo "Erreur lors de l'ajout du client : " . $conn->error;
            return false;
        }
    } else {
        // echo "Erreur lors de l'ajout de l'utilisateur : " . $conn->error;
        return false;
    }
}

function get_all_clients(){
    $conn = connexion();
    // Récupération des clients
    $sql = "SELECT id_client, numero_compteur, adresse_installation FROM client";
    $result = $conn->query($sql);
    return $result;
}

function get_client_by_id($id_client){
    $conn = connexion();

    $sql = "SELECT u.id_client, u.nom, u.prenom, u.email, c.numero_compteur, c.adresse_installation 
        FROM utilisateur u
        JOIN client c ON u.id_utilisateur = c.id_client
        WHERE u.id_utilisateur = $id_client";

    $result = $conn->query($sql);

    return $result;
}

function update_client($nom, $prenom, $email, $numero_compteur, $adresse_installation, $id_client){
    $conn = connexion();

    // Mise à jour des données dans `utilisateur`
    $sql_update_utilisateur = "UPDATE utilisateur SET 
    nom = '$nom', 
    prenom = '$prenom', 
    email = '$email'
    WHERE id_utilisateur = $id_client";

    // Mise à jour des données dans `client`
    $sql_update_client = "UPDATE client SET 
    numero_compteur = '$numero_compteur', 
    adresse_installation = '$adresse_installation'
    WHERE id_client = $id_client";

    if ($conn->query($sql_update_utilisateur) === TRUE && $conn->query($sql_update_client) === TRUE)
        return true;
    else
        return false;
}

function get_consommations(){
    $conn = connexion();

    // Récupérer les consommations des clients
    $sql = "SELECT id_consommation, client_id, mois, annee, valeur_compteur, photo_compteur, validee
    FROM consommation ";
    $result = $conn->query($sql);
    $consommations = [];

    while ($row = $result->fetch_assoc()) {
        $consommations[] = $row;
    }

    return $consommations;
}

function update_consommation($id, $valeur){
    $conn = connexion();

    $stmt = $conn->prepare("UPDATE consommation SET valeur_compteur = ?, validee = 1 WHERE id_consommation = ?");
    $stmt->bind_param("di", $valeur, $id);

    return $stmt->execute();
    //"<script>alert('Valeur corrigée avec succès !'); window.location.href='consommation.php;</script>";
}

function get_facture_info($id_client){
    $conn = connexion();

    // Requête pour récupérer les informations
    $query = $conn->prepare("
    SELECT u.nom, u.prenom, u.email, 
        c.numero_compteur, c.adresse_installation, 
        co.mois, co.annee, co.valeur_compteur 
    FROM client c
    JOIN utilisateur u ON c.id_client = u.id_utilisateur
    JOIN consommation co ON c.id_client = co.client_id
    WHERE c.id_client = ?
    ");
    $query->bind_param("i", $id_client);
    $query->execute();
    $result = $query->get_result();
    $facture_data = $result->fetch_assoc();

    return $facture_data;
}

function get_all_reclamation(){
    $conn = connexion();

    // Récupération des réclamations
    $sql = "SELECT id_reclamation, client_id, date_soumission, type_reclamation, statut FROM reclamation";
    $result = $conn->query($sql);

    return $result;
}

function update_reclamation($id_reclamation){
    $conn = connexion();

    // Mettre à jour la réclamation comme "résolue"
    $sql_update = "UPDATE reclamation SET statut = 'résolue' WHERE id_reclamation = $id_reclamation";

    if ($conn->query($sql_update) === TRUE) {
        // Récupérer les informations du client et de la réclamation
        $sql_client = "SELECT u.email, u.nom, u.prenom, r.type_reclamation, r.description
        FROM utilisateur u
        JOIN reclamation r ON u.id_utilisateur = r.client_id
        WHERE r.id_reclamation = $id_reclamation";

        $result = $conn->query($sql_client);

        if($result->num_rows > 0)
            return $result->fetch_assoc();
    }    

    return false;
}

function handle_login($email, $password){
    $conn = connexion();

    // Préparer et exécuter la requête SQL
    $stmt = $conn->prepare("SELECT id_utilisateur, nom, prenom, email, mot_de_passe, type FROM utilisateur WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}
?>