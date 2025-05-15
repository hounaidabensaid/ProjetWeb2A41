<!-- filepath: c:\xampp\htdocs\projetweb\view\frontoffice\modifier.php -->
<?php
session_start();
require_once('../../controller/UserController.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$controller = new UserController();
$user = $controller->showUser($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $tel = trim($_POST['tel']);
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
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

    // Gestion du changement de mot de passe
    if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword)) {
        if (empty($currentPassword)) {
            $errors[] = "Le mot de passe actuel est obligatoire pour changer le mot de passe.";
        } elseif (!password_verify($currentPassword, $user['mdp'])) {
            $errors[] = "Le mot de passe actuel est incorrect.";
        }

        if (empty($newPassword)) {
            $errors[] = "Le nouveau mot de passe est obligatoire.";
        } elseif ($newPassword !== $confirmPassword) {
            $errors[] = "Le nouveau mot de passe et la confirmation ne correspondent pas.";
        }
    }

    // Gestion de l'upload de la photo
    $photo = $user['photo']; // Conserver l'ancienne photo par défaut
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoDir = '../../uploads/photos/';
        if (!is_dir($photoDir)) {
            mkdir($photoDir, 0777, true);
        }
        $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
        $photoPath = $photoDir . $photoName;

        // Vérification du type de fichier
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array(mime_content_type($_FILES['photo']['tmp_name']), $allowedTypes)) {
            $errors[] = "Le fichier doit être une image (JPEG, PNG ou GIF).";
        } else {
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
                $photo = $photoName;
            } else {
                $errors[] = "Erreur lors du téléchargement de la photo.";
            }
        }
    }

    // Si aucune erreur, mise à jour des informations
    if (empty($errors)) {
        $updateData = [
            'nom' => $nom,
            'email' => $email,
            'tel' => $tel,
            'photo' => $photo // Inclure la photo si elle est téléchargée
        ];

        // Ajouter le nouveau mot de passe si fourni
        if (!empty($newPassword)) {
            $updateData['mdp'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $controller->updateUser($_SESSION['user_id'], $updateData, 'client'); // Rôle actuel de l'utilisateur

        $_SESSION['message'] = "Profil mis à jour avec succès.";
        header('Location: profil.php');
        exit;
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

                    <form method="POST" enctype="multipart/form-data">
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
                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo de profil</label>
                            <input type="file" class="form-control" name="photo" id="photo" accept="image/*">
                        </div>
                        <hr>
                        <h5>Changer le mot de passe</h5>
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                            <input type="password" class="form-control" name="current_password" id="current_password">
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control" name="new_password" id="new_password">
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <a href="profil.php" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>