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

    if (!isset($_POST['action']) || $_POST['action'] !== 'delete_demande') {
        throw new Exception("Action invalide");
    }

    if (empty($_POST['id_demande'])) {
        throw new Exception("ID de la demande manquant");
    }

    $deleted = $controller->deleteDemande((int)$_POST['id_demande']);

    if ($deleted) {
        $_SESSION['success'] = "Demande supprimée avec succès.";
    } else {
        $_SESSION['erreur'] = "Erreur lors de la suppression de la demande.";
    }

    header("Location: /web/view/frontoffice/voir_demandes.php?annonce_id=" . ($_POST['id_annonce'] ?? ''));
    exit;

} catch (Exception $e) {
    error_log("Erreur traitement suppression demande: " . $e->getMessage());
    $_SESSION['erreur'] = $e->getMessage();
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/web/view/frontoffice/voir_demandes.php'));
    exit;
}
?>
