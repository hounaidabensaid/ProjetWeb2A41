<?php
// Assuming you are fetching the responses from the database
require_once __DIR__ . '/../../controller/ReponseController.php';

// Define the base URL for the Uploads folder
define('BASE_URL', 'http://localhost/ShareRide1/Uploads/');

$controller = new ReponseController();
$reponses = $controller->getReponses();  // Correct method name here
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ShareRide BackOffice</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h1>Share a ride</h1>
        <div class="menu-item active"><i class="fas fa-tachometer-alt"></i> Dashboard</div>
        <div class="menu-item"><i class="fas fa-file-alt"></i> Réclamations</div>
        <div class="menu-item"><i class="fas fa-users"></i> Utilisateurs</div>
        <div class="menu-item"><i class="fas fa-cog"></i> Paramètres</div>
    </div>

    <!-- Dashboard -->
    <div class="dashboard">
        <div class="header">
            <h2>Gestion des Réponses</h2>
        </div>

        <!-- Reponses Table -->
        <div class="collapsible-table" id="responseTable">
            <button class="toggle-table-btn">
               <a href="dashboard.php"><i class="fas fa-info-circle"></i> retourner liste reclamations</a> 
            </button>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Réclamation ID</th>
                        <th>Contenu</th>
                        <th>Date Création</th>
                        <th>Piece Jointe</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reponses as $response): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($response['id']); ?></td>
                            <td><?php echo htmlspecialchars($response['reclamation_id']); ?></td>
                            <td><?php echo htmlspecialchars($response['contenu']); ?></td>
                            <td><?php echo htmlspecialchars($response['date_creation']); ?></td>
                            <td>
                                <?php
                                // Ensure that the piece_jointe is not empty
                                if (!empty($response['piece_jointe'])) {
                                    // Construct the URL for the uploaded file
                                    $fileUrl = BASE_URL . rawurlencode($response['piece_jointe']);
                                    $filePath = __DIR__ . '/../../Uploads/' . $response['piece_jointe'];

                                    // Check if the file exists on the server
                                    if (file_exists($filePath)) {
                                        // Get the file extension
                                        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

                                        // If the file is an image, display it
                                        if (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                                            echo '<img src="' . htmlspecialchars($fileUrl) . '" alt="Piece Jointe" style="width: 100px; height: auto;">';
                                        } else {
                                            // If it's not an image, display a download link
                                            echo '<a href="' . htmlspecialchars($fileUrl) . '" target="_blank">Télécharger le fichier</a>';
                                        }
                                    } else {
                                        echo 'Fichier introuvable';
                                    }
                                } else {
                                    // If no piece jointe is available, display a message
                                    echo 'Aucune pièce jointe';
                                }
                                ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-info" onclick="window.location.href='edit_reponse.php?id=<?php echo $response['id']; ?>'">
                                        <i class="fas fa-edit"></i> Modifier
                                    </button>
                                    <!-- Use a POST form to delete -->
                                    <form action="delete_reponse.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $response['id']; ?>">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>