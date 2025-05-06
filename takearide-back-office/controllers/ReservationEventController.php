<?php
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
    
        // Récupérer les infos actuelles de la réservation (ancien statut et id_event)
        $reservationQuery = "SELECT status, id_event FROM reservationevent WHERE id_reservation = :id_reservation";
        $reservationStmt = $this->pdo->prepare($reservationQuery);
        $reservationStmt->execute([':id_reservation' => $id_reservation]);
        $reservation = $reservationStmt->fetch();
    
        if (!$reservation) {
            throw new Exception("Réservation non trouvée.");
        }
    
        $old_status = $reservation['status'];
        $id_event = $reservation['id_event'];
    
        // Récupérer l'email et l'id du participant
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
    
        // ⚠️ Si passage de 'pending' à 'approved', on vérifie s'il reste des places
        if ($old_status === 'pending' && $new_status === 'approved') {
            $checkPlacesQuery = "SELECT nbplace FROM event WHERE id_event = :id_event";
            $checkStmt = $this->pdo->prepare($checkPlacesQuery);
            $checkStmt->execute([':id_event' => $id_event]);
            $event = $checkStmt->fetch();
    
            if (!$event || $event['nbplace'] <= 0) {
                throw new Exception("Aucune place disponible pour cet événement.");
            }
        }
    
        // Mettre à jour le statut de la réservation
        $sql = "UPDATE reservationevent SET status = :status WHERE id_reservation = :id_reservation";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':status' => $new_status,
            ':id_reservation' => $id_reservation
        ]);
    
        // 🔄 Mise à jour dynamique du nbplace selon le changement de statut
        if ($old_status === 'pending' && $new_status === 'approved') {
            // Réduire le nombre de places disponibles
            $this->pdo->prepare("UPDATE event SET nbplace = nbplace - 1 WHERE id_event = :id_event AND nbplace > 0")
                      ->execute([':id_event' => $id_event]);
    
        } elseif ($old_status === 'approved' && in_array($new_status, ['cancelled', 'declined'])) {
            // Réattribuer une place si une réservation approuvée est annulée ou déclinée
            $this->pdo->prepare("UPDATE event SET nbplace = nbplace + 1 WHERE id_event = :id_event")
                      ->execute([':id_event' => $id_event]);
        }
    
        // 📧 Envoi de l'email avec QR code si approuvé
        if ($new_status === 'approved') {
            $email = $result['email'];
            $id_participant = $result['id_participant'];
    
            $res = sendConfirmationEmailWithQR($email, $id_participant, $id_reservation);
            if (!$res['success']) {
                throw new Exception("Erreur lors de l'envoi du mail: " . $res['message']);
            }
        }
    
        return true;
    }
    
}
?>
