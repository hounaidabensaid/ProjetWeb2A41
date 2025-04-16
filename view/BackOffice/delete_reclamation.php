<?php
require_once __DIR__ . '/../../controller/ReclamationController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'] ?? '';
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID manquant.']);
            exit;
        }

        $controller = new ReclamationController();
        $controller->deleteReclamation($id);

        echo json_encode(['success' => true, 'message' => 'Réclamation supprimée avec succès.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
?>