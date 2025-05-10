<?php

require_once('../../controller/UserController.php');
require_once('../../controller/vendor/autoload.php'); // PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 🔧 Nouvelle configuration mail
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_USER', 'guellouznour1234@gmail.com'); // Votre adresse Gmail
define('MAIL_PASS', 'haoojvwqicartvbc'); // Mot de passe d'application généré depuis Gmail

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $controller = new UserController();
    $user = $controller->getUserByEmail($email);

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $controller->saveResetToken($user->getId(), $token); // 🔧 à implémenter

        $resetLink = "http://localhost/projetweb/view/frontoffice/new_password.php?token=$token";

        // 📧 ENVOI EMAIL AVEC PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = MAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = MAIL_USER;
            $mail->Password = MAIL_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Utilisation de STARTTLS
            $mail->Port = 587;

            $mail->setFrom(MAIL_USER, 'Support Technique');
            $mail->addAddress($email);
            $mail->Subject = 'Réinitialisation de votre mot de passe';
            $mail->Body = "Bonjour,\n\nCliquez ici pour réinitialiser votre mot de passe :\n$resetLink\n\nCe lien est valable 15 minutes.";

            $mail->send();
            $success = "✅ Un lien de réinitialisation a été envoyé à votre adresse.";
        } catch (Exception $e) {
            $error = "Erreur d'envoi de mail : " . $mail->ErrorInfo;
        }
    } else {
        $error = "❌ Aucune adresse email correspondante trouvée.";
    }
}
?>

<style>
    body {
        background: url('../../view/frontoffice/img/p2.jpg') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        color: #fff;
    }

    .container {
        max-width: 450px;
        margin: 50px auto;
        background-color: rgba(0, 0, 0, 0.7); /* Couleur noire avec 70% d'opacité */
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
    }

    .card {
        border: none;
        border-radius: 8px;
        overflow: hidden;
    }

    .card-body {
        padding: 20px;
    }

    h3 {
        color: #fff; /* Changed from red to white */
        font-weight: bold;
        text-align: center;
        margin-bottom: 20px;
    }

    label {
        color: #fff;
        font-weight: 500;
        margin-bottom: 5px;
        display: block;
    }

    .form-control {
        border: 1px solid #fff; /* Changed from red to white */
        border-radius: 4px;
        padding: 10px;
        font-size: 14px;
        width: 100%;
        margin-bottom: 15px;
        box-sizing: border-box;
        background-color: #222;
        color: #fff;
    }

    .form-control:focus {
        border-color: #fff; /* Changed from red to white */
        outline: none;
        box-shadow: 0 0 5px rgba(255, 255, 255, 0.5); /* Changed from red to white */
    }

    .btn-primary {
        background-color: #fff; /* Changed from red to white */
        border: none;
        color: #000; /* Changed text color to black for contrast */
        padding: 10px 15px;
        font-size: 16px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #ccc; /* Changed hover color to light gray */
    }

    .alert {
        padding: 10px;
        background-color: #721c24;
        color: #fff;
        border: 1px solid #f5c6cb;
        border-radius: 4px;
        margin-bottom: 15px;
    }

    .text-center {
        text-align: center;
    }

    a {
        color: #fff; /* Changed from red to white */
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    small {
        color: #ccc;
    }
</style>

<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">
            <h3 class="text-center mb-4">🔁 Mot de passe oublié ?</h3>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Votre email" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">📩 Envoyer le lien</button>
                </div>
            </form>
        </div>
    </div>
</div>