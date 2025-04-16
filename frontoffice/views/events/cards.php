<div class="container mt-4">
    <div class="row">
        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($event['nom']) ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                📍 <?= htmlspecialchars($event['lieu']) ?><br>
                                📅 <?= htmlspecialchars($event['date']) ?>
                            </h6>
                            <p class="card-text flex-grow-1"><?= nl2br(htmlspecialchars($event['description'])) ?></p>

                            <!-- Bouton pour ouvrir le modal -->
                            <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#reserveModal<?= $event['id_event'] ?>">
                                Réserver
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Aucun événement trouvé.</p>
        <?php endif; ?>
    </div>
</div>

<!-- 🔽 Tous les modals sont générés ici, séparément des cartes -->
<?php foreach ($events as $event): ?>
    <div class="modal fade" id="reserveModal<?= $event['id_event'] ?>" tabindex="-1" aria-labelledby="reserveModalLabel<?= $event['id_event'] ?>" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="index.php?action=storeReservation">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reserveModalLabel<?= $event['id_event'] ?>">Réserver pour : <?= htmlspecialchars($event['nom']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_event" value="<?= $event['id_event'] ?>">

                        <div class="mb-3">
                            <label for="nom<?= $event['id_event'] ?>" class="form-label">Nom :</label>
                            <input type="text" class="form-control" name="nom" id="nom<?= $event['id_event'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email<?= $event['id_event'] ?>" class="form-label">Email :</label>
                            <input type="email" class="form-control" name="email" id="email<?= $event['id_event'] ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Valider</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<!-- Bootstrap JS nécessaire -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
