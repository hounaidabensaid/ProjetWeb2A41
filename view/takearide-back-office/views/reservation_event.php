<?php
require_once '../controllers/ReservationEventController.php';
require_once '../controllers/EventController.php';
require_once '../controllers/UserController.php';

// Instanciation des contrôleurs
$reservationCtrl = new ReservationEventController();
$eventCtrl = new EventController();
$userCtrl = new UserController();

// Récupération des données pour la vue
$reservations = $reservationCtrl->getAllReservations();
$events = $eventCtrl->getAllEvents();
$users = $userCtrl->getAllUsers();
// Vérifier si on est en mode modification
$reservationToEdit = null;

if (isset($_GET['edit'])) {
    $reservationToEdit = $reservationCtrl->getReservationById(intval($_GET['edit']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle Add/Update Reservation
    if (isset($_POST['id_event'], $_POST['id_participant'])) {
        if (!empty($_POST['id_reservation'])) {
            $reservationCtrl->updateReservation($_POST['id_reservation'], $_POST['id_event'], $_POST['id_participant']);
        } else {
            $reservationCtrl->addReservation($_POST['id_event'], $_POST['id_participant']);
        }
        header("Location: reservation_event.php");
        exit();
    }
    // Handle Status Update
    elseif (isset($_POST['id_reservation'], $_POST['new_status'])) {
        try {
            $reservationCtrl->updateStatus($_POST['id_reservation'], $_POST['new_status']);
            header("Location: reservation_event.php");
            exit();
        } catch (Exception $e) {
            $error = "Erreur : " . $e->getMessage();
        }
    }
}


// Gestion de la suppression
if (isset($_GET['delete'])) {
    $reservationCtrl->deleteReservation(intval($_GET['delete']));
    header("Location: reservation_event.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backoffice - Gestion des Réservations Événements</title>
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
			            <li><a href="http://localhost/ttttttesttttt/view/takearide-back-office/views/event.php"><i class="fas fa-calendar-check"></i> Evenement</a></li>
			            <li><a href="http://localhost/ttttttesttttt/view/takearide-back-office/views/reservation_event.php"  class="active"><i class="fas fa-calendar-check"></i> Réservation Evenement</a></li>

			<li><a href="../../Backoffice/dashboard.php"><i class="fas fa-calendar-check"></i> Réclamations</a></li>
			<li><a href="../../Backoffice/view_reponse.php"><i class="fas fa-calendar-check"></i> Réponses</a></li>


            <li><a href="../../frontoffice/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="mb-0">Gestion des Réservations Événements</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Réservations Événements</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <!-- Formulaire -->
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Ajouter une Réservation</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" id="reservation-event-form" novalidate>
    <?php if ($reservationToEdit): ?>
        <input type="hidden" name="id_reservation" value="<?= $reservationToEdit['id_reservation'] ?>">
    <?php endif; ?>

    <div class="mb-3">
        <label for="id_event" class="form-label">Événement</label>
        <select class="form-select" id="id_event" name="id_event" required>
            <option value="">-- Sélectionner un événement --</option>
            <?php foreach ($events as $event): ?>
                <option value="<?= $event['id_event'] ?>"
                    <?= ($reservationToEdit && $reservationToEdit['id_event'] == $event['id_event']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($event['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="id_participant" class="form-label">Participant (Utilisateur)</label>
        <select class="form-select" id="id_participant" name="id_participant" required>
            <option value="">-- Sélectionner un participant --</option>
            <?php foreach ($users as $user): ?>
                <option value="<?= $user['id'] ?>"
                    <?= ($reservationToEdit && $reservationToEdit['id_participant'] == $user['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($user['nom']) ?> <?= htmlspecialchars($user['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary w-100">
        <i class="fas fa-save me-2"></i><?= $reservationToEdit ? 'Modifier' : 'Ajouter' ?>
    </button>

    <?php if ($reservationToEdit): ?>
        <a href="reservation_event.php" class="btn btn-outline-secondary w-100 mt-2">
            <i class="fas fa-times me-2"></i>Annuler
        </a>
    <?php endif; ?>
</form>

                        </div>
                    </div>
                </div>

                <!-- Liste des Réservations -->
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Liste des Réservations</h5>
                            <span class="badge bg-primary"><?= count($reservations) ?> réservations</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Événement</th>
                                            <th>Participant</th>
                                            <th>Date Réservation</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($reservations)): ?>
                                            <tr><td colspan="4" class="text-center">Aucune réservation trouvée</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($reservations as $res): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($res['nom_event']) ?></td>
                                                    <td><?= htmlspecialchars($res['nom'])  ?></td>
                                                    <td><?= htmlspecialchars($res['date_reservation']) ?></td>
                                                    <td>
                                                        <?php if ($res['status'] === 'pending') : ?>
                                                            <div class="d-flex gap-2">
                                                                <form method="POST" action="reservation_event.php" class="flex-grow-1">
                                                                    <input type="hidden" name="id_reservation" value="<?= $res['id_reservation'] ?>">
                                                                    <input type="hidden" name="new_status" value="approved">
                                                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                                                        <i class="fas fa-check-circle me-1"></i>Approuver
                                                                    </button>
                                                                </form>
                                                                <form method="POST" action="reservation_event.php" class="flex-grow-1">
                                                                    <input type="hidden" name="id_reservation" value="<?= $res['id_reservation'] ?>">
                                                                    <input type="hidden" name="new_status" value="declined">
                                                                    <button type="submit" class="btn btn-danger btn-sm w-100">
                                                                        <i class="fas fa-times-circle me-1"></i>Refuser
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        <?php elseif ($res['status'] === 'approved') : ?>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <span class="badge bg-success bg-opacity-25 text-success">
                                                                    <i class="fas fa-check me-1"></i>Approuvée
                                                                </span>
                                                                <form method="POST" action="reservation_event.php" class="ms-auto">
                                                                    <input type="hidden" name="id_reservation" value="<?= $res['id_reservation'] ?>">
                                                                    <input type="hidden" name="new_status" value="cancelled">
                                                                    <button type="submit" class="btn btn-outline-warning btn-sm">
                                                                        <i class="fas fa-ban me-1"></i>Annuler
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        <?php elseif ($res['status'] === 'declined') : ?>
                                                            <span class="badge bg-danger bg-opacity-25 text-danger">
                                                                <i class="fas fa-times me-1"></i>Refusée
                                                            </span>
                                                        <?php elseif ($res['status'] === 'cancelled') : ?>
                                                            <span class="badge bg-secondary bg-opacity-25 text-secondary">
                                                                <i class="fas fa-ban me-1"></i>Annulée
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>

                                                    <td class="action-btns">
                                                        <a href="?delete=<?= $res['id_reservation'] ?>" onclick="return confirm('Confirmer la suppression ?')" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash"></i>
															  <a href="?edit=<?= $res['id_reservation'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Modifier">
															<i class="fas fa-edit"></i>
															</a>
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
</body>
</html>
