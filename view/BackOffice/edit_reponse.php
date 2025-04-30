<?php
require_once __DIR__ . '/../../controller/ReponseController.php';
require_once __DIR__ . '/../../model/Reponse.php'; // Include the Reponse class

// Initialize the controller
$controller = new ReponseController();

// Get the response by ID from the URL
if (isset($_GET['id'])) {
    $response = $controller->getReponseById($_GET['id']); // Get the response by ID
} else {
    // Redirect to the view page if the ID is not set
    header("Location: view_reponses.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is submitted and the content is not empty
    if (!empty($_POST['contenu'])) {
        // Create a new Reponse object with required parameters
        $reponse = new Reponse(
            $response['reclamation_id'], // From database (unchanged)
            $response['admin_id'],       // From database (unchanged)
            $_POST['contenu']           // New content from form
        );
        
        // Set the ID properly using the setter method
        $reponse->setId($response['id']);
        
        // Handle file upload - keep existing if no new file uploaded
        $piece_jointe = $response['piece_jointe']; // Default to existing file
        
        if (isset($_FILES['piece_jointe']) && $_FILES['piece_jointe']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../uploads/';
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileName = basename($_FILES['piece_jointe']['name']);
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['piece_jointe']['tmp_name'], $targetPath)) {
                $piece_jointe = $fileName;
            }
        }
        
        $reponse->setPieceJointe($piece_jointe);
        $reponse->setDateCreation($response['date_creation']); // Keep original date

        // Call the controller's update method
        if ($controller->updateReponse($reponse)) {
            // Redirect after successful update
            header("Location: view_reponse.php");
            exit();
        } else {
            $error = "Erreur: La mise à jour n'a pas pu être effectuée.";
        }
    } else {
        $error = "Erreur: Le contenu ne peut pas être vide.";
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Réponse</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap">
    <style>
        /* Global Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .form-container {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.47);
        }

        h2 {
            text-align: center;
            color:rgb(0, 0, 0);
            margin-bottom: 20px;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 16px;
        }

        textarea,
        input[type="file"] {
            font-size: 16px;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 6px;
            transition: border-color 0.3s ease;
        }

        textarea {
            resize: vertical;
            min-height: 150px;
        }

        input[type="file"]:hover,
        textarea:focus {
            border-color:rgb(170, 8, 8);
        }

        button {
            background-color: rgb(170, 8, 8);
            color: white;
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: rgb(170, 8, 8);
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .form-container {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Modifier la Réponse</h2>
    <?php if (!empty($error)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($response['id']); ?>">
        <input type="hidden" name="reclamation_id" value="<?php echo htmlspecialchars($response['reclamation_id']); ?>">
        <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($response['admin_id']); ?>">

        <label for="contenu">Contenu</label>
        <textarea name="contenu" id="contenu" required><?php echo htmlspecialchars($response['contenu']); ?></textarea>

        <label for="piece_jointe">Pièce Jointe</label>
        <input type="file" name="piece_jointe" id="piece_jointe">

        <button type="submit">Mettre à jour</button>
    </form>
</div>

</body>
</html>
