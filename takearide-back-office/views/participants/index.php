<!-- views/participants/index.php -->

<h2>Liste des Participants</h2>
<a href="index.php?action=showAddParticipantForm">Ajouter un participant</a>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Email</th>
        <th>Événement</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($participants as $participant): ?>
        <tr>
            <td><?= htmlspecialchars($participant['id']) ?></td>
            <td><?= htmlspecialchars($participant['nom']) ?></td>
            <td><?= htmlspecialchars($participant['email']) ?></td>
            <td><?= htmlspecialchars($participant['evenement_titre'] ?? 'Aucun') ?></td>
            <td>
                <a href="index.php?action=editParticipant&id=<?= $participant['id'] ?>">Modifier</a> |
                <a href="index.php?action=deleteParticipant&id=<?= $participant['id'] ?>" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
