<?php
// test_event.php
require_once 'config/db.php';

$conn = Database::getConnection();

$query = "SELECT * FROM event";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<h2>Liste des événements :</h2><ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li><strong>" . htmlspecialchars($row['titre']) . "</strong> - " . 
             htmlspecialchars($row['description']) . " - " . 
             htmlspecialchars($row['lieu']) . " - " . 
             htmlspecialchars($row['date']) . "</li>";
    }
    echo "</ul>";
} else {
    echo "Aucun événement trouvé.";
}
?>
