<div class="container my-4">
    <h2 class="mb-4">Ajouter un événement</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?= $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?action=save" class="border p-4 rounded shadow-sm bg-light">
        <div class="mb-3">
            <label for="titre" class="form-label">Titre :</label>
            <input type="text" name="titre" id="titre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description :</label>
            <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label for="lieu" class="form-label">Lieu :</label>
            <input type="text" name="lieu" id="lieu" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Date :</label>
            <input type="date" name="date" id="date" class="form-control" min="2025-04-16" required>
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>
