<?php
require_once 'C:/xampp/htdocs/ShareRide/config.php';
require_once __DIR__ . '/../../model/Reponse.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle file or image upload
    $piece_jointe = null;
    $uploadDir = 'C:/xampp/htdocs/ShareRide/uploads/';
    
    if (isset($_FILES['piece_jointe']) && $_FILES['piece_jointe']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['piece_jointe']['tmp_name'];
        $fileName = $_FILES['piece_jointe']['name'];
        $uploadFilePath = $uploadDir . basename($fileName);

        if (!is_writable($uploadDir)) {
            die('The upload directory is not writable. Please check permissions.');
        }

        if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
            $piece_jointe = 'Uploads/' . basename($fileName);
        } else {
            die('File upload failed. PHP error: ' . $_FILES['piece_jointe']['error']);
        }
    } elseif (isset($_POST['captured_image']) && !empty($_POST['captured_image'])) {
        $imageData = $_POST['captured_image'];
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = base64_decode($imageData);

        $fileName = uniqid('camera_') . '.png';
        $uploadFilePath = $uploadDir . $fileName;

        if (file_put_contents($uploadFilePath, $imageData)) {
            $piece_jointe = 'Uploads/' . $fileName;
        } else {
            die('Failed to save the image.');
        }
    } else {
        die('No file or image provided.');
    }

    // Ensure date_creation is set
    $date_creation = $_POST['date_creation'] ?? date('Y-m-d H:i:s');

    // Get database connection
    $db = config::getConnexion();

    // Always set admin_id to 1
    $admin_id = 1;

    try {
        // Insert the response
        $sql = "INSERT INTO reponse (reclamation_id, admin_id, contenu, date_creation, piece_jointe) 
                VALUES (:reclamation_id, :admin_id, :contenu, :date_creation, :piece_jointe)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':reclamation_id', $_POST['reclamation_id']);
        $stmt->bindParam(':admin_id', $admin_id);
        $stmt->bindParam(':contenu', $_POST['contenu']);
        $stmt->bindParam(':date_creation', $date_creation);
        $stmt->bindParam(':piece_jointe', $piece_jointe);
        $stmt->execute();

        // Ajout du code d'envoi d'email
        require_once __DIR__ . '/../../vendor/autoload.php';
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        $reclamation_id = $_POST['reclamation_id'];
        $sql = "SELECT email FROM reclamation WHERE id = :reclamation_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':reclamation_id', $reclamation_id);
        $stmt->execute();
        $reclamation = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($reclamation && !empty($reclamation['email'])) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'sarrabennejma09@gmail.com'; // Remplacez par votre email
                $mail->Password = 'qjglkbeudvwiuewu'; // Remplacez par votre mot de passe d'application
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('sarrabennejma09@gmail.com', 'Support ShareRide'); // Remplacez par votre email
                $mail->addAddress($reclamation['email']);

                $mail->isHTML(true);
                $mail->Subject = 'Réponse à votre réclamation - ShareRide';
                $mail->Body = '
                    <h2>Bonjour,</h2>
                    <p>Nous avons répondu à votre réclamation (ID : ' . htmlspecialchars($reclamation_id) . ').</p>
                    <p><strong>Réponse :</strong> ' . htmlspecialchars($_POST['contenu']) . '</p>
                    <p>Veuillez consulter votre espace utilisateur pour plus de détails.</p>
                    <p>Cordialement,<br>L\'équipe ShareRide</p>
                ';
                $mail->AltBody = 'Bonjour, nous avons répondu à votre réclamation (ID : ' . $reclamation_id . '). Réponse : ' . $_POST['contenu'] . '. Consultez votre espace utilisateur pour plus de détails. Cordialement, L\'équipe ShareRide';

                $mail->send();
            } catch (Exception $e) {
                error_log("Échec de l'envoi de l'email : {$mail->ErrorInfo}");
            }
        }

        // Redirect after processing
        header("Location: view_reponse.php");
        exit();
    } catch (Exception $e) {
        die('Erreur: ' . $e->getMessage());
    }
}
?>