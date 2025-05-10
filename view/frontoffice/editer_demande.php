<?php
session_start();
require_once __DIR__.'/../../config.php';
require_once __DIR__.'/../../controller/CovoiturageController.php';

$pdo = Config::getConnexion();
$controller = new CovoiturageController($pdo);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de demande invalide ou manquant");
}

$id_demande = (int)$_GET['id'];
$annonce_id = isset($_GET['annonce_id']) && is_numeric($_GET['annonce_id']) ? (int)$_GET['annonce_id'] : null;

try {
    $demandes = $controller->getDemandesForAnnonce($annonce_id);
    $demande = null;
    foreach ($demandes as $d) {
        if ($d['id_demande'] == $id_demande) {
            $demande = $d;
            break;
        }
    }
    if (!$demande) {
        die("Demande non trouvée");
    }
} catch (Exception $e) {
    die("Erreur lors de la récupération de la demande : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Modifier la demande de covoiturage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 2rem;
            color: #333;
        }
        form {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-top: 1rem;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"] {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.25rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            margin-top: 1.5rem;
            background-color: #8B0000;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #a30000;
        }
        a.back-link {
            display: block;
            margin: 1rem auto;
            max-width: 500px;
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
    <h1>Modifier la demande de covoiturage</h1>
    <form method="POST" action="traitement_demande_edit.php">
        <input type="hidden" name="id_demande" value="<?= htmlspecialchars($demande['id_demande']) ?>">
        <input type="hidden" name="id_annonce" value="<?= htmlspecialchars($annonce_id) ?>">

        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($demande['nom']) ?>" required>

        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($demande['prenom']) ?>" required>

        <label for="telephone">Téléphone</label>
        <input type="text" id="telephone" name="telephone" value="<?= htmlspecialchars($demande['telephone']) ?>" required pattern="[0-9+]{8,15}" title="8 à 15 chiffres">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($demande['email']) ?>" required>

        <label for="places">Places demandées</label>
        <input type="number" id="places" name="places" value="<?= htmlspecialchars($demande['places']) ?>" min="1" max="8" required>

        <button type="submit" name="action" value="edit_demande">Modifier</button>
    </form>
<a href="covoiturage.php?page=demander" class="back-link">← Retour aux demandes</a>
</body>
</html>
