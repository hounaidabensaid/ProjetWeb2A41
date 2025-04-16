<?php
class Participant {
    public static function create($nom, $email, $id_event) {
        $conn = new mysqli('localhost', 'root', '', '123');
        if ($conn->connect_error) {
            die("Erreur de connexion : " . $conn->connect_error);
        }
        $stmt = $conn->prepare("INSERT INTO participant (nom, email, id_event) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nom, $email, $id_event);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
}
