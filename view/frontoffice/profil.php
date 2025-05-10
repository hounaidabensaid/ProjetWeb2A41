<?php
session_start();
require_once('../../controller/UserController.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$controller = new UserController();
$user = $controller->showUser($_SESSION['user_id']);

// Exemple de notifications (vous pouvez les récupérer depuis une base de données)
$notifications = [
    "Votre paiement de 50€ a été accepté.",
    "Votre mot de passe a été modifié avec succès.",
    "Une nouvelle mise à jour est disponible.",
];

include 'header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body text-center">
                    <h3 class="mb-4">Profil de <?= htmlspecialchars($user['nom']) ?></h3>
                    <img src="../../uploads/photos/<?= htmlspecialchars($user['photo'] ?? 'default.png') ?>" alt="Photo de profil" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px;">
                    <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
                    <p><strong>Téléphone :</strong> <?= htmlspecialchars($user['tel']) ?></p>
                    <a href="modifier.php" class="btn btn-primary mb-3">Modifier le profil</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>