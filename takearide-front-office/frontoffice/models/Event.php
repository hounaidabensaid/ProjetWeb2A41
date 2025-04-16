<?php
class Event {
    public static function getAll() {
        $conn = new mysqli('localhost', 'root', '', '123');
        if ($conn->connect_error) {
            die("Erreur de connexion : " . $conn->connect_error);
        }

        $sql = "SELECT * FROM event";
        $result = $conn->query($sql);
        $events = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $events[] = $row;
            }
        }

        $conn->close();
        return $events;
    }
}
