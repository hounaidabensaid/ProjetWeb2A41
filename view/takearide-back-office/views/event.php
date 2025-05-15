<?php
require_once '../models/Event.php';  // Assure-toi que le chemin est correct

$eventModel = new Event();

// Initialisation des variables
$events = $eventModel->getAll();
$eventToEdit = null;

// Gestion de la suppression
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($eventModel->delete($id)) {
        header("Location: event.php");  // Redirection après suppression
        exit();
    }
}

// Gestion de la récupération pour modification
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $eventToEdit = $eventModel->getById($id);
   
}

// Gestion de l'ajout ou modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $lieu = trim($_POST['lieu']);
    $date = $_POST['date'];
      $nbplace = (int)$_POST['nbplace'];
    
    // Initialize variables
    $imagePath = null;
    $isNewImage = false;

    if (!empty($nom) && !empty($description) && !empty($lieu) && !empty($date)) {
        if (!empty($_POST['id_event'])) {
            // Mise à jour
            $id_event = intval($_POST['id_event']);
            $success = $eventModel->update($id_event, $nom, $description, $lieu, $date);
            if ($success) {
                header("Location: event.php");
                exit();
            }
        } else {
            // Insertion
            $success = $eventModel->save($nom, $description, $lieu, $date);
            if ($success) {
                header("Location: event.php");
                exit();
            } 
        }
    } 
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backoffice - Gestion des Événements</title>
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
            <li><a href="../../Backoffice/dashboard_user.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="../../Backoffice/index_voiture.php"><i class="fas fa-car"></i> Gestion Voitures</a></li>
            <li><a href="../../Backoffice/index_user.php"  ><i class="fas fa-users"></i> Utilisateurs</a></li>
            <li><a href="../../Backoffice/index_reservation.php" ><i class="fas fa-calendar-check"></i> Réservations</a></li>
			            <li><a href="http://localhost/ttttttesttttt/view/takearide-back-office/views/event.php" class="active"><i class="fas fa-calendar-check"></i> Evenement</a></li>
			            <li><a href="http://localhost/ttttttesttttt/view/takearide-back-office/views/reservation_event.php"><i class="fas fa-calendar-check"></i> Réservations Evenement</a></li>

			<li><a href="../../Backoffice/dashboard.php"><i class="fas fa-calendar-check"></i> Réclamations</a></li>
			<li><a href="../../Backoffice/view_reponse.php"><i class="fas fa-calendar-check"></i> Réponses</a></li>


            <li><a href="../../frontoffice/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="mb-4">Gestion des Événements</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Événements</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <!-- Formulaire Ajout/Modification -->
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><?= isset($eventToEdit) ? 'Modifier' : 'Ajouter' ?> un Événement</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" id="event-form" novalidate>
                                <input type="hidden" name="id_event" value="<?= $eventToEdit['id_event'] ?? '' ?>">

                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom de l'Événement</label>
                                    <input type="text" class="form-control" id="nom" name="nom" value="<?= $eventToEdit['nom'] ?? '' ?>" required>
                                    <div class="text-danger error-message" id="nom-error"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" required><?= $eventToEdit['description'] ?? '' ?></textarea>
                                    <div class="text-danger error-message" id="description-error"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="lieu" class="form-label">Lieu</label>
                                    <input type="text" class="form-control" id="lieu" name="lieu" value="<?= $eventToEdit['lieu'] ?? '' ?>" required>
                                    <div class="text-danger error-message" id="lieu-error"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" value="<?= $eventToEdit['date'] ?? '' ?>" required>
                                    <div class="text-danger error-message" id="date-error"></div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save me-2"></i><?= isset($eventToEdit) ? 'Modifier' : 'Ajouter' ?>
                                </button>

                                <?php if (isset($eventToEdit)): ?>
                                    <a href="event.php" class="btn btn-outline-secondary w-100 mt-2">
                                        <i class="fas fa-times me-2"></i>Annuler
                                    </a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Liste des Événements -->
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Liste des Événements</h5>
                            <span class="badge bg-primary"><?= count($events) ?> événements</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Lieu</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($events)): ?>
                                            <tr><td colspan="4" class="text-center">Aucun événement enregistré</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($events as $e): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($e['nom']) ?></td>
                                                    <td><?= htmlspecialchars($e['lieu']) ?></td>
                                                    <td><?= htmlspecialchars($e['date']) ?></td>
                                                    <td class="action-btns">
                                                        <a href="?edit=<?= $e['id_event'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Modifier">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="?delete=<?= $e['id_event'] ?>" onclick="return confirm('Supprimer cet événement ?')" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
       
    document.getElementById('event-form').addEventListener('submit', function (e) {
        e.preventDefault();
        let error = false;

        // Récupération des champs
        const nom = document.getElementById('nom').value.trim();
        const description = document.getElementById('description').value.trim();
        const lieu = document.getElementById('lieu').value.trim();
        const date = document.getElementById('date').value;

        // Reset des messages d'erreur
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

        // Validation du Nom
        if (nom.length < 3) {
            document.getElementById('nom-error').textContent = "Le nom doit contenir au moins 3 caractères.";
            error = true;
        }

        // Validation de la Description
        if (description.length < 10) {
            document.getElementById('description-error').textContent = "La description doit contenir au moins 10 caractères.";
            error = true;
        }

        // Validation du Lieu
        if (lieu.length < 3) {
            document.getElementById('lieu-error').textContent = "Le lieu doit contenir au moins 3 caractères.";
            error = true;
        }

        // Validation de la Date
        const today = new Date().toISOString().split('T')[0];
        if (!date) {
            document.getElementById('date-error').textContent = "La date est obligatoire.";
            error = true;
        } else if (date < today) {
            document.getElementById('date-error').textContent = "La date ne peut pas être dans le passé.";
            error = true;
        }

        // Si aucune erreur, soumettre le formulaire
        if (!error) {
            e.target.submit();
        }
    });
</script>

</body>
</html>
