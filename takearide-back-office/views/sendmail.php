<?php
// Importation des classes nécessaires de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclusion des fichiers PHPMailer (adapter le chemin si nécessaire)
require __DIR__ . '/../libs/PHPMailer-master/PHPMailer.php';
require __DIR__ . '/../libs/PHPMailer-master/SMTP.php';
require __DIR__ . '/../libs/PHPMailer-master/Exception.php';

/**
 * Fonction pour envoyer un email de confirmation
 * 
 * @param string $recipientEmail Email du destinataire
 * @return array Tableau avec 'success' (bool) et 'message' (string)
 */
function sendConfirmationEmail($recipientEmail) {
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
}