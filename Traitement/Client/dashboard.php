<?php
session_start();

require_once '../../BD/Connexion.php';
require_once '../../BD/utilisateurs.php';
require_once '../../BD/client.php';
require_once '../../BD/facture.php';
require_once '../../BD/consommation.php';
require_once '../../BD/reclamation.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: ../../IHM/Client/login.php');
  exit();
}

// Récupération des données via les fonctions CRUD
$user = getUserById($_SESSION['user_id']);
$client = getClientById($_SESSION['user_id']);
$factures = getFacturesByClient($_SESSION['user_id']);
$consommations = getConsommationByClient($_SESSION['user_id']);
$reclamations = getReclamationsByClient($_SESSION['user_id']);

$_SESSION['client']=$client;
// Calculs des totaux pour les factures
$total_impaye = 0;
$factures_impayees = 0;
$factures_recentes = array_slice($factures, 0, 3);

foreach ($factures as $facture) {
  if ($facture['statut'] == 'impayée') {
      $total_impaye += $facture['montant_ttc'];
      $factures_impayees++;
  }
}

// Calcul des statistiques de consommation
$current_month_consumption = 0;
$average_consumption = 0;
$monthly_consumptions = array_fill(0, 6, 0); // Pour les 6 derniers mois
$monthly_labels = [];
$current_month = date('Y-m');

if (!empty($consommations)) {
  // Calcul de la consommation du mois courant et moyenne
  $total_consumption = 0;
  $month_counts = [];
  
  foreach ($consommations as $conso) {
      $conso_month = date('Y-m', strtotime($conso['date_saisie'] ?? $conso['date_consommation']));
      
      if ($conso_month == $current_month) {
          $current_month_consumption += $conso['valeur_compteur'];
      }
      
      $total_consumption += $conso['valeur_compteur'];
      $month_counts[$conso_month] = ($month_counts[$conso_month] ?? 0) + $conso['valeur_compteur'];
  }
  
  $average_consumption = count($month_counts) > 0 ? $total_consumption / count($month_counts) : 0;
  
  // Préparation des données pour le graphique (6 derniers mois)
  $now = new DateTime();
  for ($i = 5; $i >= 0; $i--) {
      $month = clone $now;
      $month->modify("-$i months");
      $month_key = $month->format('Y-m');
      $monthly_labels[] = $month->format('M Y');
      
      if (isset($month_counts[$month_key])) {
          $monthly_consumptions[5-$i] = $month_counts[$month_key];
      }
  }
}

// Statistiques des réclamations
$reclamations_en_attente = 0;
$reclamations_en_cours = 0;
$reclamations_resolues = 0;

foreach ($reclamations as $reclamation) {
  switch ($reclamation['statut']) {
      case 'en attente':
          $reclamations_en_attente++;
          break;
      case 'en cours':
          $reclamations_en_cours++;
          break;
      case 'résolue':
          $reclamations_resolues++;
          break;
  }
}

// Récupération des photos du compteur (supposons que c'est stocké dans la table consommation)
$photos_compteur = array_filter($consommations, function($conso) {
    return !empty($conso['photo_compteur']);
});
usort($photos_compteur, function($a, $b) {
    return strtotime($b['date_saisie'] ?? $b['date_consommation']) <=> strtotime($a['date_saisie'] ?? $a['date_consommation']);
});
$recent_photos = array_slice($photos_compteur, 0, 3);


// Stocker les variables dans la session
$_SESSION['client'] = $client;
$_SESSION['current_month_consumption'] = $current_month_consumption;
$_SESSION['average_consumption'] = $average_consumption;
$_SESSION['factures_impayees'] = $factures_impayees;
$_SESSION['total_impaye'] = $total_impaye;
$_SESSION['reclamations_en_attente'] = $reclamations_en_attente;
$_SESSION['reclamations_en_cours'] = $reclamations_en_cours;
$_SESSION['reclamations_resolues'] = $reclamations_resolues;
$_SESSION['factures'] = $factures;
$_SESSION['recent_photos'] = $recent_photos;
$_SESSION['$monthly_consumptions'] = $monthly_consumptions;

?>