<?php

require_once '../../config.php'; 
require_once '../../model/Reservation.php';
require_once 'vendor/autoload.php';

use Twilio\Rest\Client;

class ReservationController {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
	
	
	public function updateStatut($id_reservation, $newStatut) {
    $sql = "UPDATE réservations  SET statut = :statut WHERE id_reservation = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        'statut' => $newStatut,
        'id' => $id_reservation
    ]);
}


    public function addReservation(Reservation $reservation) {
        $sql = "INSERT INTO réservations (id_voiture, id_user, date_début, date_fin, statut)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $reservation->getIdVoiture(),
            $reservation->getIdUser(),
            $reservation->getDateDébut(),
            $reservation->getDateFin(),
            $reservation->getStatut()
        ]);
    }
    //message de confirmation 
    public function sendConfirmationSMS($phoneNumber, $userName, $dateDebut, $dateFin) {
		    error_log("Tentative d'envoi SMS à $phoneNumber pour $userName");

        $sid = "AC72b8e4a7ab60857a56ad52d1555d848b";
        $token = "88c45ac488c79b1d9bf76a03fe290d8c";
        $twilioNumber = "+16628508706";

        $client = new Client($sid, $token);

        $messageText = "Bonjour $userName, votre réservation est confirmée du $dateDebut au $dateFin. Merci - CarServ";

        try {
            $client->messages->create(
                $phoneNumber, 
                [
                    'from' => $twilioNumber,
                    'body' => $messageText
                ]
            );
        } catch (\Exception $e) {
            error_log("Erreur lors de l'envoi du SMS : " . $e->getMessage());
        }
    }

    public function getAllReservations() {
        $sql = "SELECT r.*, v.matricule, v.marque 
                FROM réservations r 
                JOIN voiture v ON r.id_voiture = v.id_voiture";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservationById($id) {
        $sql = "SELECT * FROM réservations WHERE id_reservation = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateReservation(Reservation $reservation) {
        $sql = "UPDATE réservations 
                SET id_voiture = ?, id_user = ?, date_début = ?, date_fin = ?, statut = ?
                WHERE id_reservation = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $reservation->getIdVoiture(),
            $reservation->getIdUser(),
            $reservation->getDateDébut(),
            $reservation->getDateFin(),
            $reservation->getStatut(),
            $reservation->getIdReservation()
        ]);
    }

    public function deleteReservation($id) {
        $sql = "DELETE FROM réservations WHERE id_reservation = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
