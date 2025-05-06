<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=123;charset=utf8', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Si un id est passé via l'URL, on récupère l'annonce
    if (isset($_GET['id'])) {
        $req = $bdd->prepare('SELECT * FROM `123` WHERE id = ?');
        $req->execute([$_GET['id']]);
        $annonce = $req->fetch();
    }

    // Mise à jour de l'annonce
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $req = $bdd->prepare('UPDATE `123` SET 
            nom = ?,
            prenom = ?,
            villeDepart = ?,
            villeArrivee = ?,
            date = ?,
            prix = ?,
            matricule = ?,
            typeVehicule = ?,
            placesDisponibles = ?,
            details = ?,
            telephone = ?
            WHERE id = ?');
        
        $req->execute([
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['villeDepart'],
            $_POST['villeArrivee'],
            $_POST['date'],
            $_POST['prix'],
            $_POST['matricule'],
            $_POST['typeVehicule'],
            $_POST['placesDisponibles'],
            $_POST['details'],
            $_POST['telephone'],
            $_POST['id']
        ]);
        
        // Redirection après mise à jour 
        header('Location: covoiturage.php?page=proposer');
        exit;
    }
} catch(Exception $e) {
    die('Erreur: '.$e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Annonce</title>
</head>
<body>
    <div class="container">
        <h1>Modifier Annonce</h1>

        <?php if (isset($_GET['id']) && $annonce): ?>
            <form method="post">
                <input type="hidden" name="id" value="<?= htmlspecialchars($annonce['id']) ?>">

            <p><strong>Nom :</strong> <input type="text" name="nom" value="<?= htmlspecialchars($annonce['nom']) ?>" required></p>

            <p><strong>Prénom :</strong> <input type="text" name="prenom" value="<?= htmlspecialchars($annonce['prenom']) ?>" required></p>

            <p><strong>Ville de départ :</strong> <input type="text" name="villeDepart" value="<?= htmlspecialchars($annonce['villeDepart']) ?>" required></p>

            <p><strong>Ville d'arrivée :</strong> <input type="text" name="villeArrivee" value="<?= htmlspecialchars($annonce['villeArrivee']) ?>" required></p>

            <p><strong>Date :</strong> <input type="date" name="date" value="<?= htmlspecialchars($annonce['date']) ?>" required></p>

            <p><strong>Prix par personne :</strong> <input type="number" name="prix" value="<?= htmlspecialchars($annonce['prix']) ?>" required></p>

            <p><strong>Matricule du véhicule :</strong> <input type="text" name="matricule" value="<?= htmlspecialchars($annonce['matricule']) ?>" required></p>

            <p><strong>Type de véhicule :</strong> <input type="text" name="typeVehicule" value="<?= htmlspecialchars($annonce['typeVehicule']) ?>" required></p>

            <p><strong>Nombre de places disponibles :</strong> <input type="number" name="placesDisponibles" value="<?= htmlspecialchars($annonce['placesDisponibles']) ?>" required></p>

            <p><strong>Téléphone :</strong> <input type="tel" name="telephone" value="<?= htmlspecialchars($annonce['telephone']) ?>" required pattern="[0-9]{8}" title="Veuillez entrer un numéro de téléphone composé de 8 chiffres"></p>

            <p><strong>Détails :</strong> <textarea name="details" ><?= htmlspecialchars($annonce['details']) ?></textarea></p>

                <button type="submit">Mettre à jour</button>
            </form>
        <?php else: ?>
            <p>Annonce introuvable.</p>
        <?php endif; ?>
    </div>
</body>
</html>
