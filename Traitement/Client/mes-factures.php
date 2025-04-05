<?php
require_once '../../BD/Connexion.php';
require_once '../../BD/facture.php';
require_once '../../BD/client.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../IHM/Client/index.php');
    exit();
}

$client_id = $_SESSION['user_id'];
$client = getClientById($client_id);
$factures = getFacturesByClient($client_id);

// Gestion des filtres
$annee = $_GET['annee'] ?? 'all';
$statut = $_GET['statut'] ?? 'all';

if ($annee !== 'all') {
    $factures = array_filter($factures, function($f) use ($annee) {
        return date('Y', strtotime($f['date_emission'])) == $annee;
    });
}

if ($statut !== 'all') {
    $factures = array_filter($factures, function($f) use ($statut) {
        return $f['statut'] == $statut;
    });
}
?>