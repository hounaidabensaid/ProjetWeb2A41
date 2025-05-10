<?php
session_start();
require_once __DIR__.'/../../config.php';
require_once __DIR__.'/../../controller/CovoiturageController.php';

try {
    $pdo = Config::getConnexion();
    $controller = new CovoiturageController($pdo);

    // Simple validation for villeDepart != villeArrivee
    if ($_POST['villeDepart'] === $_POST['villeArrivee']) {
        $_SESSION['erreur'] = "La ville de départ ne peut pas être la même que la ville d'arrivée.";
        $_SESSION['form_data'] = $_POST;
        header('Location: covoiturage.php');
        exit;
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update existing annonce
        $controller->updateAnnonce(
            $_POST['id'],
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['villeDepart'],
            $_POST['villeArrivee'],
            $_POST['date'],
            $_POST['prix'],
            $_POST['matricule'],
            $_POST['typeVehicule'],
            $_POST['placesDisponibles'],
            $_POST['details'],
            $_POST['telephone']
        );
        $_SESSION['success'] = "Annonce mise à jour avec succès.";
    } else {
        // Add new annonce
        $user_id = $_SESSION['user_id'] ?? null;
        $controller->addAnnonce(
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['villeDepart'],
            $_POST['villeArrivee'],
            $_POST['date'],
            $_POST['prix'],
            $_POST['matricule'],
            $_POST['typeVehicule'],
            $_POST['placesDisponibles'],
            $_POST['details'],
            $_POST['telephone'],
            $user_id
        );
        $_SESSION['success'] = "Annonce ajoutée avec succès.";
    }

    header('Location: covoiturage.php');
    exit;

} catch (Exception $e) {
    $_SESSION['erreur'] = "Erreur lors de l'ajout ou mise à jour de l'annonce : " . $e->getMessage();
    $_SESSION['form_data'] = $_POST;
    header('Location: covoiturage.php');
    exit;
}
?>