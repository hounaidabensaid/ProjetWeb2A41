<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controller/covoituragecontroller.php';

header('Content-Type: application/json');

try {
    $pdo = Config::getConnexion();
    $controller = new CovoiturageController($pdo);

    // Fetch all annonces
    $annonces = $controller->showAllAnnonces();

    $result = [];

    foreach ($annonces as $annonce) {
        $totalPriceData = $controller->calculateTotalPriceForAnnonce($annonce['id']);
        $result[] = [
            'id' => $annonce['id'],
            'totalPrice' => $totalPriceData['total']
        ];
    }

    echo json_encode(['success' => true, 'data' => $result]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
