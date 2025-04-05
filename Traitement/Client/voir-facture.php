<?php
require_once '../../BD/Connexion.php';
require_once '../../BD/facture.php';
require_once '../../BD/client.php';
require_once '../../BD/utilisateurs.php';
require_once '../../BD/consommation.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../IHM/Client/login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: ../../IHM/Client/mes-factures.php');
    exit();
}

$facture_id = $_GET['id'];
$facture = getFactureById($facture_id); // À implémenter dans facture.php
$user = getUserById($_SESSION['user_id']);
$client = getClientById($_SESSION['user_id']);
// Vérifier que la facture appartient bien au client
if ($facture['client_id'] != $_SESSION['user_id']) {
    header('Location: ../../IHM/Client/mes-factures.php');
    exit();
}

// Récupérer la consommation associée
$consommation = getConsommationForFacture($facture_id); // À implémenter
?>