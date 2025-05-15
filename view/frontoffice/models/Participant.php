<?php
class Participant {
    public static function create($nom, $email) {
        $conn = new mysqli('localhost', 'root', '', '123');
        if ($conn->connect_error) {
            die("Erreur de connexion : " . $conn->connect_error);
        }
        $stmt = $conn->prepare("INSERT INTO participant (nom, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $nom, $email);
        $stmt->execute();
        $inserted_id = $conn->insert_id;
        $stmt->close();
        $conn->close();
        return $inserted_id;
    }
}
