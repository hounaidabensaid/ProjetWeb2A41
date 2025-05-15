<?php
session_start();
require_once('../../controller/UserController.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$controller = new UserController();
$user = $controller->showUser($_SESSION['user_id']);
$message = ''; // Variable pour stocker le message de confirmation

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $tel = trim($_POST['tel']);
    $errors = [];

    // Validation des champs
    if (empty($nom)) {
        $errors[] = "Le nom est obligatoire.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Une adresse e-mail valide est obligatoire.";
    }
    if (empty($tel) || !preg_match('/^[0-9]{8,15}$/', $tel)) {
        $errors[] = "Un numéro de téléphone valide est obligatoire.";
    }

    // Si aucune erreur, mise à jour des informations
    if (empty($errors)) {
        $updateData = [
            'nom' => $nom,
            'email' => $email,
            'tel' => $tel
        ];

        $controller->updateUser($_SESSION['user_id'], $updateData, 'client'); // Rôle actuel de l'utilisateur

        $message = "Le profil a été modifié avec succès."; // Message de confirmation
    }
}

include 'header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body">
                    <h3 class="mb-4 text-center">Modifier le profil</h3>

                    <!-- Affichage des erreurs -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Affichage du message de confirmation -->
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom complet</label>
                            <input type="text" class="form-control" name="nom" id="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse e-mail</label>
                            <input type="email" class="form-control" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="tel" class="form-label">Téléphone</label>
                            <input type="text" class="form-control" name="tel" id="tel" value="<?= htmlspecialchars($user['tel']) ?>" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <a href="profil.php" class="btn btn-outline-secondary">Annuler</a>
                        <a href="index_user.php" class="btn btn-outline-primary">Retour à la liste</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>