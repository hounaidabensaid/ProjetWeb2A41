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
    <title>Backoffice - Gestion des Voitures</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #b30000;
            --primary-dark: #800000;
            --primary-light: #990000;
            --secondary-color: #f8f9fa;
            --text-color: #333;
            --light-gray: #f5f5f5;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-color);
        }
        .sidebar {
            width: 250px;
            background-color: var(--primary-color);
            color: white;
            min-height: 100vh;
            position: fixed;
        }
        .sidebar-header {
            padding: 20px;
            background-color: var(--primary-dark);
        }
        .sidebar-menu {
            padding: 0;
            list-style: none;
        }
        .sidebar-menu li a {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
        }
        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background-color: var(--primary-dark);
        }
        .sidebar-menu li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .card-header {
            background-color: var(--primary-color);
            color: white;
        }
        .table th {
            background-color: var(--primary-color);
            color: white;
        }
        input[type="text"] {
            padding: 8px;
            font-size: 14px;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button[type="submit"] {
            padding: 8px 16px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h3 class="text-center">Admin Panel</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="#"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="#"><i class="fas fa-users"></i> Utilisateurs</a></li>
            <li><a href="index_voiture.php" class="active"><i class="fas fa-car"></i> Gestion covoiturage</a></li>
            <li><a href="#"><i class="fas fa-calendar-check"></i> Réservations</a></li>
            <li><a href="#"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="mb-0">Gestion covoiturage</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Listes des annonces</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="search-bar mb-3">
                <form method="post" class="d-flex">
                    <input type="text" id="searchInput" name="search" value="<?= isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '' ?>" placeholder="Rechercher..." class="form-control" />
                    <button type="submit" class="ms-2 btn btn-primary">Rechercher</button>
                </form>
            </div>
            <div id="annoncesTableWrapper">
                <table class="table table-bordered table-hover">
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
                            <th>Places</th>
                            <th>Détails</th>
                            <th>Demandes</th>
                        </tr>
                    </thead>
                    <tbody id="annoncesTableBody">
                        <?php if (count($annonces) > 0): ?>
                            <?php foreach ($annonces as $annonce): ?>
                                <tr>
                                    <td><?= htmlspecialchars($annonce['nom']) ?></td>
                                    <td><?= htmlspecialchars($annonce['prenom']) ?></td>
                                    <td><?= htmlspecialchars($annonce['villeDepart']) ?></td>
                                    <td><?= htmlspecialchars($annonce['villeArrivee']) ?></td>
                                    <td><?= htmlspecialchars($annonce['date']) ?></td>
                                    <td><?= htmlspecialchars($annonce['prix']) ?> D</td>
                                    <td><?= htmlspecialchars($annonce['matricule']) ?></td>
                                    <td><?= htmlspecialchars($annonce['typeVehicule']) ?></td>
                                    <td><?= htmlspecialchars($annonce['placesDisponibles']) ?></td>
                                    <td><?= htmlspecialchars($annonce['details']) ?></td>
                                    <td><a href="demandes_par_annonce.php?id=<?= htmlspecialchars($annonce['id']) ?>" class="btn btn-primary btn-sm">Voir demandes</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="11" class="text-center">Aucune annonce trouvée.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive">
                <!-- Removed duplicate table display -->
            </div>
        </div>
    </div>
</body>
</html>
