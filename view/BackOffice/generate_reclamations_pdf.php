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
    </tr>';
}

$html .= '</tbody></table>';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Output the PDF to the browser (inline display)
$pdf->Output('reclamations_' . date('Y-m-d') . '.pdf', 'I'); // 'I' for inline display in browser
exit();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ShareRide BackOffice</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar h1 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
        }

        .menu-item {
            font-size: 18px;
            padding: 10px;
            margin: 10px 0;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .menu-item.active, .menu-item:hover {
            background-color: #34495e;
        }

        .dashboard {
            margin-left: 270px;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 28px;
            color: #333;
        }

        .search input {
            padding: 10px;
            width: 300px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .search input:focus {
            border-color:rgb(170, 8, 8);
            outline: none;
        }

        .toggle-table-btn {
            background-color:rgb(170, 8, 8);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
        }

        .toggle-table-btn:hover {
            background-color:rgb(170, 8, 8);
        }

        /* Cards Container */
        .reclamation-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 10px;
        }

        .card {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            margin-top: 0;
            font-size: 18px;
            color: #333;
        }

        .card p {
            color: #555;
            margin: 8px 0;
        }

        .btn-reply {
            background-color:rgb(170, 8, 8);
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .btn-reply:hover {
            background-color:rgb(170, 8, 8);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h1>ShareRide</h1>
        <div class="menu-item active"><i class="fas fa-tachometer-alt"></i> Dashboard</div>
        <div class="menu-item"><i class="fas fa-file-alt"></i> Réclamations</div>
        <div class="menu-item"><i class="fas fa-users"></i> Utilisateurs</div>
        <div class="menu-item"><i class="fas fa-cog"></i> Paramètres</div>
    </div>

    <!-- Dashboard -->
    <div class="dashboard">
        <div class="header">
            <h2>Gestion des Réclamations</h2>
            <div class="search">
                <input type="text" id="searchInput" placeholder="Rechercher une réclamation...">
            </div>
        </div>

        <!-- Table Controls -->
        <button class="toggle-table-btn">
            <a href="view_reponse.php" style="color: white; text-decoration: none;">
                <i class="fas fa-info-circle"></i> Liste des Réponses
            </a>
        </button>
       
        <button class="toggle-table-btn">
            <a href="generate_reclamations_pdf.php" target="_blank" style="color: white; text-decoration: none;">
                <i class="fas fa-file-pdf"></i> Exporter en PDF
            </a>
        </button>

        <!-- Reclamations Cards -->
        <div class="reclamation-cards">
            <?php foreach ($reclamations as $reclamation): ?>
                <div class="card">
                    <h3>ID: <?php echo htmlspecialchars($reclamation['id']); ?></h3>
                    <p><strong>Type:</strong> <?php echo htmlspecialchars($reclamation['type']); ?></p>
                    <p><strong>Nom Chauffeur:</strong> <?php echo htmlspecialchars($reclamation['nom_chauffeur']); ?></p>
                    <p><strong>Date Trajet:</strong> <?php echo htmlspecialchars($reclamation['date_trajet']); ?></p>
                    <p><strong>Sujet:</strong> <?php echo htmlspecialchars($reclamation['sujet']); ?></p>
                    <p><strong>Gravité:</strong> <?php echo htmlspecialchars($reclamation['gravite']); ?></p>
                    <p><strong>Statut:</strong> <?php echo htmlspecialchars($reclamation['statut']); ?></p>
                    <a href="add_reponse.php?reclamation_id=<?php echo $reclamation['id']; ?>" class="btn-reply">
                        <i class="fas fa-reply"></i> Répondre
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
