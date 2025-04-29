<?php
require_once '../../config.php';
require_once '../../controller/ReservationController.php';

session_start();

$pdo = config::getConnexion();
$controller = new ReservationController($pdo);

// On récupère le nombre total de réservations
$totalReservations = count($controller->getAllReservations());

// Stocker le dernier nombre connu dans la session
if (!isset($_SESSION['last_reservation_count'])) {
    $_SESSION['last_reservation_count'] = $totalReservations;
    echo json_encode(['new' => false]);
    exit;
}

if ($totalReservations > $_SESSION['last_reservation_count']) {
    $_SESSION['last_reservation_count'] = $totalReservations;
    echo json_encode(['new' => true]);
} else {
    echo json_encode(['new' => false]);
}
?>
