<?php
class ReservationEvent {
    public static function create($id_participant, $id_event) {
        $conn = new mysqli('localhost', 'root', '', '123');
        if ($conn->connect_error) {
            throw new Exception("Erreur de connexion : " . $conn->connect_error);
        }

        // Requête préparée pour éviter les injections SQL
        $stmt = $conn->prepare("INSERT INTO reservationevent (id_participant, id_event) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_participant, $id_event);
        
        if (!$stmt->execute()) {
            throw new Exception("Erreur d'exécution : " . $stmt->error);
        }

        $stmt->close();
        $conn->close();
    }
}