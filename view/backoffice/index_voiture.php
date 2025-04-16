<?php
require_once '../../config.php';
require_once '../../model/Voiture.php';
require_once '../../controller/VoitureController.php';




$controller = new VoitureController();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id_voiture'] ?? null;
        $matricule = trim($_POST['matricule']);
        $marque = trim($_POST['marque']);
        $modele = trim($_POST['modele']);
        $couleur = trim($_POST['couleur']);
        $nb_place = intval($_POST['nb_place']);
        $statut = trim($_POST['statut']);

        // Validation des données
        if (empty($matricule) || empty($marque) || empty($modele) || empty($couleur) || $nb_place <= 0) {
            throw new Exception("Tous les champs sont obligatoires");
        }

        $voiture = new Voiture($id, $matricule, $marque, $modele, $couleur, $nb_place, $statut);

        if ($id) {
            $controller->updateVoiture($voiture);
            $_SESSION['message'] = "Voiture mise à jour avec succès";
        } else {
            $controller->addVoiture($voiture);
            $_SESSION['message'] = "Voiture ajoutée avec succès";
        }
        
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Suppression
if (isset($_GET['delete'])) {
    try {
        $controller->deleteVoiture($_GET['delete']);
        $_SESSION['message'] = "Voiture supprimée avec succès";
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Pré-remplissage pour modification
$voitureToEdit = null;
if (isset($_GET['edit'])) {
    $voitureToEdit = $controller->getVoitureById($_GET['edit']);
    if (!$voitureToEdit) {
        $_SESSION['error'] = "Voiture introuvable";
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
    }
}

// Liste des voitures
$voituress = $controller->getAllVoitures();
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
            transition: all 0.3s;
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
            transition: all 0.3s;
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
            transition: all 0.3s;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border: none;
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .table th {
            background-color: var(--primary-color);
            color: white;
        }
        
        .badge-disponible {
            background-color: #28a745;
        }
        
        .badge-service {
            background-color: #ffc107;
            color: #212529;
        }
        
        .badge-reserve {
            background-color: #dc3545;
        }
        
        .action-btns .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                min-height: auto;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
		.error-message {
    color: red;
    font-size: 12px;
    margin-bottom: 5px;
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
            <li><a href="index_voiture.php" class="active"><i class="fas fa-car"></i> Gestion Voitures</a></li>
            <li><a href="#"><i class="fas fa-users"></i> Utilisateurs</a></li>
            <li><a href="#"><i class="fas fa-calendar-check"></i> Réservations</a></li>
            <li><a href="#"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="mb-0">Gestion des Voitures</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Voitures</li>
                        </ol>
                    </nav>
                </div>
            </div>

              
            <div class="row">
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><?= $voitureToEdit ? 'Modifier' : 'Ajouter' ?> une Voiture</h5>
                        </div>
                        <div class="card-body">
                           <form method="POST" id="voiture-form">
    <input type="hidden" id="id_voiture" name="id_voiture" value="<?= $voitureToEdit ? $voitureToEdit->getId() : '' ?>">

    <div class="mb-3">
        <label for="matricule" class="form-label">Matricule</label>
        <input type="text" class="form-control" id="matricule" name="matricule"
               value="<?= $voitureToEdit ? htmlspecialchars($voitureToEdit->getMatricule()) : '' ?>" required>
        <div class="text-danger error-message" id="matricule-error"></div>
    </div>

    <div class="mb-3">
        <label for="marque" class="form-label">Marque</label>
        <input type="text" class="form-control" id="marque" name="marque"
               value="<?= $voitureToEdit ? htmlspecialchars($voitureToEdit->getMarque()) : '' ?>" required>
        <div class="text-danger error-message" id="marque-error"></div>
    </div>

    <div class="mb-3">
        <label for="modele" class="form-label">Modèle</label>
        <input type="text" class="form-control" id="modele" name="modele"
               value="<?= $voitureToEdit ? htmlspecialchars($voitureToEdit->getModele()) : '' ?>" required>
        <div class="text-danger error-message" id="modele-error"></div>
    </div>

    <div class="mb-3">
        <label for="couleur" class="form-label">Couleur</label>
        <input type="text" class="form-control" id="couleur" name="couleur"
               value="<?= $voitureToEdit ? htmlspecialchars($voitureToEdit->getCouleur()) : '' ?>" required>
        <div class="text-danger error-message" id="couleur-error"></div>
    </div>

    <div class="mb-3">
        <label for="nb_place" class="form-label">Nombre de places</label>
        <input type="number" class="form-control" id="nb_place" name="nb_place" min="1"
               value="<?= $voitureToEdit ? htmlspecialchars($voitureToEdit->getNbPlace()) : '' ?>" required>
        <div class="text-danger error-message" id="nb_place-error"></div>
    </div>

    <div class="mb-3">
        <label for="statut" class="form-label">Statut</label>
        <select class="form-select" id="statut" name="statut" required>
            <option value="">-- Choisir un statut --</option>
            <option value="disponible" <?= ($voitureToEdit && $voitureToEdit->getStatut() === "disponible") ? 'selected' : '' ?>>Disponible</option>
            <option value="en service" <?= ($voitureToEdit && $voitureToEdit->getStatut() === "en service") ? 'selected' : '' ?>>En service</option>
            <option value="réservé" <?= ($voitureToEdit && $voitureToEdit->getStatut() === "réservé") ? 'selected' : '' ?>>Réservé</option>
        </select>
        <div class="text-danger error-message" id="statut-error"></div>
    </div>

    <button type="submit" class="btn btn-primary w-100">
        <i class="fas fa-save me-2"></i><?= $voitureToEdit ? 'Modifier' : 'Ajouter' ?>
    </button>

    <?php if ($voitureToEdit): ?>
        <a href="index_voiture.php" class="btn btn-outline-secondary w-100 mt-2">
            <i class="fas fa-times me-2"></i>Annuler
        </a>
    <?php endif; ?>
</form>

                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Liste des Voitures</h5>
            <span class="badge bg-primary"><?= count($voituress) ?> voitures</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Matricule</th>
                            <th>Marque</th>
                            <th>Modèle</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($voituress)): ?>
                            <tr>
                                <td colspan="5" class="text-center">Aucune voiture enregistrée</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($voituress as $voiture): ?>
                                <tr>
                                    <td><?= htmlspecialchars($voiture->getMatricule()) ?></td>
                                    <td><?= htmlspecialchars($voiture->getMarque()) ?></td>
                                    <td><?= htmlspecialchars($voiture->getModele()) ?></td>
                                    <td>
                                        <?php 
                                            $statut = $voiture->getStatut();
                                            $badgeClass = 'bg-secondary';
                                            if ($statut === 'disponible') $badgeClass = 'bg-success';
                                            elseif ($statut === 'en service') $badgeClass = 'bg-warning text-dark';
                                            elseif ($statut === 'réservé') $badgeClass = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= ucfirst($statut) ?></span>
                                    </td>
                                    <td class="action-btns">
                                       
    <a href="http://localhost/ShareRide3/ShareRide/view/backoffice/index_voiture.php?edit=<?= $voiture->getId() ?>" class="btn btn-sm btn-outline-primary me-1" title="Modifier">
        <i class="fas fa-edit"></i>
    </a>

    <!-- Bouton Supprimer avec confirmation -->
    <a href="http://localhost/ShareRide3/ShareRide/view/backoffice/index_voiture.php?delete=<?= $voiture->getId() ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette voiture ?')" class="btn btn-sm btn-outline-danger" title="Supprimer">
        <i class="fas fa-trash"></i>
    </a>

</td>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script pour gérer les messages flash et autres interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Fermer automatiquement les alertes après 5 secondes
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
            
            // Confirmation avant suppression
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Êtes-vous sûr de vouloir supprimer cette voiture ?')) {
                        e.preventDefault();
                    }
                });
            });
        });
		
		
    </script>
	
	
	<script>
    const validMarques = ['Toyota', 'Peugeot', 'Renault', 'Volkswagen', 'Fiat']; // exemple
    const validColors = ['rouge', 'noir', 'blanc', 'bleu', 'gris', 'vert']; // exemple

    document.getElementById('voiture-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const id = document.getElementById('id_voiture').value;
        const matricule = document.getElementById('matricule').value.trim();
        const marque = document.getElementById('marque').value.trim();
        const modele = document.getElementById('modele').value.trim();
        const couleur = document.getElementById('couleur').value.trim().toLowerCase();
        const nb_place = document.getElementById('nb_place').value.trim();
        const statut = document.getElementById('statut').value.trim();

        document.querySelectorAll('.error-message').forEach(function (elem) {
            elem.textContent = '';
        });

        let errorMessage = false;
        const matriculeRegex = /^\d+TN\d+$/;

        if (!matricule || !matriculeRegex.test(matricule)) {
            document.getElementById('matricule-error').textContent = "Le matricule doit être dans le format: [numéro]TN[numéro] (ex: 12345TN67890).";
            errorMessage = true;
        }

        if (!validMarques.includes(marque)) {
            document.getElementById('marque-error').textContent = `La marque doit être l'une des suivantes : ${validMarques.join(', ')}.`;
            errorMessage = true;
        }

        if (!modele || modele.length < 7) {
            document.getElementById('modele-error').textContent = "Le modèle doit contenir au moins 7 caractères.";
            errorMessage = true;
        }

        if (!validColors.includes(couleur)) {
            document.getElementById('couleur-error').textContent = `La couleur doit être l'une des suivantes : ${validColors.join(', ')}.`;
            errorMessage = true;
        }

        if (!nb_place || isNaN(nb_place) || nb_place < 2 || nb_place > 7) {
            document.getElementById('nb_place-error').textContent = "Le nombre de places doit être un nombre entre 2 et 7.";
            errorMessage = true;
        }

        if (!statut) {
            document.getElementById('statut-error').textContent = "Le statut est obligatoire.";
            errorMessage = true;
        }

        if (!errorMessage) {
            // Soumettre le formulaire en cas de succès
            e.target.submit();
        }
    });
</script>

</body>
</html>