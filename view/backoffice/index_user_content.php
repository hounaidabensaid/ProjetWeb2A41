<?php
// This file contains the user management content to be included inside index_voiture.php

require_once '../../config.php';
require_once '../../model/User.php';
require_once '../../controller/UserController.php';

$pdo = config::getConnexion();
$controller = new UserController();

// Traitement du formulaire POST pour l'ajout ou la modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = isset($_POST['id']) && is_numeric($_POST['id']) ? (int)$_POST['id'] : null;
        $nom = trim($_POST['nom']);
        $email = trim($_POST['email']);
        $tel = trim($_POST['tel']);
        $mdp = trim($_POST['mdp']);
        $role = trim($_POST['role']);

        if (empty($nom) || empty($email) || empty($tel) || empty($mdp) || empty($role)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        $user = new User($id, $nom, $email, $tel, password_hash($mdp, PASSWORD_DEFAULT), $role, new DateTime());

        if ($id) {
            $controller->updateUser($id, [
                'nom' => $nom,
                'email' => $email,
                'tel' => $tel,
                'mdp' => password_hash($mdp, PASSWORD_DEFAULT),
                'role' => $role
            ], $role);
            $_SESSION['message'] = "Utilisateur mis à jour avec succès.";
        } else {
            $controller->addUser($user);
            $_SESSION['message'] = "Utilisateur ajouté avec succès.";
        }

        header('Location: ' . $_SERVER['PHP_SELF'] . '?page=users');
        exit;
    } catch (Exception $e) {
        echo "<div style='color:red;'>Erreur : " . $e->getMessage() . "</div>";
    }
}

// Suppression
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $controller->deleteUser((int)$_GET['delete']);
    header('Location: ' . $_SERVER['PHP_SELF'] . '?page=users');
    exit;
}

// Bannir un utilisateur
if (isset($_GET['ban']) && is_numeric($_GET['ban'])) {
    $controller->banUser((int)$_GET['ban']);
    $_SESSION['message'] = "Utilisateur banni avec succès.";
    header('Location: ' . $_SERVER['PHP_SELF'] . '?page=users');
    exit;
}

// Débannir un utilisateur
if (isset($_GET['unban']) && is_numeric($_GET['unban'])) {
    $controller->unbanUser((int)$_GET['unban']);
    $_SESSION['message'] = "Utilisateur débanni avec succès.";
    header('Location: ' . $_SERVER['PHP_SELF'] . '?page=users');
    exit;
}

// Préremplissage pour modification
$userToEdit = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $userToEdit = $controller->showUser((int)$_GET['edit']);
}

// Liste des utilisateurs
$users = $controller->listUser();
?>

<?php if (isset($_GET['action']) && $_GET['action'] === 'add'): ?>
    <!-- Formulaire d'ajout d'utilisateur -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ajouter un Utilisateur</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Nom</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Téléphone</label>
                            <input type="text" name="tel" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Mot de passe</label>
                            <input type="password" name="mdp" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Rôle</label>
                            <select name="role" class="form-select" required>
                                <option value="client">Client</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- Liste des utilisateurs -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-4">Gestion des Utilisateurs</h2>
            <div class="input-group w-50">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="searchUser" class="form-control" placeholder="Rechercher un utilisateur...">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Liste des Utilisateurs</h5>
                    <div>
                        <a href="pdf.php" class="btn btn-primary btn-sm">📄 Télécharger PDF</a>
                        <a href="?page=users&action=add" class="btn btn-success btn-sm">➕ Ajouter un Utilisateur</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Tél</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><?= htmlspecialchars($u['nom']) ?></td>
                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                    <td><?= htmlspecialchars($u['tel']) ?></td>
                                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($u['role']) ?></span></td>
                                    <td>
                                        <span class="badge <?= $u['status'] === 'active' ? 'bg-success' : 'bg-danger' ?>">
                                            <?= htmlspecialchars($u['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="?page=users&edit=<?= $u['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                                        <a href="?page=users&delete=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                                        <?php if ($u['status'] === 'active'): ?>
                                            <a href="?page=users&ban=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bannir cet utilisateur ?')">Ban</a>
                                        <?php else: ?>
                                            <a href="?page=users&unban=<?= $u['id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Débannir cet utilisateur ?')">Unban</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
document.getElementById('searchUser').addEventListener('input', function() {
    const query = this.value;

    // Envoyer une requête AJAX
    fetch('search_user.php?query=' + encodeURIComponent(query))
        .then(response => response.text())
        .then(data => {
            document.querySelector('tbody').innerHTML = data;
        })
        .catch(error => console.error('Erreur:', error));
});
</script>
