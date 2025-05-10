<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardName = htmlspecialchars($_POST['cardName']);
    $cardNumber = htmlspecialchars($_POST['cardNumber']);
    $expiryDate = htmlspecialchars($_POST['expiryDate']);
    $cvv = htmlspecialchars($_POST['cvv']);

    // Validation basique
    if (empty($cardName) || empty($cardNumber) || empty($expiryDate) || empty($cvv)) {
        die('Tous les champs sont obligatoires.');
    }

    // Exemple de traitement (à remplacer par une intégration avec une passerelle de paiement)
    if (strlen($cardNumber) === 16 && strlen($cvv) === 3) {
        echo "Paiement réussi pour $cardName.";
    } else {
        echo "Erreur dans les informations de paiement.";
    }
}
?>