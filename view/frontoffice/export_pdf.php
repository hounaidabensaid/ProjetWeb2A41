<?php
require_once '../../config.php';
require_once '../../controller/ReservationController.php';
require_once '../../fpdf/fpdf.php';

$pdo = config::getConnexion();
$controller = new ReservationController($pdo);

$id_user = 1;
$reservations = $controller->getAllReservations();
$mesReclamations = array_filter($reservations, fn($r) => $r['id_user'] == $id_user);

// Initialisation du PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

// Titre
$pdf->SetTextColor(200, 0, 0);
$pdf->Cell(0, 10, utf8_decode('Mes Réservations'), 0, 1, 'C');
$pdf->Ln(5);

// En-tête du tableau
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(220, 53, 69); // rouge doux
$pdf->SetTextColor(255); // texte blanc
$pdf->Cell(10, 10, '#', 1, 0, 'C', true);
$pdf->Cell(55, 10, utf8_decode('Véhicule'), 1, 0, 'C', true);
$pdf->Cell(30, 10, utf8_decode('Début'), 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Fin', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Statut', 1, 1, 'C', true);

// Contenu du tableau
$pdf->SetFont('Arial', '', 11);
$pdf->SetTextColor(0);
foreach ($mesReclamations as $r) {
    $pdf->Cell(10, 10, $r['id_reservation'], 1, 0, 'C');
    $pdf->Cell(55, 10, utf8_decode($r['matricule'] . ' - ' . $r['marque']), 1);
    $pdf->Cell(30, 10, $r['date_début'], 1, 0, 'C');
    $pdf->Cell(30, 10, $r['date_fin'], 1, 0, 'C');

    // Statut avec texte coloré simplifié
    $statut = strtolower($r['statut']);
    $label = ucfirst($statut);
    if ($statut === 'confirmée') {
        $label = '✔ ' . $label;
    } elseif ($statut === 'annulée') {
        $label = '✖ ' . $label;
    } else {
        $label = '⏳ ' . $label;
    }

    $pdf->Cell(50, 10, utf8_decode($label), 1, 1, 'C');
}

// Footer
$pdf->Ln(5);
$pdf->SetFont('Arial', 'I', 9);
$pdf->SetTextColor(100);
$pdf->Cell(0, 10, utf8_decode('Généré le : ') . date('d/m/Y H:i'), 0, 0, 'R');

$pdf->Output('I', 'mes_reservations.pdf');
