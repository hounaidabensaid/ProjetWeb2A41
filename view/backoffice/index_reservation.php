<?php
require_once '../../config.php';
require_once '../../model/Reservation.php';
require_once '../../controller/ReservationController.php';

$pdo = config::getConnexion();
$controller = new ReservationController($pdo);

// Gestion des actions de statut
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    try {
        if ($action === 'confirm') {
            $controller->updateStatut($id, 'confirmée');
            $_SESSION['message'] = "Réservation confirmée avec succès.";

            // Optionnel : Envoi SMS de confirmation
            $reservation = $controller->getReservationById($id);
            $stmt = $pdo->prepare("SELECT nom, phone FROM user WHERE id = ?");
            $stmt->execute([$reservation['id_user']]);
            $user = $stmt->fetch();
            if ($user && !empty($user['phone'])) {
                $controller->sendConfirmationSMS($user['phone'], $user['nom'], $reservation['date_début'], $reservation['date_fin']);
            }

        } elseif ($action === 'refuse') {
            $controller->updateStatut($id, 'annulée');  // Ou 'refusée' selon ta logique
            $_SESSION['message'] = "Réservation refusée.";
        }

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } catch (Exception $e) {
        echo "<div style='color:red;'>Erreur : " . $e->getMessage() . "</div>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id_reservation'] ?? null;
        $id_voiture = intval($_POST['id_voiture']);
        $id_user = intval($_POST['id_user']);
        $date_debut = trim($_POST['date_debut']);
        $date_fin = trim($_POST['date_fin']);
        $statut = trim($_POST['statut']);

        if (empty($id_voiture) || empty($id_user) || empty($date_debut) || empty($date_fin) || empty($statut)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        $reservation = new Reservation();
        $reservation->setIdReservation($id);
        $reservation->setIdVoiture($id_voiture);
        $reservation->setIdUser($id_user);
        $reservation->setDateDébut($date_debut);
        $reservation->setDateFin($date_fin);
        $reservation->setStatut($statut);

        if ($id) {
            $controller->updateReservation($reservation);
            $_SESSION['message'] = "Réservation mise à jour avec succès";
        } else {
            $controller->addReservation($reservation);
            $_SESSION['message'] = "Réservation ajoutée avec succès";
        }

        // ✅ Envoi SMS après traitement
        if ($statut === 'confirmée') {
            $stmt = $pdo->prepare("SELECT nom, phone FROM user WHERE id = ?");
            $stmt->execute([$id_user]);
            $user = $stmt->fetch();

            if ($user && !empty($user['phone'])) {
                $controller->sendConfirmationSMS($user['phone'], $user['nom'], $date_debut, $date_fin);
            } else {
                throw new Exception("Numéro de téléphone introuvable pour l'utilisateur.");
            }
        }

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;

    } catch (Exception $e) {
        echo "<div style='color:red;'>Erreur : " . $e->getMessage() . "</div>";
    }
}

// Suppression
if (isset($_GET['delete'])) {
    try {
        $controller->deleteReservation($_GET['delete']);
        $_SESSION['message'] = "Réservation supprimée avec succès";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Pré-remplissage pour modification
$reservationToEdit = null;
if (isset($_GET['edit'])) {
    $reservationToEdit = $controller->getReservationById($_GET['edit']);
    if (!$reservationToEdit) {
        $_SESSION['error'] = "Réservation introuvable";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Liste des réservations
$reservations = $controller->getAllReservations();
// Pour les listes déroulantes
$voitures = $pdo->query("SELECT * FROM voiture")->fetchAll();
$users = $pdo->query("SELECT * FROM user")->fetchAll();
include 'header.php'
?>

        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
        <h2 class="mb-4">Gestion des Réservations</h2>
		<nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reclamations</li>
                        </ol>
                    </nav>
                </div>
            </div><div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?= $reservationToEdit ? 'Modifier' : 'Ajouter' ?> une Réservation</h5>
            </div>
            <div class="card-body">
                <form method="POST" id="reservation-form" novalidate>
                    <input type="hidden" id="id_reservation" name="id_reservation" value="<?= $reservationToEdit ? $reservationToEdit['id_reservation'] : '' ?>">

                    <div class="mb-3">
                        <label for="id_voiture" class="form-label">Voiture</label>
                        <select class="form-select" id="id_voiture" name="id_voiture" required>
                            <option value="">-- Sélectionner une voiture --</option>
                            <?php foreach ($voitures as $v): ?>
                                <option value="<?= $v['id_voiture'] ?>" <?= ($reservationToEdit && $reservationToEdit['id_voiture'] == $v['id_voiture']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($v['matricule']) ?> - <?= htmlspecialchars($v['marque']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="text-danger error-message" id="voiture-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="id_user" class="form-label">Utilisateur</label>
                        <select class="form-select" id="id_user" name="id_user" required>
                            <option value="">-- Sélectionner un utilisateur --</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id'] ?>" <?= ($reservationToEdit && $reservationToEdit['id_user'] == $u['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($u['nom']) ?> <?= htmlspecialchars($u['prenom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="text-danger error-message" id="user-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="date_debut" class="form-label">Date début</label>
                        <input type="date" class="form-control" id="date_debut" name="date_debut"
                            value="<?= $reservationToEdit ? $reservationToEdit['date_début'] : '' ?>" required>
                        <div class="text-danger error-message" id="date_debut-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="date_fin" class="form-label">Date fin</label>
                        <input type="date" class="form-control" id="date_fin" name="date_fin"
                            value="<?= $reservationToEdit ? $reservationToEdit['date_fin'] : '' ?>" required>
                        <div class="text-danger error-message" id="date_fin-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="statut" class="form-label">Statut</label>
                        <select class="form-select" id="statut" name="statut" required>
                            <option value="">-- Choisir un statut --</option>
                            <option value="en attente" <?= ($reservationToEdit && $reservationToEdit['statut'] === "en attente") ? 'selected' : '' ?>>En attente</option>
                            <option value="confirmée" <?= ($reservationToEdit && $reservationToEdit['statut'] === "confirmée") ? 'selected' : '' ?>>Confirmée</option>
                            <option value="annulée" <?= ($reservationToEdit && $reservationToEdit['statut'] === "annulée") ? 'selected' : '' ?>>Annulée</option>
                        </select>
                        <div class="text-danger error-message" id="statut-error"></div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i><?= $reservationToEdit ? 'Modifier' : 'Ajouter' ?>
                    </button>

                    <?php if ($reservationToEdit): ?>
                        <a href="index_reservation.php" class="btn btn-outline-secondary w-100 mt-2">
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
            <h5 class="mb-0">Liste des Réservations</h5>
            <span class="badge bg-primary"><?= count($reservations) ?> réservations</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Voiture</th>
                            <th>Utilisateur</th>
                            <th>Date Début</th>
                            <th>Date Fin</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reservations)): ?>
                            <tr>
                                <td colspan="6" class="text-center">Aucune réservation enregistrée</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reservations as $res): ?>
                                <tr>
                                    <td><?= htmlspecialchars($res['matricule_voiture'] ?? $res['id_voiture']) ?></td>
                                    <td><?= htmlspecialchars($res['nom_user'] ?? $res['id_user']) ?></td>
                                    <td><?= htmlspecialchars($res['date_début']) ?></td>
                                    <td><?= htmlspecialchars($res['date_fin']) ?></td>
                                    <td>
                                        <?php
                                            $statut = $res['statut'];
                                            $badgeClass = 'bg-secondary';
                                            if ($statut === 'confirmée') $badgeClass = 'bg-success';
                                            elseif ($statut === 'en attente') $badgeClass = 'bg-warning text-dark';
                                            elseif ($statut === 'annulée') $badgeClass = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= ucfirst($statut) ?></span>
                                    </td>
                                   <td class="action-btns d-flex justify-content-center flex-wrap gap-2">
    <a href="?edit=<?= $res['id_reservation'] ?>" class="btn btn-sm btn-outline-primary" title="Modifier">
        <i class="fas fa-edit"></i>
    </a>
    <a href="?delete=<?= $res['id_reservation'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')" class="btn btn-sm btn-outline-danger" title="Supprimer">
        <i class="fas fa-trash"></i>
    </a>
    <a href="?action=confirm&id=<?= $res['id_reservation'] ?>" class="btn btn-sm btn-success" title="Confirmer">
        <i class="fas fa-check"></i>
    </a>
    <a href="?action=refuse&id=<?= $res['id_reservation'] ?>" class="btn btn-sm btn-danger text-white" title="Refuser">
        <i class="fas fa-times"></i>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
            button.addEventListener('click', function (e) {
                if (!confirm("Êtes-vous sûr de vouloir supprimer cette réservation ?")) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
<script>
    document.getElementById('reservation-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const id_voiture = document.getElementById('id_voiture').value.trim();
        const id_user = document.getElementById('id_user').value.trim();
        const date_debut = document.getElementById('date_debut').value.trim();
        const date_fin = document.getElementById('date_fin').value.trim();
        const statut = document.getElementById('statut').value.trim();

        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

        let error = false;

        if (!id_voiture) {
            document.getElementById('voiture-error').textContent = "La voiture est obligatoire.";
            error = true;
        }

        if (!id_user) {
            document.getElementById('user-error').textContent = "L'utilisateur est obligatoire.";
            error = true;
        }

        if (!date_debut) {
            document.getElementById('date_debut-error').textContent = "La date de début est requise.";
            error = true;
        }

        if (!date_fin) {
            document.getElementById('date_fin-error').textContent = "La date de fin est requise.";
            error = true;
        }

        if (new Date(date_debut) > new Date(date_fin)) {
            document.getElementById('date_fin-error').textContent = "La date de fin doit être après la date de début.";
            error = true;
        }

        if (!statut) {
            document.getElementById('statut-error').textContent = "Le statut est requis.";
            error = true;
        }

        if (!error) {
            e.target.submit();
        }
    });
</script>
<?php
require_once 'footer.php';
?>