<?php
require_once 'C:/xampp/htdocs/ShareRide/config.php';
require_once __DIR__ . '/../../model/Reponse.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle file or image upload
    if (isset($_FILES['piece_jointe']) && $_FILES['piece_jointe']['error'] == UPLOAD_ERR_OK) {
        // Handle file upload
        $fileTmpPath = $_FILES['piece_jointe']['tmp_name'];
        $fileName = $_FILES['piece_jointe']['name'];
        $fileType = $_FILES['piece_jointe']['type'];
        $fileSize = $_FILES['piece_jointe']['size'];

        // Specify the directory where the file will be saved
        $uploadDir = 'C:/xampp/htdocs/ShareRide/uploads/';  // Absolute path
        $uploadFilePath = $uploadDir . basename($fileName);

        // Check if the directory is writable
        if (!is_writable($uploadDir)) {
            die('The upload directory is not writable. Please check permissions.');
        }

        // Check if the file is successfully uploaded
        if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
            // File uploaded successfully
            $piece_jointe = 'uploads/' . basename($fileName);  // Store relative path in DB
        } else {
            die('File upload failed. PHP error: ' . $_FILES['piece_jointe']['error']);
        }
    } elseif (isset($_POST['captured_image']) && !empty($_POST['captured_image'])) {
        // Handle the base64-encoded image data (camera image)
        $imageData = $_POST['captured_image'];
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = base64_decode($imageData);

        // Create a unique filename for the image
        $fileName = uniqid('camera_') . '.png';
        $uploadFilePath = $uploadDir . $fileName;

        // Save the image to the server
        if (file_put_contents($uploadFilePath, $imageData)) {
            $piece_jointe = 'uploads/' . $fileName;  // Store relative path in DB
        } else {
            die('Failed to save the image.');
        }
    } else {
        die('No file or image provided.');
    }

    // Ensure date_creation is set (use current date if not provided)
    $date_creation = $_POST['date_creation'] ?? date('Y-m-d H:i:s');  // Default to current date and time if not provided

    // Get database connection
    $db = config::getConnexion();

    // Always set admin_id to 1
    $admin_id = 1;  // Automatically set admin_id to 1 (since Sara is the only admin)

    try {
        $sql = "INSERT INTO reponse (reclamation_id, admin_id, contenu, date_creation, piece_jointe) 
                VALUES (:reclamation_id, :admin_id, :contenu, :date_creation, :piece_jointe)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':reclamation_id', $_POST['reclamation_id']);
        $stmt->bindParam(':admin_id', $admin_id);  // Always set to 1
        $stmt->bindParam(':contenu', $_POST['contenu']);
        $stmt->bindParam(':date_creation', $date_creation);  // Bind the date_creation
        $stmt->bindParam(':piece_jointe', $piece_jointe);
        $stmt->execute();
    } catch (Exception $e) {
        die('Erreur: ' . $e->getMessage());
    }

    // Redirect or respond after processing
    header("Location: view_reponse.php");
    exit();
}
?>
