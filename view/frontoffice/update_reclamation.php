<?php
ob_start();
require_once __DIR__ . '/../../controller/ReclamationController.php';
require_once __DIR__ . '/../../model/Recl.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

if (empty($_POST)) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Données POST manquantes']);
    exit;
}

try {
    $id = $_POST['id'] ?? null;
    $type = $_POST['type'] ?? '';
    $nom_chauffeur = trim($_POST['nom_chauffeur'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $date_trajet = $_POST['date_trajet'] ?? '';
    $sujet = trim($_POST['sujet'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $gravite = $_POST['gravite'] ?? '';
    $statut = $_POST['statut'] ?? '';

    $errors = [];
    if (!$id) {
        $errors[] = "ID de la réclamation manquant.";
    }
    if (!in_array($type, ['occupation', 'incident', 'feedback', 'other'])) {
        $errors[] = "Type de réclamation invalide.";
    }
    if (!preg_match('/^[a-zA-ZÀ-ÿ\s-]{3,}$/', $nom_chauffeur)) {
        $errors[] = "Nom du chauffeur invalide (minimum 3 caractères, lettres, espaces, tirets).";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide.";
    }
    $now = new DateTime();
    $selected_date = DateTime::createFromFormat('Y-m-d', $date_trajet);
    $one_year_ago = (new DateTime())->modify('-1 year');
    if (!$selected_date || $selected_date > $now || $selected_date < $one_year_ago) {
        $errors[] = "Date du trajet invalide (doit être dans l'année écoulée).";
    }
    if (strlen($sujet) < 5 || strlen($sujet) > 255) {
        $errors[] = "Sujet invalide (5 à 255 caractères).";
    }
    $word_count = count(preg_split('/\s+/', $description, -1, PREG_SPLIT_NO_EMPTY));
    if ($word_count < 10 || $word_count > 500) {
        $errors[] = "Description invalide (10 à 500 mots).";
    }
    if (!in_array($gravite, ['faible', 'moyenne', 'elevee', 'urgent'])) {
        $errors[] = "Gravité invalide.";
    }
    if (!in_array($statut, ['nouveau', 'en_cours', 'resolu', 'ferme'])) {
        $errors[] = "Statut invalide.";
    }

    $piece_jointe = null;
    if (!empty($_FILES['fichier']['name'])) {
        $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
        $max_size = 2 * 1024 * 1024;
        $upload_dir = __DIR__ . '/uploads/';
        $file_name = uniqid() . '_' . basename($_FILES['fichier']['name']);
        $file_path = $upload_dir . $file_name;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (!in_array($_FILES['fichier']['type'], $allowed_types)) {
            $errors[] = "Seuls les fichiers JPG, PNG et PDF sont acceptés.";
        } elseif ($_FILES['fichier']['size'] > $max_size) {
            $errors[] = "Le fichier ne doit pas dépasser 2MB.";
        } elseif ($_FILES['fichier']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Erreur lors du téléchargement du fichier.";
        } else {
            if (!move_uploaded_file($_FILES['fichier']['tmp_name'], $file_path)) {
                $errors[] = "Impossible de sauvegarder le fichier.";
            } else {
                $piece_jointe = $file_name;
            }
        }
    }

    if (!empty($errors)) {
        ob_clean();
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    $controller = new ReclamationController();
    $existing = $controller->getReclamationById($id);
    if (!$existing) {
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'Réclamation non trouvée.']);
        exit;
    }

    $piece_jointe = $piece_jointe ?? $existing['piece_jointe'];

    $reclamation = new Reclamation(
        $type,
        $nom_chauffeur,
        $email,
        $date_trajet,
        $sujet,
        $description,
        $gravite,
        $piece_jointe,
        $statut
    );
    $reclamation->setId($id);

    $controller->updateReclamation($reclamation);

    ob_clean();
    echo json_encode(['success' => true, 'message' => 'Réclamation mise à jour avec succès.']);
    exit;

} catch (Exception $e) {
    ob_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
    exit;
}

ob_end_flush();
?>