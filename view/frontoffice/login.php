<?php
session_start();
require_once('../../controller/UserController.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

    $controller = new UserController();
    $user = $controller->getUserByEmail($email);

    if ($user) {
        // Check if the user is banned
        if ($user->getStatus() === 'banned') {
            $error = "Votre compte a été banni. Veuillez contacter l'administrateur.";
        } elseif (password_verify($mdp, $user->getMdp())) {
            // Set session variables
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['role'] = $user->getRole();
            $_SESSION['nom'] = $user->getNom();

            // Redirect based on role
            if ($user->getRole() === 'admin') {
                header('Location: ../backoffice/dashboard_user.php');
            } else {
                header('Location: acceuil.php');
            }
            exit;
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    } else {
        $error = "Aucun utilisateur trouvé avec cet email.";
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
            <h3 class="text-center mb-4">🔐 Connexion</h3>

            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Entrez votre email" required>
                </div>

                <div class="mb-3">
                    <label>Mot de passe</label>
                    <input type="password" name="mdp" class="form-control" placeholder="Mot de passe" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </div>
            </form>

            <div class="mt-3 text-center">
                <a href="reset_password.php">🔁 Mot de passe oublié ?</a><br>
                <a href="register.php" class="text-muted">👤 Pas encore inscrit ? Créer un compte</a>
            </div>
        </div>
    </div>
</div>






