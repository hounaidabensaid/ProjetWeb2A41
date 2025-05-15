<?php
// models/Reservation.php

require_once __DIR__ . '/../config/db.php';

class Reservation
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function create($participant_id, $event_id)
    {
        $stmt = $this->conn->prepare("INSERT INTO reservationevent (id_participant, id_event) VALUES (?, ?)");
        $stmt->bind_param("ii", $participant_id, $event_id);
        $stmt->execute();
    }
}
