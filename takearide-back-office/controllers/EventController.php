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
    public function addEvent($nom, $description, $lieu, $date, $nbplace, $image)
    {
        $sql = "INSERT INTO event (nom, description, lieu, date, nbplace, image) 
                VALUES (:nom, :description, :lieu, :date, :nbplace, :image)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':description' => $description,
            ':lieu' => $lieu,
            ':date' => $date,
            ':nbplace' => (int)$nbplace,
            ':image' => $image
        ]);
    }

    // 🔹 Récupérer tous les événements
    public function getAllEvents()
    {
        $sql = "SELECT * FROM event";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Récupérer un événement par ID
    public function getEventById($id_event)
    {
        $sql = "SELECT * FROM event WHERE id_event = :id_event";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_event' => $id_event]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🔹 Mettre à jour un événement
    public function updateEvent($id, $nom, $description, $lieu, $date, $nbplace, $image = null) {
        if ($image !== null) {
            $sql = "UPDATE event SET nom=?, description=?, lieu=?, date=?, nbplace = ?, image=? WHERE id_event=?";
            $params = [$nom, $description, $lieu, $date, (int)$nbplace, $image, $id];
        } else {
            $sql = "UPDATE event SET nom=?, description=?, lieu=?, date=?, nbplace = ? WHERE id_event=?";
            $params = [$nom, $description, $lieu, $date, (int)$nbplace, $id];
        }
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // 🔹 Supprimer un événement
    public function deleteEvent($id_event)
    {
        $sql = "DELETE FROM event WHERE id_event = :id_event";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_event' => $id_event]);
    }
     // 🔹 Mettre à jour nbplace (pour les réservations)
     public function updateNbPlace($id_event, $increment)
     {
         $sql = "UPDATE event SET nbplace = nbplace + :increment WHERE id_event = :id";
         $stmt = $this->pdo->prepare($sql);
         return $stmt->execute([
             ':increment' => $increment, // +1 ou -1
             ':id' => $id_event
         ]);
     }
}
?>
