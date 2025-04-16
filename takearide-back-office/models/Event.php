<?php
require_once 'config/db.php';

class Event
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function getAll()
    {
        $result = $this->conn->query("SELECT * FROM event");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function save($nom, $description, $lieu, $date)
    {
        $stmt = $this->conn->prepare("INSERT INTO event (nom, description, lieu, date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nom, $description, $lieu, $date);
        return $stmt->execute();
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM event WHERE id_event = ?");

        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update($id, $nom, $description, $lieu, $date)
{
    $stmt = $this->conn->prepare("UPDATE event SET nom = ?, description = ?, lieu = ?, date = ? WHERE id_event = ?");
    $stmt->bind_param("ssssi", $nom, $description, $lieu, $date, $id);
    return $stmt->execute();
}


    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM event WHERE id_event = ?");

        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}
