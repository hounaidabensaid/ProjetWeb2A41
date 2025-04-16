<div class="container mt-5">
    <h2>Ajouter un participant</h2>
    <form action="index.php?action=saveParticipant" method="post">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom :</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="evenement_id" class="form-label">Événement :</label>
            <select class="form-select" id="evenement_id" name="evenement_id" required>
                <option value="">-- Choisir un événement --</option>
                <?php foreach ($evenements as $event): ?>
                    <option value="<?= $event['id'] ?>"><?= htmlspecialchars($event['titre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>
