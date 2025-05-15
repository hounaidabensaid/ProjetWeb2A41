<?php
require_once __DIR__ . '/../../controller/ReponseController.php';
require_once __DIR__ . '/../../model/Reponse.php';
require_once __DIR__ . '/../../vendor/autoload.php'; // PHPMailer autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Initialize the controller
$controller = new ReponseController();

// Get the response by ID from the URL
if (isset($_GET['id'])) {
    $response = $controller->getReponseById($_GET['id']);
    if (!$response) {
        header("Location: view_reponse.php?error=Réponse non trouvée");
        exit();
    }
} else {
    header("Location: view_reponse.php?error=ID non spécifié");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['contenu'])) {
        try {
            // Create a new Reponse object
            $reponse = new Reponse(
                $response['reclamation_id'],
                $response['admin_id'],
                $_POST['contenu']
            );
            $reponse->setId($response['id']);
            $reponse->setDateCreation($response['date_creation']);

            // Handle file upload
            $piece_jointe = $response['piece_jointe'];
            if (isset($_FILES['piece_jointe']) && $_FILES['piece_jointe']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../Uploads/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $fileName = time() . '_' . basename($_FILES['piece_jointe']['name']);
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['piece_jointe']['tmp_name'], $targetPath)) {
                    $piece_jointe = $fileName;
                }
            }
            $reponse->setPieceJointe($piece_jointe);

            // Update response
            if ($controller->updateReponse($reponse)) {
                // Fetch reclamation details for email
                $reclamation = $controller->getReclamationById($response['reclamation_id']);
                if (!$reclamation) {
                    throw new Exception('Réclamation non trouvée pour l\'envoi de l\'email.');
                }

                // Send email to user
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP host
                    $mail->SMTPAuth = true;
                    $mail->Username = 'sarrabennejma09@gmail.com'; // Replace with your email
                $mail->Password = 'qjglkbeudvwiuewu'; // Replace with your app-specific password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('reclamations@shareride.fr', 'Share a Ride');
                    $mail->addAddress($reclamation['email']);

                    $mail->isHTML(true);
                    $mail->Subject = 'Mise à jour de la réponse à votre réclamation #' . $reclamation['id'];
                    $mail->Body = "
                        <h2>Mise à jour de la réponse à votre réclamation</h2>
                        <p><strong>ID Réclamation :</strong> {$reclamation['id']}</p>
                        <p><strong>Sujet :</strong> " . htmlspecialchars($reclamation['sujet']) . "</p>
                        <p><strong>Réponse mise à jour :</strong> " . htmlspecialchars($_POST['contenu']) . "</p>
                        <p>Merci d'avoir utilisé notre service. Si vous avez d'autres questions, contactez-nous à reclamations@shareride.fr.</p>
                    ";
                    $mail->AltBody = "Réclamation ID: {$reclamation['id']}\nSujet: " . htmlspecialchars($reclamation['sujet']) . "\nRéponse mise à jour: " . htmlspecialchars($_POST['contenu']) . "\nContactez-nous à reclamations@shareride.fr.";

                    $mail->send();
                } catch (Exception $e) {
                    throw new Exception("Erreur lors de l'envoi de l'email: " . $mail->ErrorInfo);
                }

                header("Location: view_reponse.php?success=Réponse mise à jour et email envoyé avec succès.");
                exit();
            } else {
                $error = "Erreur: La mise à jour n'a pas pu être effectuée.";
            }
        } catch (Exception $e) {
            $error = "Erreur: " . $e->getMessage();
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
            color: rgb(0, 0, 0);
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
            border-color: rgb(170, 8, 8);
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
            background-color: rgb(140, 6, 6);
        }

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