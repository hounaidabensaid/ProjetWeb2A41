<?php
require_once '../config.php';

class ReservationEventController
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = config::getConnexion();
    }

    public function addReservation($id_event, $id_participant)
    {
        $sql = "INSERT INTO reservationevent (id_event, id_participant) VALUES (:id_event, :id_participant)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_event' => $id_event,
            ':id_participant' => $id_participant
        ]);
    }

   public function getAllReservations()
{
    $sql = "SELECT re.id_reservation, 
                   e.nom AS nom_event, 
                   u.nom AS nom_user, 
                   u.prenom AS prenom_user, 
                   re.date_reservation
            FROM reservationevent re
            JOIN event e ON re.id_event = e.id_event
            JOIN user u ON re.id_participant = u.id";
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll();
}


    public function getReservationById($id_reservation)
    {
        $sql = "SELECT * FROM reservationevent WHERE id_reservation = :id_reservation";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_reservation' => $id_reservation]);
        return $stmt->fetch();
    }

  
    public function updateReservation($id_reservation, $id_event, $id_participant)
    {
        $sql = "UPDATE reservationevent 
                SET id_event = :id_event, id_participant = :id_participant 
                WHERE id_reservation = :id_reservation";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_event' => $id_event,
            ':id_participant' => $id_participant,
            ':id_reservation' => $id_reservation
        ]);
    }

    public function deleteReservation($id_reservation)
    {
        $sql = "DELETE FROM reservationevent WHERE id_reservation = :id_reservation";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_reservation' => $id_reservation]);
    }
	public function getReservationsByParticipant($id_participant)
{
    $sql = "SELECT re.id_reservation, e.nom, re.date_reservation 
            FROM reservationevent re
            JOIN event e ON re.id_event = e.id_event
            WHERE re.id_participant = :id_participant";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id_participant' => $id_participant]);
    return $stmt->fetchAll();
}


}
?>
