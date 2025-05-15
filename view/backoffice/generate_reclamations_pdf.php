<?php
require_once __DIR__ . '/../../vendor/autoload.php'; // Path to autoload.php
require_once __DIR__ . '/../../controller/ReclamationController.php';

use TCPDF as TCPDF;

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('ShareRide');
$pdf->SetTitle('Liste des Réclamations');
$pdf->SetSubject('Export PDF des Réclamations');

// Set margins
$pdf->SetMargins(10, 10, 10);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Liste des Réclamations - ShareRide', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);

// Get reclamations data
$controller = new ReclamationController();
$reclamations = $controller->getReclamations();

// Create table header
$html = '<table border="1" cellpadding="4">
    <thead>
        <tr style="background-color:#f2f2f2;">
            <th><b>ID</b></th>
            <th><b>Type</b></th>
            <th><b>Nom Chauffeur</b></th>
            <th><b>Date Trajet</b></th>
            <th><b>Sujet</b></th>
            <th><b>Gravité</b></th>
            <th><b>Statut</b></th>
            <th><b>Email</b></th>
        </tr>
    </thead>
    <tbody>';

// Add table rows
foreach ($reclamations as $reclamation) {
    $html .= '<tr>
        <td>'.htmlspecialchars($reclamation['id']).'</td>
        <td>'.htmlspecialchars($reclamation['type']).'</td>
        <td>'.htmlspecialchars($reclamation['nom_chauffeur']).'</td>
        <td>'.htmlspecialchars($reclamation['date_trajet']).'</td>
        <td>'.htmlspecialchars($reclamation['sujet']).'</td>
        <td>'.htmlspecialchars($reclamation['gravite']).'</td>
        <td>'.htmlspecialchars($reclamation['statut']).'</td>
        <td>'.htmlspecialchars($reclamation['email']).'</td>
    </tr>';
}

$html .= '</tbody></table>';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Output the PDF to the browser (inline display)
$pdf->Output('reclamations_' . date('Y-m-d') . '.pdf', 'I'); // 'I' for inline display in browser
exit();
?>