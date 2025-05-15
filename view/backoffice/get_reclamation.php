<?php
require_once __DIR__ . '/../../controller/ReclamationController.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $controller = new ReclamationController();
    $reclamations = $controller->getReclamations();
    $reclamation = array_filter($reclamations, function($rec) use ($id) {
        return $rec['id'] == $id;
    });
    $reclamation = array_values($reclamation)[0] ?? null;

    if ($reclamation) {
        echo json_encode($reclamation);
    } else {
        echo json_encode(['error' => 'Réclamation non trouvée']);
    }
} else {
    echo json_encode(['error' => 'ID manquant']);
}
?>