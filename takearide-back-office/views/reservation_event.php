<?php
require_once '../controllers/ReservationEventController.php';
require_once '../controllers/EventController.php';
require_once '../controllers/UserController.php';

// Instanciation des contrôleurs
$reservationCtrl = new ReservationEventController();
$eventCtrl = new EventController();
$userCtrl = new UserController();

// Récupération des données
$reservations = $reservationCtrl->getAllReservations();
$events = $eventCtrl->getAllEvents();
$users = $userCtrl->getAllUsers();
$reservationToEdit = null;

// Mode modification
if (isset($_GET['edit'])) {
    $reservationToEdit = $reservationCtrl->getReservationById(intval($_GET['edit']));
}

// Traitement POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_event'], $_POST['id_participant'])) {
        if (!empty($_POST['id_reservation'])) {
            $reservationCtrl->updateReservation($_POST['id_reservation'], $_POST['id_event'], $_POST['id_participant']);
        } else {
            $reservationCtrl->addReservation($_POST['id_event'], $_POST['id_participant']);
        }
        header("Location: reservation_event.php");
        exit();
    } elseif (isset($_POST['id_reservation'], $_POST['new_status'])) {
        try {
            $reservationCtrl->updateStatus($_POST['id_reservation'], $_POST['new_status']);
            header("Location: reservation_event.php");
            exit();
        } catch (Exception $e) {
            $error = "Erreur : " . $e->getMessage();
        }
    }
}

// Suppression
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
    <link href="../css/registration.css" rel="stylesheet">
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
        <li><a href="event.php"><i class="fas fa-calendar"></i> Événements</a></li>
        <li><a href="reservation_event.php" class="active"><i class="fas fa-calendar-plus"></i> Réservations Événement</a></li>
        <li><a href="#"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="container-fluid">

        <!-- Titre et breadcrumb -->
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
                        <h5 class="mb-0"><?= $reservationToEdit ? 'Modifier' : 'Ajouter' ?> une Réservation</h5>
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
                                        <option value="<?= $event['id_event'] ?>" <?= ($reservationToEdit && $reservationToEdit['id_event'] == $event['id_event']) ? 'selected' : '' ?>>
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
                                        <option value="<?= $user['id'] ?>" <?= ($reservationToEdit && $reservationToEdit['id_participant'] == $user['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($user['nom']) ?> <?= htmlspecialchars($user['prenom']) ?>
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

                <!-- Graphique -->
                <div class="card mt-4">
                    <?php
                        $statCounts = ['pending' => 0, 'approved' => 0, 'declined' => 0, 'cancelled' => 0];
                        foreach ($reservations as $res) {
                            if (isset($statCounts[$res['status']])) {
                                $statCounts[$res['status']]++;
                            }
                        }

                        $total = array_sum($statCounts) ?: 1;
                        $start = 0;
                        $segments = [];

                        foreach ($statCounts as $status => $count) {
                            $angle = round(($count / $total) * 360);
                            $segments[] = [
                                'start' => $start,
                                'end' => $start + $angle,
                                'color' => match($status) {
                                    'pending' => '#f0ad4e',
                                    'approved' => '#5cb85c',
                                    'declined' => '#d9534f',
                                    'cancelled' => '#6c757d',
                                },
                                'label' => ucfirst($status),
                                'count' => $count
                            ];
                            $start += $angle;
                        }

                        $chartGradient = implode(', ', array_map(fn($s) => "{$s['color']} {$s['start']}deg {$s['end']}deg", $segments));
                    ?>
                    <div class="pie-chart-container p-3">
                        <h5 class="text-center mb-3 " style="font-weight: 600;">Répartition des Réservations</h5>
                        <div class="pie-chart mx-auto" style="--chart: <?= $chartGradient ?>;"></div>
                        <ul class="pie-legend list-unstyled mt-3">
                            <?php foreach ($segments as $seg): ?>
                                <li><span class="dot" style="background:<?= $seg['color'] ?>;"></span><?= $seg['label'] ?> (<?= $seg['count'] ?>)</li>
                            <?php endforeach; ?>
                        </ul>
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
                                        <tr><td colspan="5" class="text-center">Aucune réservation trouvée</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($reservations as $res): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($res['nom_event']) ?></td>
                                                <td><?= htmlspecialchars($res['nom_user']) ?> <?= htmlspecialchars($res['prenom_user']) ?></td>
                                                <td><?= htmlspecialchars($res['date_reservation']) ?></td>
                                                <td>
                                                    <?php if ($res['status'] === 'pending'): ?>
                                                        <div class="d-flex gap-2">
                                                            <form method="POST">
                                                                <input type="hidden" name="id_reservation" value="<?= $res['id_reservation'] ?>">
                                                                <input type="hidden" name="new_status" value="approved">
                                                                <button type="submit" class="btn btn-success btn-sm w-100">
                                                                    <i class="fas fa-check-circle me-1"></i>Approuver
                                                                </button>
                                                            </form>
                                                            <form method="POST">
                                                                <input type="hidden" name="id_reservation" value="<?= $res['id_reservation'] ?>">
                                                                <input type="hidden" name="new_status" value="declined">
                                                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                                                    <i class="fas fa-times-circle me-1"></i>Refuser
                                                                </button>
                                                            </form>
                                                        </div>
                                                    <?php elseif ($res['status'] === 'approved'): ?>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="badge bg-success bg-opacity-25 text-success">
                                                                <i class="fas fa-check me-1"></i>Approuvée
                                                            </span>
                                                            <form method="POST" class="ms-auto">
                                                                <input type="hidden" name="id_reservation" value="<?= $res['id_reservation'] ?>">
                                                                <input type="hidden" name="new_status" value="cancelled">
                                                                <button type="submit" class="btn btn-outline-warning btn-sm">
                                                                    <i class="fas fa-ban me-1"></i>Annuler
                                                                </button>
                                                            </form>
                                                        </div>
                                                    <?php elseif ($res['status'] === 'declined'): ?>
                                                        <span class="badge bg-danger bg-opacity-25 text-danger">
                                                            <i class="fas fa-times me-1"></i>Refusée
                                                        </span>
                                                    <?php elseif ($res['status'] === 'cancelled'): ?>
                                                        <span class="badge bg-secondary bg-opacity-25 text-secondary">
                                                            <i class="fas fa-ban me-1"></i>Annulée
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="?edit=<?= $res['id_reservation'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                                                    <a href="?delete=<?= $res['id_reservation'] ?>" onclick="return confirm('Confirmer la suppression ?')" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>
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

        <!-- Export PDF -->
        <form method="post" action="export_pdf.php" class="mt-4">
            <button type="submit" name="export_pdf" class="btn btn-primary w-100">Exporter en PDF</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
