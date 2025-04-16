<!-- views/participants/index.php -->

<h2>Liste des participants</h2>

<a href="index.php?action=add_participant">Ajouter un participant</a>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Email</th>
        <th>ID Événement</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($participants as $participant): ?>
        <tr>
            <td><?= $participant['id'] ?></td>
            <td><?= $participant['nom'] ?></td>
            <td><?= $participant['email'] ?></td>
            <td><?= $participant['id_evenement'] ?></td>
            <td>
                <a href="index.php?action=edit_participant&id=<?= $participant['id'] ?>">Modifier</a> |
                <a href="index.php?action=delete_participant&id=<?= $participant['id'] ?>" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
