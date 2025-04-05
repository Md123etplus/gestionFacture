<?php
session_start();

require_once '../../BD/Connexion.php';
require_once '../../BD/utilisateurs.php';
require_once '../../BD/client.php';
require_once '../../BD/consommation.php';
require_once '../../BD/facture.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../IHM/Client/login.php');
    exit();
}

// Récupération sécurisée des données client
$user = getUserById($_SESSION['user_id']);
$client = getClientById($_SESSION['user_id']);

// Vérification que le client existe bien
if (!$client) {
    die("Erreur : Profil client introuvable");
}

// Récupération des données
$consommations = getConsommationByClient($client['id_client']);
$factures = getFacturesByClient($client['id_client']); // Récupération des factures

// Initialisation des statistiques
$stats_annuelles = [];
$stats_mensuelles = array_fill(1, 12, 0); // Initialise tous les mois à 0

foreach ($consommations as $conso) {
    $annee = $conso['annee'];
    $mois = $conso['mois'];
    
    // Stats annuelles
    if (!isset($stats_annuelles[$annee])) {
        $stats_annuelles[$annee] = 0;
    }
    $stats_annuelles[$annee] += $conso['valeur_compteur'];
    
    // Stats mensuelles (pour l'année en cours)
    if ($annee == date('Y')) {
        $stats_mensuelles[$mois] += $conso['valeur_compteur'];
    }
}

$last_consumption = !empty($consommations) ? $consommations[0] : null;

// Traitement du formulaire (inchangé)
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation des données
    $valeur_compteur = trim($_POST['valeur_compteur'] ?? '');
    $photo_compteur = $_FILES['photo_compteur'] ?? null;

    if (empty($valeur_compteur)) {
        $errors[] = "La valeur du compteur est obligatoire";
    } elseif (!is_numeric($valeur_compteur)) {
        $errors[] = "La valeur du compteur doit être un nombre";
    } elseif ($last_consumption && $valeur_compteur < $last_consumption['valeur_compteur']) {
        $errors[] = "La nouvelle valeur ne peut pas être inférieure à la précédente (" . $last_consumption['valeur_compteur'] . ")";
    }

    // Traitement de l'image
    $photo_path = null;
    if ($photo_compteur && $photo_compteur['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB

        if (!in_array($photo_compteur['type'], $allowed_types)) {
            $errors[] = "Le type de fichier n'est pas autorisé (seuls JPEG, PNG et GIF sont acceptés)";
        } elseif ($photo_compteur['size'] > $max_size) {
            $errors[] = "La taille du fichier dépasse la limite autorisée (2MB)";
        } else {
            $upload_dir = '../../uploads/compteurs/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $extension = pathinfo($photo_compteur['name'], PATHINFO_EXTENSION);
            $filename = 'compteur_' . $client['id_client'] . '_' . date('YmdHis') . '.' . $extension;
            $photo_path = $upload_dir . $filename;

            if (!move_uploaded_file($photo_compteur['tmp_name'], $photo_path)) {
                $errors[] = "Une erreur est survenue lors du téléchargement de la photo";
                $photo_path = null;
            } else {
                // Pour l'affichage, on garde seulement le chemin relatif
                $photo_path = 'uploads/compteurs/' . $filename;
            }
        }
    }

    // Si pas d'erreurs, enregistrement
    if (empty($errors)) {
        $current_date = date('Y-m-d H:i:s');
        $mois = date('m');
        $annee = date('Y');

        if (createConsommation(
            $client['id_client'],
            $mois,
            $annee,
            $valeur_compteur,
            $photo_path
        )) {
            $success = true;
            // Actualiser les données
            $last_consumption = [
                'valeur_compteur' => $valeur_compteur,
                'date_saisie' => $current_date,
                'photo_compteur' => $photo_path
            ];
            // Recharger les consommations
            $consommations = getConsommationByClient($client['id_client']);
        } else {
            $errors[] = "Une erreur est survenue lors de l'enregistrement";
        }
    }
}
?>