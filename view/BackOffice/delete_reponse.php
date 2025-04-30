<?php
require_once __DIR__ . '/../../controller/ReponseController.php';

if (isset($_POST['id'])) {
    $controller = new ReponseController();
    $id = $_POST['id'];

    // Call the controller method to delete the response
    $controller->deleteReponse($id);
    
    // Redirect after deletion
    header('Location: view_reponse.php');
    exit();
} else {
    echo "Invalid request.";
}
?>
