<?php
require_once('fpdf/fpdf.php');
require('FactureElectricite.php');

// Calcul des tranches tarifaires
$consommation = $facture_data['valeur_compteur'];
$tranche1 = min($consommation, 100);
$tranche2 = ($consommation > 100) ? min($consommation - 100, 50) : 0;
$tranche3 = ($consommation > 150) ? $consommation - 150 : 0;

$montant_tranche1 = $tranche1 * 0.82;
$montant_tranche2 = $tranche2 * 0.92;
$montant_tranche3 = $tranche3 * 1.10;

$montant_ht = $montant_tranche1 + $montant_tranche2 + $montant_tranche3;
$tva = $montant_ht * 0.18;
$montant_ttc = $montant_ht + $tva;


// Convertir le mois en texte (ex: 3 → Mars)
$mois_noms = [
    1 => "Janvier", 2 => "Février", 3 => "Mars", 4 => "Avril", 5 => "Mai", 6 => "Juin",
    7 => "Juillet", 8 => "Août", 9 => "Septembre", 10 => "Octobre", 11 => "Novembre", 12 => "Décembre"
];

$mois_texte = $mois_noms[intval($facture_data['mois'])];
$periode = "$mois_texte " . $facture_data['annee'];


// Création du PDF
$pdf = new FactureElectricite();
$pdf->AddPage();

$pdf->InfoClient([
    'nom' => $facture_data['nom'] . " " . $facture_data['prenom'],
    'adresse' => $facture_data['adresse_installation'],
    'compteur' => $facture_data['numero_compteur']
]);

$pdf->DetailsFacture([
    'periode' => $periode,
    'tranche1' => $tranche1,
    'tranche2' => $tranche2,
    'tranche3' => $tranche3,
    'montant_t1' => $montant_tranche1,
    'montant_t2' => $montant_tranche2,
    'montant_t3' => $montant_tranche3,
    'montant_ht' => $montant_ht,
    'tva' => $tva,
    'montant_ttc' => $montant_ttc,
    
]);

// Génération du PDF
$filename = "../IHM/Admin/factures/facture_client_{$id_client}.pdf";
$pdf->Output('F', $filename);
$pdf->Output('I', "Facture_Client_{$id_client}.pdf");
?>