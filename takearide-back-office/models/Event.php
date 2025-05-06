<?php
require_once '../config.php';

class Event
{
    private $conn;

    public function __construct()
    {
        $this->conn = Config::getConnexion();  // Majuscule ici
    }

    // Récupérer tous les événements
    public function getAll()
    {
        $sql = "SELECT * FROM event";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();   // En PDO, fetchAll() sans param récupère selon FETCH_ASSOC défini
    }

    // Ajouter un événement
    public function save($nom, $description, $lieu, $date, $nbplace, $image)
    {
        $sql = "INSERT INTO event (nom, description, lieu, date, nbplace, image) VALUES (:nom, :description, :lieu, :date, :nbplace, :image)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nom' => $nom,
            ':description' => $description,
            ':lieu' => $lieu,
            ':date' => $date,
            ':nbplace' => (int)$nbplace,
            ':image' => $image
        ]);
    }

    // Récupérer un événement par ID
    public function getById($id)
    {
        $sql = "SELECT * FROM event WHERE id_event = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();   // fetch() pour une seule ligne
    }

    // Mettre à jour un événement
    public function update($id, $nom, $description, $lieu, $date, $nbplace, $image)
    {
        $sql = "UPDATE event SET nom = :nom, description = :description, lieu = :lieu, date = :date, nbplace = :nbplace, image = :image  WHERE id_event = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nom' => $nom,
            ':description' => $description,
            ':lieu' => $lieu,
            ':date' => $date,
            ':nbplace' => (int)$nbplace,
            ':image' => $image,
            ':id' => $id
        ]);
    }

    // Supprimer un événement
    public function delete($id)
    {
        $sql = "DELETE FROM event WHERE id_event = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Mettre à jour nbplace (pour les réservations)
    public function updateNbPlace($id_event, $increment)
    {
        $sql = "UPDATE event SET nbplace = nbplace + :increment WHERE id_event = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':increment' => $increment, // Peut être +1 ou -1
            ':id' => $id_event
        ]);
    }
}
?>
