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
    // Get form data
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $lieu = trim($_POST['lieu']);
    $date = $_POST['date'];
    
    // Initialize variables
    $imagePath = null;
    $isNewImage = false;
    
    // Handle image upload if provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../../image/";
        $baseUrlPath = "../../image/";
        
        // Create directory if needed
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        // Generate unique filename
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $imageName;
        
        // Validate file type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = $baseUrlPath . $imageName;
                $isNewImage = true;
            }
        }
    }

    // Validate required fields
    if (!empty($nom) && !empty($description) && !empty($lieu) && !empty($date)) {
        if (!empty($_POST['id_event'])) {
            // UPDATE CASE
            $id_event = intval($_POST['id_event']);
            
            // If no new image, get existing image path
            if (!$isNewImage) {
                $existingEvent = $eventModel->getById($id_event);
                $imagePath = $existingEvent['image'] ?? "";
            }
            
            // Update event (image can be null to keep existing)
            $success = $eventModel->update($id_event, $nom, $description, $lieu, $date, $imagePath);
            
            if ($success) {
                header("Location: event.php");
                exit();
            }
        } else {
            // INSERT CASE (image required)
            if (!$isNewImage) {
                die("Une image est obligatoire pour créer un nouvel événement");
            }
            
            $success = $eventModel->save($nom, $description, $lieu, $date, $imagePath);
            if ($success) {
                header("Location: event.php");
                exit();
            }
        }
    } else {
        die("Tous les champs obligatoires doivent être remplis");
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
            <li><a href="#"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="#"><i class="fas fa-car"></i> Gestion Voitures</a></li>
            <li><a href="#"><i class="fas fa-users"></i> Utilisateurs</a></li>
            <li><a href="#"><i class="fas fa-calendar-check"></i> Réservations</a></li>
            <li><a href="event.php" class="active"><i class="fas fa-calendar"></i> Événements</a></li>
            <li><a href="reservation_event.php"><i class="fas fa-calendar-plus"></i> Réservations Événement</a></li>
            <li><a href="#"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
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
                        <form method="POST" id="event-form" enctype="multipart/form-data" novalidate>
                        <input type="hidden" name="id_event" value="<?= $eventToEdit['id_event'] ?? '' ?>">
                            <?php if (!empty($eventToEdit['image'])): ?>
                                <div class="mb-3 text-center">
                                    <label class="form-label fw-bold">Image actuelle :</label><br>
                                    <img src="<?= htmlspecialchars($eventToEdit['image']) ?>" alt="Image actuelle" class="img-thumbnail" style="max-height: 200px;">
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="image" class="form-label">Image de l'Événement</label>
                                <input type="file" class="form-control" name="image" id="image">
                            </div>

                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom de l'Événement</label>
                                <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($eventToEdit['nom'] ?? '') ?>" required>
                                <div class="text-danger error-message" id="nom-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required><?= htmlspecialchars($eventToEdit['description'] ?? '') ?></textarea>
                                <div class="text-danger error-message" id="description-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="lieu" class="form-label">Lieu</label>
                                <input type="text" class="form-control" id="lieu" name="lieu" value="<?= htmlspecialchars($eventToEdit['lieu'] ?? '') ?>" required>
                                <div class="text-danger error-message" id="lieu-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars($eventToEdit['date'] ?? '') ?>" required>
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
                                            <th>Image</th>
                                            <th>Nom</th>
                                            <th>Description</th>
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
                                                <td class="align-middle">
                                                    <?php if (!empty($e['image'])): ?>
                                                        <img src="<?= htmlspecialchars($e['image']) ?>" alt="image" width="80" height="60" style="object-fit: cover; border-radius: 5px;">
                                                    <?php else: ?>
                                                        <span class="text-muted">Aucune</span>
                                                    <?php endif; ?>
                                                </td>

                                                    <td class="align-middle"><?= htmlspecialchars($e['nom']) ?></td>
                                                    <td class="align-middle"><?= htmlspecialchars($e['description']) ?></td>
                                                    <td class="align-middle"><?= htmlspecialchars($e['lieu']) ?></td>
                                                    <td class="align-middle" style="min-width:100px;"><?= htmlspecialchars($e['date']) ?></td>
                                                    <td class="action-btns d-flex align-items-center h-100" style="height:84px !important;">
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
