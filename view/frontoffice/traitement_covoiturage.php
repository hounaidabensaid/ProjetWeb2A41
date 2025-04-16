<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=123;charset=utf8', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(Exception $e) {
    die('Erreur : '.$e->getMessage());
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

header('Location: covoiturage.php');
?>