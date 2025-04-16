<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion Événements & Participants</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-4">
    <h1 class="mb-4">Application de Gestion</h1>

    <?php
    // Si l'utilisateur soumet le formulaire de login
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($username === 'admin' && $password === 'admin') {
            $_SESSION['admin'] = true;
            echo "<div class='alert alert-success'>Connexion réussie !</div>";
        } else {
            echo "<div class='alert alert-danger'>Identifiants incorrects</div>";
        }
    }

    // Si l'utilisateur veut se déconnecter
    if (isset($_GET['action']) && $_GET['action'] === 'logout') {
        session_destroy();
        header("Location: index.php");
        exit;
    }

    // Si non connecté, afficher le formulaire de login
    if (!isset($_SESSION['admin'])) {
        ?>
        <form method="POST" class="card p-4">
            <h2 class="mb-3">Connexion Admin</h2>
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control" name="username" id="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
        <?php
        exit; // Arrêter ici si pas connecté
    }

    // Connecté => afficher bouton de déconnexion
    echo '<div class="mb-3 text-end"><a href="?action=logout" class="btn btn-danger">Déconnexion</a></div>';

    // Inclure les contrôleurs
    require_once 'controllers/EventController.php';
    require_once 'controllers/ParticipantController.php';

    $controller = new EventController();
    $participantController = new ParticipantController();

    $action = $_GET['action'] ?? 'list';
    $actionHandled = false;

    // echo "<pre>"; print_r($_POST); echo "</pre>";

    // Gestion des événements
    switch ($action) {
        case 'add':
            echo "<div class='alert alert-info'>Appel de showAddForm()</div>";
            $controller->showAddForm();
            $actionHandled = true;
            break;
        case 'save':
            echo "<div class='alert alert-info'>Appel de save()</div>";
            $controller->save();
            echo "<div class='alert alert-success'>Fin de save()</div>";
            $actionHandled = true;
            break;
        case 'list':
            $controller->index();
            $actionHandled = true;
            break;
        case 'edit':
            echo "<div class='alert alert-info'>Appel de edit()</div>";
            $controller->edit();
            $actionHandled = true;
            break;
        case 'update':
            echo "<div class='alert alert-info'>Appel de update()</div>";
            $controller->update();
            $actionHandled = true;
            break;
        case 'delete':
            $controller->delete();
            $actionHandled = true;
            break;
    }

    // Gestion des participants
    switch ($action) {
        case 'addParticipant':
            $participantController->showAddForm();
            $actionHandled = true;
            break;
        case 'saveParticipant':
            $participantController->save();
            $actionHandled = true;
            break;
        case 'listParticipants':
            $participantController->index();
            $actionHandled = true;
            break;
        case 'editParticipant':
            $participantController->edit();
            $actionHandled = true;
            break;
        case 'updateParticipant':
            $participantController->update();
            $actionHandled = true;
            break;
        case 'deleteParticipant':
            $participantController->delete();
            $actionHandled = true;
            break;
    }
    ?>
</div>

</body>
</html>
