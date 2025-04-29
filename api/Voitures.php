<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// En-têtes HTTP
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Inclusions nécessaires
require_once '../config.php';
require_once '../model/Voiture.php';
require_once '../controller/VoitureController.php';

// Récupération de la méthode HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Instancier le contrôleur une seule fois
$controller = new VoitureController();

switch ($method) {
    case 'GET':
        $voitures = $controller->getAllVoitures();

        // Conversion des objets Voiture en tableau
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
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data) {
            $voiture = new Voiture(
                null,
                $data['matricule'] ?? '',
                $data['marque'] ?? '',
                $data['modele'] ?? '',
                $data['couleur'] ?? '',
                $data['nb_place'] ?? 0,
                $data['statut'] ?? ''
            );
            $controller->addVoiture($voiture);
            echo json_encode(['message' => 'Voiture ajoutée avec succès']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Données invalides']);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data && isset($data['id_voiture'])) {
            $voiture = new Voiture(
                $data['id_voiture'],
                $data['matricule'] ?? '',
                $data['marque'] ?? '',
                $data['modele'] ?? '',
                $data['couleur'] ?? '',
                $data['nb_place'] ?? 0,
                $data['statut'] ?? ''
            );
            $controller->updateVoiture($voiture);
            echo json_encode(['message' => 'Voiture mise à jour']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID ou données manquantes pour la mise à jour']);
        }
        break;

    case 'DELETE':
        // Lire les données du corps de la requête
        $data = json_decode(file_get_contents("php://input"), true); // Récupère les données JSON
        $id_voiture = $data['id_voiture'] ?? null;

        if ($id_voiture) {
            $controller = new VoitureController();
            $controller->deleteVoiture($id_voiture);
            echo json_encode(['message' => 'Voiture supprimée']);  // Retourne un message JSON
        } else {
            echo json_encode(['message' => 'ID de la voiture manquant']);  // Retourne un message JSON
        }
        break;

    default:
        echo json_encode(['message' => 'Méthode non autorisée']);  // Retourne un message JSON
        break;

}
?>