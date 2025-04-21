<?php
session_start();
try {
    $bdd = new PDO('mysql:host=localhost;dbname=123;charset=utf8', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(Exception $e) {
    die('Erreur : '.$e->getMessage());
}
// Contrôle de saisie
if ($_POST['villeDepart'] === $_POST['villeArrivee']) {
    $_SESSION['erreur'] = "La ville de départ ne peut pas être la même que la ville d'arrivée.";

    // Sauvegarde des autres champs sauf villes
    $_SESSION['form_data'] = [
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'date' => $_POST['date'],
        'prix' => $_POST['prix'],
        'matricule' => $_POST['matricule'],
        'typeVehicule' => $_POST['typeVehicule'],
        'placesDisponibles' => $_POST['placesDisponibles'],
        'details' => $_POST['details']
    ];

    header('Location: covoiturage.php');
    exit;
}
$req = $bdd->prepare('INSERT INTO `123` (nom, prenom, villeDepart, villeArrivee, date, prix, matricule, typeVehicule, placesDisponibles, details) 
                      VALUES (:nom, :prenom, :villeDepart, :villeArrivee, :date, :prix, :matricule, :typeVehicule, :placesDisponibles, :details)');

$req->execute(array(
    'nom' => $_POST['nom'],
    'prenom' => $_POST['prenom'],
    'villeDepart' => $_POST['villeDepart'],
    'villeArrivee' => $_POST['villeArrivee'],
    'date' => $_POST['date'],
    'prix' => $_POST['prix'],
    'matricule' => $_POST['matricule'],
    'typeVehicule' => $_POST['typeVehicule'],
    'placesDisponibles' => $_POST['placesDisponibles'],
    'details' => $_POST['details']
));

$_SESSION['success'] = "Annonce ajoutée avec succès.";
header('Location: covoiturage.php');
?>