<?php
session_start();
require_once __DIR__.'/../../config.php';
require_once __DIR__.'/../../controller/CovoiturageController.php';

$pdo = Config::getConnexion();
$controller = new CovoiturageController($pdo);

if (!isset($_GET['annonce_id']) || !is_numeric($_GET['annonce_id'])) {
    die("ID d'annonce invalide ou manquant");
}

$annonce_id = (int)$_GET['annonce_id'];

try {
    $demandes = $controller->getDemandesForAnnonce($annonce_id);
} catch (Exception $e) {
    die("Erreur lors de la récupération des demandes : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Voir les demandes de covoiturage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 2rem;
            color: #333;
        }
        h1 {
            text-align: center;
            margin-bottom: 2rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
            max-width: 900px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #8B0000;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .no-data {
            text-align: center;
            padding: 2rem;
            font-size: 1.2rem;
            color: #666;
        }
        a.back-link {
            display: block;
            margin: 1rem auto;
            max-width: 900px;
            text-align: center;
            text-decoration: none;
            color: #8B0000;
            font-weight: bold;
        }
        a.back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Demandes de covoiturage</h1>

    <?php if (empty($demandes)): ?>
        <div class="no-data">Aucune demande pour cette annonce.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Places demandées</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($demandes as $demande): ?>
                    <tr>
                        <td><?= htmlspecialchars($demande['nom']) ?></td>
                        <td><?= htmlspecialchars($demande['prenom']) ?></td>
                        <td><?= htmlspecialchars($demande['telephone']) ?></td>
                        <td><?= htmlspecialchars($demande['email']) ?></td>
                        <td><?= htmlspecialchars($demande['places']) ?></td>
                        <td>
                            <a href="editer_demande.php?id=<?= $demande['id_demande'] ?>&annonce_id=<?= $annonce_id ?>" class="btn-edit">Modifier</a>
                            <form method="POST" action="traitement_demande_delete.php" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?');">
                                <input type="hidden" name="id_demande" value="<?= $demande['id_demande'] ?>">
                                <input type="hidden" name="annonce_id" value="<?= $annonce_id ?>">
                                <input type="hidden" name="action" value="delete_demande">
                                <button type="submit" class="btn-delete">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="covoiturage.php" class="back-link">← Retour aux annonces</a>
</body>
</html>
