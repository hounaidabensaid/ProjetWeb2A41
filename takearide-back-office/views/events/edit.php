<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Modifier l'événement</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="index.php?action=update">
            <input type="hidden" name="id" value="<?= $event['id_event'] ?>">

            <div class="mb-3">
                <label for="titre" class="form-label">Titre :</label>
                <input type="text" class="form-control" name="titre" id="titre" value="<?= htmlspecialchars($event['nom']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description :</label>
                <textarea class="form-control" name="description" id="description" rows="3" required><?= htmlspecialchars($event['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="lieu" class="form-label">Lieu :</label>
                <input type="text" class="form-control" name="lieu" id="lieu" value="<?= htmlspecialchars($event['lieu']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Date :</label>
                <?php $dateFormatted = date('Y-m-d', strtotime($event['date'])); ?>
                <input type="date" class="form-control" name="date" id="date" value="<?= $dateFormatted ?>" min="2025-04-16" required>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</div>
