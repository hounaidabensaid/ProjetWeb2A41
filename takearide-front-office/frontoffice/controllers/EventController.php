<?php
require_once 'models/Event.php';

class EventController {
    public function showCards() {
        $events = Event::getAll();
        require 'views/events/cards.php';
    }

    public function storeReservation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $email = $_POST['email'];
            $id_event = $_POST['id_event'];
    
            require_once 'models/Participant.php';
            Participant::create($nom, $email, $id_event);
    
            $_SESSION['flash'] = "Réservation enregistrée.";
            header("Location: index.php");
            exit;
        }
    }
    
}
