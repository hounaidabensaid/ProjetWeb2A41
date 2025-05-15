<?php
require_once '../controller/chatboxcontroller.php'; // chemin correct si ton controller est dans un dossier "controller"

if (isset($_POST['nom']) && isset($_POST['created_by'])) {
    $chatboxController = new chatboxcontroller();
    $chatboxController->addChatbox($_POST['nom'], $_POST['created_by']);
    echo "✅ Chatbox ajoutée avec succès !";
} else {
    echo "❗ Veuillez remplir tous les champs.";
}
?>
