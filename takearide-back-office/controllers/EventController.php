<?php
require_once '../config.php';

class EventController
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = config::getConnexion();
    }

    // 🔹 Ajouter un événement
    public function addEvent($nom, $description, $lieu, $date, $image)
    {
        $sql = "INSERT INTO event (nom, description, lieu, date, image) 
                VALUES (:nom, :description, :lieu, :date, :image)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':description' => $description,
            ':lieu' => $lieu,
            ':date' => $date,
            ':image' => $image
        ]);
    }

    // 🔹 Récupérer tous les événements
    public function getAllEvents()
    {
        $sql = "SELECT * FROM event";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    // 🔹 Récupérer un événement par ID
    public function getEventById($id_event)
    {
        $sql = "SELECT * FROM event WHERE id_event = :id_event";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_event' => $id_event]);
        return $stmt->fetch();
    }

    // 🔹 Mettre à jour un événement
    public function updateEvent($id_event, $nom, $description, $lieu, $date, $image)
    {
        $sql = "UPDATE event 
                SET nom = :nom, description = :description, lieu = :lieu, date = :date, image = :image 
                WHERE id_event = :id_event";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':description' => $description,
            ':lieu' => $lieu,
            ':date' => $date,
            ':id_event' => $id_event,
            ':image' => $image
        ]);
    }

    // 🔹 Supprimer un événement
    public function deleteEvent($id_event)
    {
        $sql = "DELETE FROM event WHERE id_event = :id_event";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_event' => $id_event]);
    }
}
?>
