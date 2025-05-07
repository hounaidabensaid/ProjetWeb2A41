<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controller/ReclamationController.php';
require_once __DIR__ . '/../../model/Recl.php';

// Set JSON header
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Server-side validation
        $errors = [];

        // Validate type
        $type = $_POST['type'] ?? '';
        if (!in_array($type, ['occupation', 'incident', 'feedback', 'other'])) {
            $errors[] = "Type de réclamation invalide.";
        }

        // Validate nom_chauffeur
        $nom_chauffeur = trim($_POST['nom_chauffeur'] ?? '');
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s-]{3,}$/', $nom_chauffeur)) {
            $errors[] = "Le nom du chauffeur doit contenir au moins 3 caractères (lettres, espaces, tirets uniquement).";
        }

        // Validate date_trajet
        $date_trajet = $_POST['date_trajet'] ?? '';
        $now = new DateTime();
        $selected_date = DateTime::createFromFormat('Y-m-d', $date_trajet);
        $one_year_ago = (new DateTime())->modify('-1 year');
        if (!$selected_date) {
            $errors[] = "La date du trajet est requise.";
        } elseif ($selected_date > $now) {
            $errors[] = "La date du trajet ne peut pas être dans le futur.";
        } elseif ($selected_date < $one_year_ago) {
            $errors[] = "La date du trajet ne peut pas être antérieure à 1 an.";
        }

        // Validate sujet
        $sujet = trim($_POST['sujet'] ?? '');
        if (strlen($sujet) < 5) {
            $errors[] = "Le sujet doit contenir au moins 5 caractères.";
        } elseif (strlen($sujet) > 255) {
            $errors[] = "Le sujet ne doit pas dépasser 255 caractères.";
        }

        // Validate description
        $description = trim($_POST['description'] ?? '');
        $word_count = count(preg_split('/\s+/', $description, -1, PREG_SPLIT_NO_EMPTY));
        if ($word_count < 10) {
            $errors[] = "La description doit contenir au moins 10 mots.";
        } elseif ($word_count > 500) {
            $errors[] = "La description ne doit pas dépasser 500 mots.";
        }

        // Validate gravite
        $gravite = $_POST['gravite'] ?? '';
        if (!in_array($gravite, ['faible', 'moyenne', 'elevee', 'urgent'])) {
            $errors[] = "Niveau de gravité invalide.";
        }

        // Validate email
        $email = trim($_POST['email'] ?? '');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'email est invalide.";
        }

        // Handle file upload
        $piece_jointe = null;
        if (!empty($_FILES['fichier']['name'])) {
            $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
            $max_size = 2 * 1024 * 1024; // 2MB
            $upload_dir = __DIR__ . '/uploads/';
            $file_name = uniqid() . '_' . basename($_FILES['fichier']['name']);
            $file_path = $upload_dir . $file_name;

            // Create uploads directory if it doesn't exist
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
                // Move the uploaded file
                if (!move_uploaded_file($_FILES['fichier']['tmp_name'], $file_path)) {
                    $errors[] = "Impossible de sauvegarder le fichier.";
                } else {
                    $piece_jointe = $file_name;
                }
            }
        }

        // If there are errors, return them as JSON
        if (!empty($errors)) {
            echo json_encode(['success' => false, 'errors' => $errors]);
            exit;
        }

        // Create Reclamation object
        $reclamation = new Reclamation(
            $type,
            $nom_chauffeur,
            $date_trajet,
            $sujet,
            $description,
            $gravite,
            $piece_jointe,
            $email // Ajout de l'email
        );

        // Add to database
        $controller = new ReclamationController();
        $controller->addReclamation($reclamation);

        // Return success response as JSON
        echo json_encode([
            'success' => true,
            'message' => 'Réclamation envoyée avec succès ! Vous recevrez une confirmation par email sous 24h.'
        ]);
        exit;

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'errors' => ["Erreur lors de l'enregistrement: " . $e->getMessage()]
        ]);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'errors' => ['Méthode non autorisée.']]);
    exit;
}
?>