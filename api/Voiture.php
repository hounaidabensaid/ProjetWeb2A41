<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

require_once '../config.php';
require_once '../model/Voiture.php';
require_once '../controller/VoitureController.php';

// Récupérer les paramètres de filtrage
$marque = $_GET['marque'] ?? null;
$statut = $_GET['statut'] ?? null;
$places = $_GET['places'] ?? null;

// Initialiser le contrôleur
$controller = new VoitureController();
$voitures = $controller->getAllVoitures();

// Filtrer si des paramètres sont présents
if ($marque || $statut || $places) {
    $voitures = array_filter($voitures, function($v) use ($marque, $statut, $places) {
        return (!$marque || strcasecmp($v->getMarque(), $marque) === 0) &&
               (!$statut || strcasecmp($v->getStatut(), $statut) === 0) &&
               (!$places || $v->getNbPlace() >= $places);
    });
}

// Conversion en tableau pour JSON
$result = array_map(function($v) {
    return [
        'id_voiture' => $v->getId(),
        'matricule' => $v->getMatricule(),
        'marque' => $v->getMarque(),
        'modele' => $v->getModele(),
        'couleur' => $v->getCouleur(),
        'nb_place' => $v->getNbPlace(),
        'statut' => $v->getStatut()
    ];
}, array_values($voitures));

echo json_encode($result);
?>

?>