<?php
// Simple script to list files in uploads directory and piece_jointe entries in database

$uploadsDir = __DIR__ . '/uploads';

if (!is_dir($uploadsDir)) {
    echo "Uploads directory not found at: $uploadsDir\n";
    exit(1);
}

echo "Files in uploads directory:\n";
$files = scandir($uploadsDir);
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        echo "- $file\n";
    }
}

require_once 'config.php';

try {
    $db = config::getConnexion();
    $stmt = $db->query("SELECT piece_jointe FROM reponse WHERE piece_jointe IS NOT NULL AND piece_jointe != ''");
    $dbFiles = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\npiece_jointe entries in database:\n";
foreach ($dbFiles as $dbFile) {
    echo "- " . $dbFile . "\n";
}

?>
