<?php
require('fpdf/fpdf.php');

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "electricity");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID client manquant ou invalide.");
}

$id_client = intval($_GET['id']);

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

if (!$facture_data) {
    die("Données introuvables pour ce client.");
}

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
class FactureElectricite extends FPDF {
    function Header() {
        $this->Image('images/electricite.png', 10, 6, 30);
        $this->SetFont('Arial', 'B', 18);
        $this->SetTextColor(255, 102, 0);
        $this->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'VoltForce'), 0, 1, 'R');
        $this->SetFont('Arial', 'I', 12);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Votre fournisseur d\'énergie premium'), 0, 1, 'R');
        $this->Ln(15);
        $this->SetFont('Arial', 'B', 20);
        $this->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'FACTURE D\'ÉLECTRICITÉ'), 0, 1, 'C');
        $this->Ln(20);
        
        $this->SetDrawColor(0, 0, 255);
        $this->SetLineWidth(0.5);
        $this->Line(10, 50, 200, 50);
        $this->Ln(10);
    }

    function InfoClient($info) {
        $this->SetFont('Arial', '', 12);
        $this->Cell(40, 8, 'Nom:', 0, 0);
        $this->Cell(0, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $info['nom']), 0, 1);
        $this->Cell(40, 8, 'Adresse:', 0, 0);
        $this->MultiCell(0, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $info['adresse']));
        $this->Cell(40, 8, 'Compteur:', 0, 0);
        $this->Cell(0, 8, $info['compteur'], 0, 1);
       
        $this->Cell(40, 8, 'Date facture:', 0, 0);
        $this->Cell(0, 8, date('d/m/Y'), 0, 1);
        $this->Ln(10);
    }

    function DetailsFacture($data) {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'DÉTAILS DE CONSOMMATION'), 0, 1);
        
        // Tableau des tranches
        $this->SetFont('Arial', '', 12);
        $this->Cell(60, 6,  iconv('UTF-8', 'ISO-8859-1//TRANSLIT','Période:'), 1);
        $this->Cell(140, 6, $data['periode'], 1, 1);
        $this->Cell(60, 8, 'Tranche 0-100 kWh:', 1);
        $this->Cell(40, 8, $data['tranche1'].' kWh', 1, 0, 'R');
        $this->Cell(40, 8, '0.82 DH/kWh', 1, 0, 'R');
        $this->Cell(0, 8, number_format($data['montant_t1'], 2, ',', ' ').' DH', 1, 1, 'R');
        
        $this->Cell(60, 8, 'Tranche 101-150 kWh:', 1);
        $this->Cell(40, 8, $data['tranche2'].' kWh', 1, 0, 'R');
        $this->Cell(40, 8, '0.92 DH/kWh', 1, 0, 'R');
        $this->Cell(0, 8, number_format($data['montant_t2'], 2, ',', ' ').' DH', 1, 1, 'R');
        
        $this->Cell(60, 8, 'Tranche 151+ kWh:', 1);
        $this->Cell(40, 8, $data['tranche3'].' kWh', 1, 0, 'R');
        $this->Cell(40, 8, '1.10 DH/kWh', 1, 0, 'R');
        $this->Cell(0, 8, number_format($data['montant_t3'], 2, ',', ' ').' DH', 1, 1, 'R');
        
        // Total HT
        $this->Cell(140, 8, 'Total HT:', 1, 0, 'R');
        $this->Cell(0, 8, number_format($data['montant_ht'], 2, ',', ' ').' DH', 1, 1, 'R');
        
        // TVA
        $this->Cell(140, 8, 'TVA (18%):', 1, 0, 'R');
        $this->Cell(0, 8, number_format($data['tva'], 2, ',', ' ').' DH', 1, 1, 'R');
        
        // Total TTC
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(255, 102, 0);
        $this->Cell(140, 10, 'TOTAL TTC:', 1, 0, 'R');
        $this->Cell(0, 10, number_format($data['montant_ttc'], 2, ',', ' ').' DH', 1, 1, 'R');
        $this->SetTextColor(0, 0, 0);
        $this->Ln(15);
    }
}

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
$filename = "factures/facture_client_{$id_client}.pdf";
$pdf->Output('F', $filename);
$pdf->Output('I', "Facture_Client_{$id_client}.pdf");
?>