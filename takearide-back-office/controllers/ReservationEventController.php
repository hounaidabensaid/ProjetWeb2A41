<?php
/**require_once '../config.php';
require_once __DIR__ . '/../views/sendmail.php';
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
    //    return $this->pdo->lastInsertId(); // 🔁 retourne l'ID généré

    }

   public function getAllReservations()
{
    $sql = "SELECT re.id_reservation, 
                   e.nom AS nom_event, 
                   u.nom AS nom_user, 
                   u.prenom AS prenom_user, 
                   re.date_reservation,
                   re.status
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

    public function updateStatus($id_reservation, $new_status)
    {
        // List of valid statuses
        $validStatuses = ['pending', 'approved', 'declined', 'cancelled'];

        // Validate the new status
        if (!in_array($new_status, $validStatuses)) {
            throw new InvalidArgumentException("Statut non valide.");
        }

        // First, get the participant's email before updating
        $emailQuery = "SELECT u.email 
                    FROM reservationevent r
                    JOIN user u ON r.id_participant = u.id
                    WHERE r.id_reservation = :id_reservation";
        
        $emailStmt = $this->pdo->prepare($emailQuery);
        $emailStmt->execute([':id_reservation' => $id_reservation]);
        $participantEmail = $emailStmt->fetchColumn();

        if (!$participantEmail) {
            throw new Exception("Email du participant non trouvé.");
        }

        // SQL query to update the status
        $sql = "UPDATE reservationevent SET status = :status WHERE id_reservation = :id_reservation";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':status' => $new_status,
            ':id_reservation' => $id_reservation
        ]);

        // Only send email if status is approved
        if ($new_status === 'approved') {
            // Include the sendmail.php file
            require_once __DIR__ . '/../views/sendmail.php';
        
            // Call the sendConfirmationEmail function
            $result = sendConfirmationEmail($participantEmail);
            if (!$result['success']) {
                throw new Exception("Erreur lors de l'envoi du mail: " . $result['message']);
            }
        }

        return true;
    }

}
public function addReservation($id_event, $id_participant)
{
    $sql = "INSERT INTO reservationevent (id_event, id_participant) VALUES (:id_event, :id_participant)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':id_event' => $id_event,
        ':id_participant' => $id_participant
    ]);
    return $this->pdo->lastInsertId(); // 🔁 retourne l'ID généré
}

?>*/



require_once '../config.php';
require_once __DIR__ . '/../views/sendmail.php';

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
        return $this->pdo->lastInsertId(); // ✅ On retourne bien l'ID ici
    }

    public function getAllReservations()
    {
        $sql = "SELECT re.id_reservation, 
                       e.nom AS nom_event, 
                       u.nom AS nom_user, 
                       u.prenom AS prenom_user, 
                       re.date_reservation,
                       re.status
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

    public function updateStatus($id_reservation, $new_status)
    {
        $validStatuses = ['pending', 'approved', 'declined', 'cancelled'];
        if (!in_array($new_status, $validStatuses)) {
            throw new InvalidArgumentException("Statut non valide.");
        }

        $emailQuery = "SELECT u.email, u.id as id_participant 
                       FROM reservationevent r
                       JOIN user u ON r.id_participant = u.id
                       WHERE r.id_reservation = :id_reservation";
        $emailStmt = $this->pdo->prepare($emailQuery);
        $emailStmt->execute([':id_reservation' => $id_reservation]);
        $result = $emailStmt->fetch();

        if (!$result || empty($result['email'])) {
            throw new Exception("Email du participant non trouvé.");
        }

        $sql = "UPDATE reservationevent SET status = :status WHERE id_reservation = :id_reservation";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':status' => $new_status,
            ':id_reservation' => $id_reservation
        ]);

        if ($new_status === 'approved') {
            $email = $result['email'];
            $id_participant = $result['id_participant'];

            // ✅ Appel à la fonction avec QR
            $res = sendConfirmationEmailWithQR($email, $id_participant, $id_reservation);
            if (!$res['success']) {
                throw new Exception("Erreur lors de l'envoi du mail: " . $res['message']);
            }
        }

        return true;
    }
}
?>
