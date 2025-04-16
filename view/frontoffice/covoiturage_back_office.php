<?php
try {
    // Connexion à la base de données
    $bdd = new PDO('mysql:host=localhost;dbname=123;charset=utf8', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête de recherche
    $searchQuery = "";
    if (isset($_POST['search']) && $_POST['search'] != "") {
        $searchQuery = '%' . $_POST['search'] . '%';
    }
    
    // Récupérer toutes les annonces, ou selon la recherche
    if ($searchQuery) {
        $stmt = $bdd->prepare('SELECT * FROM `123` WHERE nom LIKE ? OR prenom LIKE ? OR villeDepart LIKE ? OR villeArrivee LIKE ?');
        $stmt->execute([$searchQuery, $searchQuery, $searchQuery, $searchQuery]);
    } else {
        $stmt = $bdd->prepare('SELECT * FROM `123`');
        $stmt->execute();
    }

    $annonces = $stmt->fetchAll();
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back Office - Gestion des Annonces</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .header {
            background-color:rgb(73, 99, 124);
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        .search-bar {
            margin: 20px 0;
            text-align: center;
        }
        input[type="text"] {
            padding: 8px;
            font-size: 14px;
            width: 200px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color:rgb(108, 122, 138);
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gestion des Annonces de Covoiturage</h1>
        </div>
        
        <!-- Barre de recherche -->
        <div class="search-bar">
            <form method="post">
                <input type="text" name="search" value="<?= isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '' ?>" placeholder="Rechercher par nom, prénom ou ville...">
                <button type="submit">Rechercher</button>
            </form>
        </div>
        
        <!-- Tableau pour afficher les annonces -->
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Ville de départ</th>
                    <th>Ville d'arrivée</th>
                    <th>Date</th>
                    <th>Prix</th>
                    <th>Matricule</th>
                    <th>Type de véhicule</th>
                    <th>Places Disponibles</th>
                    <th>Détails</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($annonces) > 0): ?>
                    <?php foreach ($annonces as $annonce): ?>
                        <tr>
                            <td><?= htmlspecialchars($annonce['nom']) ?></td>
                            <td><?= htmlspecialchars($annonce['prenom']) ?></td>
                            <td><?= htmlspecialchars($annonce['villeDepart']) ?></td>
                            <td><?= htmlspecialchars($annonce['villeArrivee']) ?></td>
                            <td><?= htmlspecialchars($annonce['date']) ?></td>
                            <td><?= htmlspecialchars($annonce['prix']) ?>€</td>
                            <td><?= htmlspecialchars($annonce['matricule']) ?></td>
                            <td><?= htmlspecialchars($annonce['typeVehicule']) ?></td>
                            <td><?= htmlspecialchars($annonce['placesDisponibles']) ?></td>
                            <td><?= htmlspecialchars($annonce['details']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" style="text-align: center;">Aucune annonce trouvée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</body>
</html>
