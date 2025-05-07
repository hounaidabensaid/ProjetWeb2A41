<?php
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/../../controller/messageController.php");
session_start();

// Vérification de l'authentification
if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit;
}

// Vérification de la méthode HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Méthode non autorisée";
    header("Location: chatbox.php");
    exit;
}

// Validation des données
$messageId = filter_input(INPUT_POST, 'messageId', FILTER_VALIDATE_INT);
$reaction = htmlspecialchars($_POST['reaction'] ?? '');
$receiverId = filter_input(INPUT_POST, 'receiverId', FILTER_VALIDATE_INT);

if (!$messageId || empty($reaction)) {
    $_SESSION['error'] = "Paramètres invalides";
    header("Location: chatbox.php");
    exit;
}

// Traitement de la réaction
try {
    $messageController = new MessageController();
    $updatedMessage = $messageController->updateMessageReaction($messageId, $reaction);
    
    if ($updatedMessage) {
        // Récupération de l'ID du destinataire depuis le message
        $receiverId = $updatedMessage['idreciever']; // À adapter selon votre structure de données
        $_SESSION['success'] = "Réaction mise à jour";
        header("Location: chatbox.php?user_id=$receiverId");
    } else {
        $_SESSION['error'] = "Échec de la mise à jour";
        header("Location: chatbox.php?user_id=$receiverId");
    }
    exit;
} catch (Exception $e) {
    error_log("Erreur de réaction: " . $e->getMessage());
    $_SESSION['error'] = "Erreur serveur";
    header("Location: chatbox.php?user_id=$receiverId");
    exit;
}