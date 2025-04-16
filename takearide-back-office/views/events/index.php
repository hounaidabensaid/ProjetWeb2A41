<!-- views/events/index.php -->

<?php if (isset($_SESSION['flash'])): ?>
    <div class="alert alert-warning text-center fw-bold">
        <?= $_SESSION['flash']; ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Liste des Événements</h2>
        <a href="index.php?action=add" class="btn btn-success">+ Ajouter un événement</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Titre</th>
                    <th scope="col">Description</th>
                    <th scope="col">Lieu</th>
                    <th scope="col">Date</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                <tr>
                    <td><?= $event['id_event'] ?></td>
                    <td><?= htmlspecialchars($event['nom']) ?></td>
                    <td><?= htmlspecialchars($event['description']) ?></td>
                    <td><?= htmlspecialchars($event['lieu']) ?></td>
                    <td><?= htmlspecialchars($event['date']) ?></td>
                    <td>
                        <a href="index.php?action=edit&id=<?= $event['id_event'] ?>" class="btn btn-sm btn-primary">Modifier</a>
                        <a href="index.php?action=delete&id=<?= $event['id_event'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet événement ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
