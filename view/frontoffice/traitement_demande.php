<?php
session_start();

// 1. Définir le chemin vers le dossier de base (web/)
$baseDir = realpath(__DIR__.'/../..');

// 2. Charger les dépendances (config, modèle, contrôleur)
require_once $baseDir.'/config.php';
require_once $baseDir.'/model/CovoiturageModel.php';
require_once $baseDir.'/controller/CovoiturageController.php';

// 3. Activer le log pour le debug (désactiver en production)
error_log("Chargement de config depuis : ".$baseDir.'/config.php');
error_log("Chargement du modèle depuis : ".$baseDir.'/model/CovoiturageModel.php');

try {
    // 4. Initialiser la connexion PDO et le contrôleur
    $pdo = Config::getConnexion();
    $controller = new CovoiturageController($pdo);

    // 5. Vérifier que la méthode est bien POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Méthode non autorisée");
    }

    // 6. Vérifier l'action
    if (!isset($_POST['action']) || $_POST['action'] !== 'demande_covoiturage') {
        throw new Exception("Action invalide");
    }

    // 7. Vérifier les champs obligatoires
    $requiredFields = ['id_annonce', 'nom', 'prenom', 'telephone', 'email', 'places'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Le champ $field est requis");
        }
    }

    // Validate email format
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Format d'email invalide");
    }

    // Validate telephone: exactly 8 digits
    if (!preg_match('/^\d{8}$/', $_POST['telephone'])) {
        throw new Exception("Le numéro de téléphone doit contenir exactement 8 chiffres");
    }

    // 8. Traitement des données
    try {
        error_log("Tentative d'ajout de demande pour annonce ID: " . $_POST['id_annonce']);
        $demandeId = $controller->addDemande(
            (int)$_POST['id_annonce'],
            htmlspecialchars($_POST['nom']),
            htmlspecialchars($_POST['prenom']),
            preg_replace('/[^0-9+]/', '', $_POST['telephone']),
            filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
            (int)$_POST['places']
        );
        error_log("Demande ajoutée avec succès, ID: " . $demandeId);
    } catch (Exception $ex) {
        error_log("Erreur lors de l'ajout de la demande: " . $ex->getMessage());
        $_SESSION['erreur'] = "Erreur lors de l'ajout de la demande: " . $ex->getMessage();
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../view/frontoffice/index.php'));
        exit;
    }

    // 9. Succès
    $_SESSION['success'] = "Votre demande a été enregistrée avec succès (ID: $demandeId)";
    header("Location: /web/view/frontoffice/voir_demandes.php?annonce_id=" . $_POST['id_annonce']);
    exit;

} catch (PDOException $e) {
    error_log("Erreur base de données : " . $e->getMessage());
    $_SESSION['erreur'] = "Erreur technique. Veuillez réessayer plus tard.";
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../view/frontoffice/index.php'));
    exit;
} catch (Exception $e) {
    error_log("Erreur de traitement : " . $e->getMessage());
    $_SESSION['erreur'] = $e->getMessage();
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../view/frontoffice/index.php'));
    exit;
}
?>
