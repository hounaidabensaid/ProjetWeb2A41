<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=123;charset=utf8', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(Exception $e) {
    die('Erreur : '.$e->getMessage());
}

$villeDepart = isset($_GET['villeDepart']) ? trim($_GET['villeDepart']) : '';
$villeArrivee = isset($_GET['villeArrivee']) ? trim($_GET['villeArrivee']) : '';

$query = 'SELECT * FROM `123` WHERE date >= CURDATE()';
$params = [];

if ($villeDepart !== '') {
    $query .= ' AND villeDepart LIKE :villeDepart';
    $params[':villeDepart'] = '%' . $villeDepart . '%';
}

if ($villeArrivee !== '') {
    $query .= ' AND villeArrivee LIKE :villeArrivee';
    $params[':villeArrivee'] = '%' . $villeArrivee . '%';
}

$query .= ' ORDER BY date DESC';

$stmt = $bdd->prepare($query);
$stmt->execute($params);

if ($stmt->rowCount() == 0) {
    echo '<p class="text-gray-500 text-center">Aucune annonce trouvée pour ces critères</p>';
} else {
    while ($annonce = $stmt->fetch()) {
        echo '
        <div class="annonce">
            <div class="annonce-header">
                <div class="annonce-title">
                    '.htmlspecialchars($annonce['villeDepart']).' → '.htmlspecialchars($annonce['villeArrivee']).'
                </div>
                <div class="annonce-actions">
                    <button onclick="editAnnonce('.$annonce['id'].')" class="btn-action btn-edit">✏️ Modifier</button>
                    <button onclick="deleteAnnonce('.$annonce['id'].')" class="btn-action btn-delete">🗑️ Supprimer</button>
                    <a href="demande_covoiturage.php?id=' . $annonce['id'] . '" class="btn-action btn-request" style="text-decoration: none; cursor: pointer; background-color: #6b7280; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem;">🚗 Demande de covoiturage</a>
                </div>
            </div>
            <div class="annonce-details">
                <div class="annonce-info"><strong>Date:</strong> '.htmlspecialchars($annonce['date']).'</div>
                <div class="annonce-info"><strong>Prix:</strong> '.htmlspecialchars($annonce['prix']).' D</div>
                <div class="annonce-info"><strong>Places:</strong> '.htmlspecialchars($annonce['placesDisponibles']).'</div>
                <div class="annonce-info"><strong>Conducteur:</strong> '.htmlspecialchars($annonce['prenom']).' '.htmlspecialchars($annonce['nom']).'</div>
            </div>
            <div class="annonce-info"><strong>Véhicule:</strong> '.htmlspecialchars($annonce['typeVehicule']).' ('.htmlspecialchars($annonce['matricule']).')</div>';
        
        if (!empty($annonce['details'])) {
            echo '<div class="annonce-info" style="margin-top: 0.5rem;"><strong>Détails:</strong> '.htmlspecialchars($annonce['details']).'</div>';
        }
        echo '</div>';
    }
}
?>
