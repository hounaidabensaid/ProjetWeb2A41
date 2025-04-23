<?php
require_once 'models/Event.php';
require_once 'models/Participant.php';
require_once 'models/ReservationEvent.php';

class EventController {
    public function showCards() {
        $events = Event::getAll();
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