

<?php
// Importation des classes nécessaires de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclusion des fichiers PHPMailer (adapter le chemin si nécessaire)
require __DIR__ . '/../libs/PHPMailer-master/\PHPMailer.php';
require __DIR__ . '/../libs/PHPMailer-master/SMTP.php';
require __DIR__ . '/../libs/PHPMailer-master/Exception.php';


// Création d'une nouvelle instance de PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuration du serveur SMTP
    $mail->isSMTP();                                // Utiliser le protocole SMTP
    $mail->Host       = 'smtp.gmail.com';           // Adresse du serveur SMTP de Gmail
    $mail->SMTPAuth   = true;                       // Activer l’authentification SMTP
    $mail->Username   = 'yasminemaatougui9@gmail.com';    // ✅ Ton adresse Gmail
    $mail->Password   = 'ybnz kvzb rjhq zxbq';         // ✅ Ton mot de passe d’application
    $mail->SMTPSecure = 'ssl';                      // Chiffrement TLS
    $mail->Port       = 465;                        // Port SMTP TLS

    // Expéditeur et destinataire
    $mail->setFrom('yasminemaatougui9@gmail.com'); // ✅ Remplace par ton mail
    $mail->addAddress('yasminemaat09@gmail.com');     // Destinataire

    // Contenu du mail
    $mail->isHTML(true);                            // Envoyer en HTML
    $mail->Subject = 'Confirmation de Réservation'; // Sujet
    $mail->Body    = '
        <h2>Confirmation de Réservation</h2>
        <p>Bonjour,</p>
        <p>Votre réservation sur notre site <strong>TakeARide</strong> est bien confirmée.</p>
        <p>Merci pour votre confiance !</p>
        <p>Cordialement,<br>L’équipe TakeARide</p>
    ';

    // Envoi du mail
    $mail->send();
    echo '✅ Mail envoyé avec succès';
} catch (Exception $e) {
    // En cas d’erreur
    echo "❌ Erreur lors de l'envoi du mail : {$mail->ErrorInfo}";
}
