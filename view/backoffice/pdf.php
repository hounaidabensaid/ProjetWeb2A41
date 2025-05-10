<?php
ob_start();

require_once '../../config.php';
if (!file_exists('../../controller/fpdf186/fpdf.php')) {
    ob_end_clean(); // Vide le tampon avant d’envoyer l’erreur
    die('Le fichier fpdf.php est introuvable. Vérifiez le chemin.');
}
require_once '../../controller/fpdf186/fpdf.php';

try {
    $pdo = config::getConnexion();
    $stmt = $pdo->query("SELECT nom, email, tel, role, status FROM user");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);

    // Titre
    $pdf->Cell(0, 10, 'Liste des Utilisateurs', 0, 1, 'C');
    $pdf->Ln(10);

    // En-tête du tableau
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 10, 'Nom', 1);
    $pdf->Cell(60, 10, 'Email', 1);
    $pdf->Cell(30, 10, 'Tel', 1);
    $pdf->Cell(30, 10, 'Role', 1);
    $pdf->Cell(30, 10, 'Statut', 1);
    $pdf->Ln();

    // Contenu du tableau
    $pdf->SetFont('Arial', '', 10);
    foreach ($users as $user) {
        $pdf->Cell(40, 10, $user['nom'], 1);
        $pdf->Cell(60, 10, $user['email'], 1);
        $pdf->Cell(30, 10, $user['tel'], 1);
        $pdf->Cell(30, 10, $user['role'], 1);
        $pdf->Cell(30, 10, $user['status'], 1);
        $pdf->Ln();
    }

    ob_end_clean();  // Vide le tampon avant de générer le PDF proprement
    $pdf->Output('D', 'Liste_Utilisateurs.pdf');
    exit;
} catch (Exception $e) {
    ob_end_clean();
    die('Erreur : ' . $e->getMessage());
}
?>
