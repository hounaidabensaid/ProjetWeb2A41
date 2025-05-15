<?php
require_once('../../controller/UserController.php');
require_once('../../controller/vendor/autoload.php'); // PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$controller = new UserController();
$token = $_GET['token'] ?? null;
$error = '';
$success = '';

if (!$token) {
    $error = "❌ Token manquant.";
} else {
    $db = config::getConnexion();
    $stmt = $db->prepare("SELECT id, email FROM user WHERE reset_token = ? AND reset_token_expire > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $error = "❌ Token invalide ou expiré.";
    }

    // Si formulaire soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if (strlen($newPassword) < 6) {
            $error = "⚠️ Le mot de passe doit contenir au moins 6 caractères.";
        } elseif ($newPassword !== $confirmPassword) {
            $error = "❌ Les mots de passe ne correspondent pas.";
        } else {
            try {
                // Mise à jour du mot de passe
                $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $db->prepare("UPDATE user SET mdp = ?, reset_token = NULL, reset_token_expire = NULL WHERE id = ?");
                $stmt->execute([$hashed, $user['id']]);

                // Envoi d'un email de confirmation
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Remplacez par votre hôte SMTP
                $mail->SMTPAuth = true;
                $mail->Username = 'guellouznour1234@gmail.com'; // Remplacez par votre email
                $mail->Password = 'haoojvwqicartvbc'; // Remplacez par votre mot de passe ou mot de passe d'application
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('guellouznour1234@gmail.com', 'Support Technique');
                $mail->addAddress($user['email']);
                $mail->Subject = 'Confirmation de réinitialisation de mot de passe';
                $mail->Body = "Bonjour,\n\nVotre mot de passe a été réinitialisé avec succès.\n\nSi vous n'êtes pas à l'origine de cette action, veuillez nous contacter immédiatement.";

                $mail->send();
                $success = "✅ Mot de passe réinitialisé avec succès. Un email de confirmation a été envoyé.";
            } catch (Exception $e) {
                $error = "Erreur lors de l'envoi de l'email : " . $mail->ErrorInfo;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-success:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
<div class="card p-4">
    <h3 class="text-center mb-3">🔐 Réinitialiser le mot de passe</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if (!$success && $token && $user): ?>
    <form method="POST">
        <div class="mb-3">
            <label for="new_password" class="form-label">Nouveau mot de passe</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-success">Réinitialiser</button>
        </div>
    </form>
    <?php endif; ?>
</div>
</body>
</html>