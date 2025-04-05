<?php
session_start();

require_once '../../BD/Connexion.php';
require_once '../../BD/utilisateurs.php';
require_once '../../BD/client.php';
require_once '../../BD/reclamation.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../IHM/Client/login.php');
    exit();
}

$user = getUserById($_SESSION['user_id']);
$client = getClientById($_SESSION['user_id']);
$reclamations = getReclamationsByClient($_SESSION['user_id']);

// Traitement du formulaire de nouvelle réclamation
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reclamation'])) {
    $type = trim($_POST['type'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    // Validation
    if (empty($type)) {
        $errors[] = "Le type de réclamation est obligatoire";
    }
    if (empty($description)) {
        $errors[] = "La description est obligatoire";
    } elseif (strlen($description) < 20) {
        $errors[] = "La description doit contenir au moins 20 caractères";
    }
    
    // Si pas d'erreurs, création
    if (empty($errors)) {
        if (createReclamation($_SESSION['user_id'], $type, $description)) {
            $success = true;
            // Rafraîchir les données
            $reclamations = getReclamationsByClient($_SESSION['user_id']);
        } else {
            $errors[] = "Une erreur est survenue lors de l'enregistrement";
        }
    }
}

// Traitement de la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_reclamation'])) {
    $id = $_POST['id_reclamation'] ?? 0;
    if (deleteReclamation($id)) {
        $success = true;
        $reclamations = getReclamationsByClient($_SESSION['user_id']);
    }
}
?>
