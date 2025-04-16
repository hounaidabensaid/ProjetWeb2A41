<?php
ob_start(); // Start output buffering


require_once __DIR__ . '/../../controller/ReclamationController.php';
require_once __DIR__ . '/../../model/Recl.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST)) {
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'Données POST manquantes']);
        exit;
    }
    try {
        $id = $_POST['id'] ?? null;
        $type = $_POST['type'] ?? '';
        $nom_chauffeur = trim($_POST['nom_chauffeur'] ?? '');
        $date_trajet = $_POST['date_trajet'] ?? '';
        $sujet = trim($_POST['sujet'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $gravite = $_POST['gravite'] ?? '';

        // Basic validation
        $errors = [];
        if (!$id) {
            $errors[] = "ID de la réclamation manquant.";
        }
        if (!in_array($type, ['occupation', 'incident', 'feedback', 'other'])) {
            $errors[] = "Type de réclamation invalide.";
        }
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s-]{3,}$/', $nom_chauffeur)) {
            $errors[] = "Nom du chauffeur invalide.";
        }
        $now = new DateTime();
        $selected_date = DateTime::createFromFormat('Y-m-d', $date_trajet);
        $one_year_ago = (new DateTime())->modify('-1 year');
        if (!$selected_date || $selected_date > $now || $selected_date < $one_year_ago) {
            $errors[] = "Date du trajet invalide.";
        }
        if (strlen($sujet) < 5) {
            $errors[] = "Sujet trop court.";
        }
        $word_count = count(preg_split('/\s+/', $description, -1, PREG_SPLIT_NO_EMPTY));
        if ($word_count < 10) {
            $errors[] = "Description trop courte.";
        }
        if (!in_array($gravite, ['faible', 'moyenne', 'elevee', 'urgent'])) {
            $errors[] = "Gravité invalide.";
        }

        if (!empty($errors)) {
            echo json_encode(['success' => false, 'errors' => $errors]);
            exit;
        }

        // Fetch the existing reclamation to preserve fields like piece_jointe and statut
        $controller = new ReclamationController();
        $existing = $controller->getReclamationById($id);
        if (!$existing) {
            echo json_encode(['success' => false, 'message' => 'Réclamation non trouvée.']);
            exit;
        }

        $reclamation = new Reclamation($type, $nom_chauffeur, $date_trajet, $sujet, $description, $gravite, $existing['piece_jointe'], $existing['statut']);
        $reclamation->setId($id);

        $controller->updateReclamation($reclamation);

        echo json_encode(['success' => true, 'message' => 'Réclamation mise à jour avec succès.']);
    } catch (Exception $e) {
        try {
            // Your existing code...
        } catch (Exception $e) {
            // Ensure we clear any previous output
            ob_clean();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
            exit;
        }    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
ob_end_flush(); // Send the output buffer and turn off output buffering

?>