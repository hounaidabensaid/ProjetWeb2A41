<?php
session_start();
require_once __DIR__.'/../../config.php';
require_once __DIR__.'/../../controller/CovoiturageController.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Log full request URI and parameters for debugging without breaking layout
    error_log('Request URI: ' . $_SERVER['REQUEST_URI']);
    error_log('GET parameters: ' . print_r($_GET, true));
    die("ID d'annonce invalide ou manquant");
}

$id_annonce = (int)$_GET['id'];

try {
    $pdo = Config::getConnexion();
    $controller = new CovoiturageController($pdo);

    $annonce = $controller->showAnnonceById($id_annonce);
    if (!$annonce) {
        die("Annonce non trouvée");
    }

    $demandes = $controller->getDemandesForAnnonce($id_annonce);
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Demandes de covoiturage - Backoffice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 2rem;
            background-color: #f8f9fa;
        }
        h1 {
            color: #b30000;
            margin-bottom: 1rem;
        }
        .container {
            background: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            padding: 0.75rem;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #b30000;
            color: white;
        }
        a.back-link {
            display: inline-block;
            margin-top: 1rem;
            color: #b30000;
            text-decoration: none;
            font-weight: bold;
        }
        a.back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Demandes pour l'annonce: <?= htmlspecialchars($annonce['villeDepart']) ?> → <?= htmlspecialchars($annonce['villeArrivee']) ?> le <?= htmlspecialchars($annonce['date']) ?></h1>
        <?php if (count($demandes) > 0): ?>
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
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune demande pour cette annonce.</p>
        <?php endif; ?>
        <a href="index_voiture1.php" class="back-link">← Retour à la liste des annonces</a>
    </div>
</body>
</html>
