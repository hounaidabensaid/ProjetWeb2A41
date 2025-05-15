<?php
require_once 'models/Event.php';
require_once 'models/Participant.php';
require_once 'models/ReservationEvent.php';

class EventController {
    public function showCards() {
        $events = Event::getAll();

        // Tri
        $validSortKeys = ['nom', 'date'];
        $validOrders = ['asc', 'desc'];
        $sortKey = isset($_GET['sort']) && in_array($_GET['sort'], $validSortKeys) ? $_GET['sort'] : null;
        $sortOrder = isset($_GET['order']) && in_array($_GET['order'], $validOrders) ? $_GET['order'] : 'asc';
    
        // Recherche
        if (!empty($_GET['search'])) {
            $search = strtolower($_GET['search']);
            $events = array_filter($events, function ($event) use ($search) {
                return strpos(strtolower($event['nom']), $search) !== false ||
                       strpos(strtolower($event['description']), $search) !== false;
            });
        }
    
        // Tri final si demandé
        if ($sortKey) {
            usort($events, function ($a, $b) use ($sortKey, $sortOrder) {
                $comparison = strcmp($a[$sortKey], $b[$sortKey]);
                return $sortOrder === 'asc' ? $comparison : -$comparison;
            });
        }
    
        require 'views/events/cards.php';
    }

    public function storeReservation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation des données
            $requiredFields = ['nom', 'email', 'id_event'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    http_response_code(400);
                    exit('Champs manquants');
                }
            }

            // Nettoyage des données
            $nom = htmlspecialchars($_POST['nom']);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $id_event = (int)$_POST['id_event'];

            // Création des entités
            try {
                $participantId = Participant::create($nom, $email);
                ReservationEvent::create($participantId, $id_event);
                
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
            }
            exit;
        }
    }
}