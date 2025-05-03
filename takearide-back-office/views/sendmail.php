<?php
// Importation des classes nécessaires de PHPMailer
//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;

// Inclusion des fichiers PHPMailer (adapter le chemin si nécessaire)
/*require __DIR__ . '/../libs/PHPMailer-master/PHPMailer.php';
require __DIR__ . '/../libs/PHPMailer-master/SMTP.php';
require __DIR__ . '/../libs/PHPMailer-master/Exception.php';
require_once __DIR__ . '/../libs/phpqrcode/qrlib.php';
*/

/**
 * Fonction pour envoyer un email de confirmation
 * 
 * @param string $recipientEmail Email du destinataire
 * @return array Tableau avec 'success' (bool) et 'message' (string)
 */
/*function sendConfirmationEmail($recipientEmail) {
    // Création d'une nouvelle instance de PHPMailer
    $mail = new PHPMailer(true);



    try {
        // Configuration du serveur SMTP
        $mail->isSMTP();                                // Utiliser le protocole SMTP
        $mail->Host       = 'smtp.gmail.com';           // Adresse du serveur SMTP de Gmail
        $mail->SMTPAuth   = true;                       // Activer l'authentification SMTP
        $mail->Username   = 'yasminemaatougui9@gmail.com';    // Adresse Gmail
        $mail->Password   = 'ybnz kvzb rjhq zxbq';         // Mot de passe d'application
        $mail->SMTPSecure = 'ssl';                      // Chiffrement TLS
        $mail->Port       = 465;                        // Port SMTP TLS

        // Expéditeur et destinataire
        $mail->setFrom('yasminemaatougui9@gmail.com', 'TakeARide');
        $mail->addAddress($recipientEmail);             // Destinataire dynamique

        // Contenu du mail
        $mail->isHTML(true);                            // Envoyer en HTML
        $mail->Subject = 'Confirmation de Réservation'; // Sujet
        $mail->Body    = '
            <h2>Confirmation de Réservation</h2>
            <p>Bonjour,</p>
            <p>Votre réservation sur notre site <strong>TakeARide</strong> est bien confirmée.</p>
            <p>Merci pour votre confiance !</p>
            <p>Cordialement,<br>équipe TakeARide</p>
        ';

        // Envoi du mail
        $mail->send();
        return ['success' => true, 'message' => '✅ Mail envoyé avec succès'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => "❌ Erreur lors de l'envoi du mail : {$mail->ErrorInfo}"];
    }
}*/
/* 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../libs/PHPMailer-master/PHPMailer.php';
require __DIR__ . '/../libs/PHPMailer-master/SMTP.php';
require __DIR__ . '/../libs/PHPMailer-master/Exception.php';
require_once __DIR__ . '/../libs/phpqrcode/qrlib.php'; // inclusion QR code

function sendConfirmationEmailWithQR($recipientEmail, $id_participant, $id_reservation) {
    try {
        // === 1. Génération du QR Code ===
        $qrContent = "id_participant: $id_participant\nid_reservation: $id_reservation\nconfirmed reservation";
        $qrDir = __DIR__ . '/../qrcodes/';
        if (!file_exists($qrDir)) {
            mkdir($qrDir, 0777, true);
        }
        $qrFile = $qrDir . "qr_{$id_reservation}.png";
        QRcode::png($qrContent, $qrFile, QR_ECLEVEL_L, 4);

        // === 2. Configuration PHPMailer ===
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true ;
        $mail->Username   = 'yasminemaatougui9@gmail.com';
        $mail->Password   = 'ybnz kvzb rjhq zxbq'; // mot de passe application Gmail
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        $mail->setFrom('yasminemaatougui9@gmail.com', 'TakeARide');
        $mail->addAddress($recipientEmail);
        $mail->isHTML(true);
        $mail->Subject = 'Confirmation de Réservation';

        // === 3. Ajout du QR code au mail ===
        $mail->addEmbeddedImage($qrFile, 'qrimg'); // cid:qrimg pour HTML

        $mail->Body = "
            <h2>Votre réservation est confirmée !</h2>
            <p>ID Participant : $id_participant</p>
            <p>ID Réservation : $id_reservation</p>
            <p>Voici votre code QR à présenter :</p>
            <img src='cid:qrimg' alt='QR Code'>
            <p><em>Merci pour votre confiance.</em></p>
        ";

        $mail->send();

        // Optionnel : supprimer image QR après envoi
        unlink($qrFile);

        return ['success' => true, 'message' => '✅ Email envoyé avec QR code'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => "❌ Erreur mail : " . $e->getMessage()];
    }
}
    */

   
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    require_once __DIR__ . '/../libs/phpqrcode/qrlib.php'; // Assurez-vous que cette lib est installée
    require __DIR__ . '/../libs/PHPMailer-master/PHPMailer.php';
    require __DIR__ . '/../libs/PHPMailer-master/SMTP.php';
    require __DIR__ . '/../libs/PHPMailer-master/Exception.php';
    
    function sendConfirmationEmailWithQR($recipientEmail, $participantId, $reservationId)
    {
        // 1. Générer le contenu du QR code
        $qrContent = "ID de réservation : $reservationId\nID participant : $participantId";
    
        // 2. Générer un fichier PNG temporaire pour le QR code
        $qrFilePath = __DIR__ . "/qr_codes/qr_$reservationId.png";
        if (!file_exists(dirname($qrFilePath))) {
            mkdir(dirname($qrFilePath), 0777, true);
        }
        QRcode::png($qrContent, $qrFilePath);
    
        // 3. Créer et configurer l'e-mail
        $mail = new PHPMailer(true);
        try {
            // Configuration serveur SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Remplacer par votre SMTP si nécessaire
            $mail->SMTPAuth   = true;
            $mail->Username   = 'yasminemaatougui9@gmail.com'; // 🔁 Remplacer par ton adresse Gmail
            $mail->Password   = 'ybnz kvzb rjhq zxbq';    // 🔁 Remplacer par le mot de passe d’application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587; // Port pour STARTTLS
    
            // Expéditeur & destinataire
            $mail->setFrom('yasminemaatougui9@gmail.com', 'Take a Ride');
            $mail->addAddress($recipientEmail);
    
            // Contenu
            $mail->isHTML(true);
            $mail->Subject = 'Confirmation de votre réservation';
            $mail->Body    = "
                <p>Bonjour,</p>
                <p>Votre réservation (ID: <strong>$reservationId</strong>) a été confirmée avec succès.</p>
                <p>Vous trouverez ci-joint un QR code à présenter lors de l’événement.</p>
                <p>Merci pour votre confiance,<br><strong>L'équipe Take a Ride</strong></p>
            ";
            $mail->addAttachment($qrFilePath, "qr_reservation_$reservationId.png");
    
            // Envoi du mail
            $mail->send();
    
            // Nettoyer le fichier QR après envoi
            if (file_exists($qrFilePath)) {
                unlink($qrFilePath);
            }
    
            return ['success' => true, 'message' => 'E-mail envoyé avec succès'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => "Erreur d'envoi: {$mail->ErrorInfo}"];
        }
    }
    

