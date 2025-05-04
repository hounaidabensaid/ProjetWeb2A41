<?php
// Récupération obligatoire de l'ID depuis l'URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID d'annonce invalide ou manquant");
}
$id_annonce = (int)$_GET['id'];
?>

<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Demande de covoiturage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #000000;
            padding: 2rem;
            color: #ffffff;
        }
        .form-section {
            background: #8B0000;
            padding: 2rem 3rem;
            border-radius: 1rem;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 0 15px #ff0000;
        }
        .form-section h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 2.5rem;
            font-weight: bold;
            color: #ffffff;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #ff0000;
            border-radius: 0.5rem;
            font-size: 1.1rem;
            background-color: #000000;
            color: #ffffff;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus {
            border-color: #ff4d4d;
            outline: none;
        }
        button[type="submit"] {
            width: 100%;
            padding: 1rem;
            background-color: #ff0000;
            border: none;
            border-radius: 0.75rem;
            color: #ffffff;
            font-size: 1.3rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button[type="submit"]:hover {
            background-color: #cc0000;
        }
        .message {
            max-width: 600px;
            margin: 0 auto 1rem auto;
            padding: 1rem;
            border-radius: 0.5rem;
            font-weight: bold;
            text-align: center;
        }
        .message.error {
            background-color: #ff4d4d;
            color: #fff;
            box-shadow: 0 0 10px #ff0000;
        }
        .message.success {
            background-color: #4CAF50;
            color: #fff;
            box-shadow: 0 0 10px #2e7d32;
        }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['erreur'])): ?>
        <div class="message error">
            <?= $_SESSION['erreur']; unset($_SESSION['erreur']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="message success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <section class="form-section">
        <h2>Demande de covoiturage</h2>
        <form action="traitement_demande.php" method="POST">
            <input type="hidden" name="action" value="demande_covoiturage" />
            <input type="hidden" name="id_annonce" value="<?= $id_annonce ?>" />
            <div class="form-group">
                <input type="text" name="nom" required placeholder="Nom" />
            </div>
            <div class="form-group">
                <input type="text" name="prenom" required placeholder="Prénom" />
            </div>
            <div class="form-group">
                <input type="tel" name="telephone" required placeholder="Téléphone" pattern="[0-9]{8}" title="Veuillez entrer un numéro de téléphone composé de 8 chiffres" />
            </div>
            <div class="form-group">
                <input type="email" name="email" required placeholder="Email" />
            </div>
            <div class="form-group">
                <input type="number" name="places" required placeholder="Nombre de places" />
            </div>
            <button type="submit">Soumettre la demande</button>
        </form>
    </section>
<?php unset($_SESSION['form_data']); ?>
</body>
</html>
