<?php
require_once __DIR__ . '/../../controller/covoituragecontroller.php';

// Database connection
try {
    $pdo = new PDO('mysql:host=localhost;dbname=123;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

// Instantiate controller
$controller = new CovoiturageController($pdo);

// Get statistics data
$stats = $controller->getStatsVille();

// Include the view
include __DIR__ . '/statistiques_villes.php';
?>
