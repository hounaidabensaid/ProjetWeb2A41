<h2 class="mb-4">Liste des événements</h2>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <?php foreach ($evenements as $event) : ?>
        <div class="col">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($event['titre']) ?></h5>
                    <p class="card-text"><strong>Description :</strong> <?= htmlspecialchars($event['description']) ?></p>
                    <p class="card-text"><strong>Lieu :</strong> <?= htmlspecialchars($event['lieu']) ?></p>
                    <p class="card-text"><strong>Date :</strong> <?= htmlspecialchars($event['date']) ?></p>
                </div>
                <div class="card-footer bg-white border-0 text-end">
                    <a href="index.php?action=edit&id=<?= $event['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                    <a href="index.php?action=delete&id=<?= $event['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
