<?php
require_once '../../config.php';
require_once '../../controller/UserController.php';

$pdo = config::getConnexion();
$controller = new UserController();

$query = $_GET['query'] ?? '';

if (!empty($query)) {
    // Recherche par nom ou numéro de téléphone
    $stmt = $pdo->prepare("SELECT * FROM user WHERE nom LIKE :query OR tel LIKE :query");
    $stmt->execute(['query' => '%' . $query . '%']);
    $users = $stmt->fetchAll();

    foreach ($users as $u) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($u['nom']) . '</td>';
        echo '<td>' . htmlspecialchars($u['email']) . '</td>';
        echo '<td>' . htmlspecialchars($u['tel']) . '</td>';
        echo '<td><span class="badge bg-info text-dark">' . htmlspecialchars($u['role']) . '</span></td>';
        echo '<td>';
        echo '<a href="?edit=' . $u['id'] . '" class="btn btn-sm btn-warning">Modifier</a> ';
        echo '<a href="?delete=' . $u['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Supprimer cet utilisateur ?\')">Supprimer</a>';
        echo '</td>';
        echo '</tr>';
    }
}
?>