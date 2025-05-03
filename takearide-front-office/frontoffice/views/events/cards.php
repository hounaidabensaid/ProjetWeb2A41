<?php
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id' => 3,
        'nom' => 'Mejri',
        'prenom' => 'Lina',
        'email' => 'lina@example.com',
        'role' => 'client'
    ];
}

// Récupérer l'utilisateur
$user = $_SESSION['user'];
?>
<form method="GET" class="mb-4">
    <div class="row">
        <div class="col-md-3">
            <label for="search" class="form-label">🔍 Rechercher :</label>
            <input type="text" name="search" id="search" class="form-control" placeholder="Nom ou description"
                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        </div>
        <div class="col-md-3">
            <label for="sort" class="form-label">Trier par :</label>
            <select class="form-select" id="sort" name="sort">
                <option value="">-- Sélectionner --</option>
                <option value="nom" <?= (isset($_GET['sort']) && $_GET['sort'] === 'nom') ? 'selected' : '' ?>>Nom</option>
                <option value="date" <?= (isset($_GET['sort']) && $_GET['sort'] === 'date') ? 'selected' : '' ?>>Date</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="order" class="form-label">Ordre :</label>
            <select class="form-select" id="order" name="order">
                <option value="asc" <?= (isset($_GET['order']) && $_GET['order'] === 'asc') ? 'selected' : '' ?>>Croissant</option>
                <option value="desc" <?= (isset($_GET['order']) && $_GET['order'] === 'desc') ? 'selected' : '' ?>>Décroissant</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
        </div>
    </div>
</form>



<div class="container mt-4">
    <div class="row">
        <?php if (isset($events) && !empty($events)): ?>
            <?php foreach ($events as $event): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($event['nom']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($event['description']) ?></p>
                            
                            <img src="<?= htmlspecialchars($event['image']) ?>" alt="Image de l'événement" style="width:100%; height:auto;">

                            <p><strong>Date:</strong> <?= htmlspecialchars($event['date']) ?></p>
                            <button class="btn btn-primary" onclick="openConfirmModal(<?= (int)$event['id_event'] ?>)">
                                Réserver
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Confirmation DIRECTE -->
                <div class="modal fade" id="confirmModal<?= (int)$event['id_event'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirmation Réservation</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>💬 Confirmez-vous votre réservation pour :</p>
                                <p><strong>Nom :</strong> <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></p>
                                <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
                                <p><strong>Événement :</strong> <?= htmlspecialchars($event['nom']) ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" onclick="submitReservation(<?= (int)$event['id_event'] ?>)">Confirmer</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun événement disponible pour le moment.</p>
        <?php endif; ?>
    </div>
</div>

<!-- JS -->
<script>
function openConfirmModal(eventId) {
    const confirmModal = new bootstrap.Modal(document.getElementById("confirmModal" + eventId));
    confirmModal.show();
}

function submitReservation(eventId) {
    const formData = new FormData();
    formData.append("id_event", eventId);

    fetch("http://localhost/yassvf/takearideyas/takearide-front-office/frontoffice/booking.php?action=storeReservation", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(result => {
        if (result.success) {
            alert("✅ Réservation confirmée !");
            bootstrap.Modal.getInstance(document.getElementById("confirmModal" + eventId)).hide();
        } else {
            alert("❌ Erreur : " + result.error);
        }
    })
    .catch(err => {
        console.error(err);
        alert("⚠️ Erreur serveur !");
    });
}
</script>
