<?php
require_once('fpdf/fpdf.php');

class FactureElectricite extends FPDF {
    function Header() {
        $this->Image('../IHM/Admin/images/electricite.png', 10, 6, 30);
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