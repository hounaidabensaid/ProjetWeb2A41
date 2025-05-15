<?php

// Inclure la bibliothèque FPDF
require_once __DIR__ . '/../libs/fpdf/fpdf.php'; // Assure-toi que ce chemin est correct
require_once __DIR__ . '/../config.php'; // Inclut la classe config pour la connexion PDO

if (isset($_POST['export_pdf'])) {
    // Connexion à la base de données via PDO
    $pdo = config::getConnexion();

    // Requête SQL pour récupérer les réservations approuvées
    $sql = "SELECT r.id_reservation AS reservation_id, u.prenom AS participant_name, u.email AS participant_email, r.date_reservation
            FROM reservationevent r 
            JOIN user u ON r.id_participant = u.id
            WHERE r.status = 'approved'";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $reservations = $stmt->fetchAll();

    if (count($reservations) > 0) {
        // Créer le PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);

        // Titre
        $pdf->Cell(0, 10, 'Liste des Reservations Approuvées', 0, 1, 'C');
        $pdf->Ln(5);

        // En-têtes de colonne
        $pdf->Cell(40, 10, 'ID Reservation', 1);
        $pdf->Cell(50, 10, 'Nom Participant', 1);
        $pdf->Cell(60, 10, 'Email Participant', 1);
        $pdf->Cell(40, 10, 'Date Reservation', 1);
        $pdf->Ln();

        // Contenu du tableau
        foreach ($reservations as $row) {
            $pdf->Cell(40, 10, $row['reservation_id'], 1);
            $pdf->Cell(50, 10, $row['participant_name'], 1);
            $pdf->Cell(60, 10, $row['participant_email'], 1);
            $pdf->Cell(40, 10, $row['date_reservation'], 1);
            $pdf->Ln();
        }

        // Télécharger le PDF
        $pdf->Output('D', 'reservations_approuvees.pdf');
    } else {
        echo "Aucune réservation approuvée à afficher.";
    }
}
?>
