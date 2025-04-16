<!-- views/participants/edit.php -->

<h2>Modifier un Participant</h2>

<form method="post" action="index.php?action=updateParticipant">
    <input type="hidden" name="id" value="<?= htmlspecialchars($participant['id']) ?>">

    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($participant['nom']) ?>" required><br>

    <label for="email">Email :</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($participant['email']) ?>" required><br>

    <label for="evenement_id">Événement :</label>
    <select id="evenement_id" name="evenement_id" required>
        <?php foreach ($evenements as $event): ?>
            <option value="<?= $event['id'] ?>" <?= $event['id'] == $participant['evenement_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($event['titre']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Mettre à jour</button>
</form>
