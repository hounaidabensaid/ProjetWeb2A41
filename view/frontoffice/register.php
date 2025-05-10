<?php
require_once('../../controller/UserController.php');
require_once('../../model/User.php');
require_once('../../controller/vendor/autoload.php'); // Charger PHPMailer via Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $tel = trim($_POST['tel']);
    $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
    $role = 'client';
    $date_creation = new DateTime();

    $controller = new UserController();

    // Vérification si l'email existe déjà
    $existingUser = $controller->getUserByEmail($email);
    if ($existingUser) {
        // Envoi d'un email à l'utilisateur avec PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuration du serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Remplacez par votre serveur SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@gmail.com'; // Remplacez par votre email
            $mail->Password = 'your_password'; // Remplacez par votre mot de passe ou mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Débogage (facultatif)
            $mail->SMTPDebug = 0; // 0 pour désactiver le débogage, 2 pour des informations détaillées
            $mail->Debugoutput = 'html';

            // Destinataire
            $mail->setFrom('your_email@gmail.com', 'Support ProjetWeb');
            $mail->addAddress($email, $nom);

            // Contenu de l'email
            $mail->isHTML(true);
            $mail->Subject = 'Compte déjà existant';
            $mail->Body = "Bonjour $nom,<br><br>Il semble que vous ayez déjà un compte avec cet email. Cliquez sur le lien ci-dessous pour vous connecter :<br><br>";
            $mail->Body .= "<a href='http://localhost/projetweb/view/frontoffice/login.php'>Se connecter</a><br><br>";
            $mail->Body .= "Cordialement,<br>L'équipe de support.";

            $mail->send();
            $_SESSION['message'] = "Un email vous a été envoyé avec un lien pour vous connecter.";
        } catch (Exception $e) {
            $_SESSION['message'] = "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
        }

        header('Location: register.php');
        exit;
    }

    // Gestion de l'upload de la photo
    $photo = 'default.png'; // Valeur par défaut si aucune photo n'est fournie
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoDir = '../../uploads/photos/';
        if (!is_dir($photoDir)) {
            mkdir($photoDir, 0777, true);
        }
        $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
        $photoPath = $photoDir . $photoName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
            $photo = $photoName;
        }
    }

    // Création de l'utilisateur
    $user = new User(null, $nom, $email, $tel, $mdp, $role, $date_creation, $photo);
    $controller->addUser($user);

    $_SESSION['message'] = "Inscription réussie. Vous pouvez maintenant vous connecter.";
    header('Location: login.php');
    exit;
}
// include 'header.php';
?>

<style>
    body {
        background: url('../../view/frontoffice/img/p3.jpg') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        color: #fff;
    }

    .container {
        max-width: 600px;
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
        color: #ff0000;
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
        border: 1px solid #ff0000;
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
        border-color: #ff0000;
        outline: none;
        box-shadow: 0 0 5px rgba(255, 0, 0, 0.5);
    }

    .btn-success {
        background-color: #ff0000;
        border: none;
        color: #fff;
        padding: 10px 15px;
        font-size: 16px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-success:hover {
        background-color: #cc0000;
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
        color: #ff0000;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    small {
        color: #ccc;
    }
</style>
<div class="container py-5">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h3 class="mb-4 text-center">Créer un compte</h3>
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-info">
                            <?= htmlspecialchars($_SESSION['message']) ?>
                        </div>
                        <?php unset($_SESSION['message']); ?>
                    <?php endif; ?>
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom complet</label>
                            <input type="text" class="form-control" name="nom" id="nom" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse e-mail</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="tel" class="form-label">Téléphone</label>
                            <input type="text" class="form-control" name="tel" id="tel" required>
                        </div>
                        <div class="mb-3">
                            <label for="mdp" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" name="mdp" id="mdp" required>
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo de profil</label>
                            <input type="file" class="form-control" name="photo" id="photo" accept="image/*">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">S'inscrire</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <small>Déjà inscrit ? <a href="login.php">Se connecter</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>