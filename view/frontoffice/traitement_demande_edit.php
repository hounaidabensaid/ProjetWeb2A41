<?php
session_start();

$baseDir = realpath(__DIR__.'/../..');
require_once $baseDir.'/config.php';
require_once $baseDir.'/controller/CovoiturageController.php';

try {
    $pdo = Config::getConnexion();
    $controller = new CovoiturageController($pdo);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Méthode non autorisée");
    }

    if (!isset($_POST['action']) || $_POST['action'] !== 'edit_demande') {
        throw new Exception("Action invalide");
    }

    $requiredFields = ['id_demande', 'nom', 'prenom', 'telephone', 'email', 'places', 'id_annonce'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Le champ $field est requis");
        }
    }

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Format d'email invalide");
    }

    if (!preg_match('/^\d{8,15}$/', $_POST['telephone'])) {
        throw new Exception("Le numéro de téléphone doit contenir entre 8 et 15 chiffres");
    }

    $updated = $controller->updateDemande(
        (int)$_POST['id_demande'],
        htmlspecialchars($_POST['nom']),
        htmlspecialchars($_POST['prenom']),
        preg_replace('/[^0-9+]/', '', $_POST['telephone']),
        filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
        (int)$_POST['places']
    );

    error_log("UpdateDemande result: " . var_export($updated, true));

    if ($updated) {
        $_SESSION['success'] = "Demande modifiée avec succès.";
    } else {
        $_SESSION['erreur'] = "Erreur lors de la modification de la demande.";
    }

    header("Location: /web/view/frontoffice/voir_demandes.php?annonce_id=" . ($_POST['id_annonce'] ?? ''));
    exit;

} catch (Exception $e) {
    error_log("Erreur traitement modification demande: " . $e->getMessage());
    $_SESSION['erreur'] = $e->getMessage();
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/web/view/frontoffice/voir_demandes.php'));
    exit;
}
?>
