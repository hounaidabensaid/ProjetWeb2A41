<?php
require_once 'controller/ReponseController.php';

$controller = new ReponseController();
$responses = $controller->getReponses();

foreach ($responses as $r) {
    echo $r['id'] . ': ' . ($r['piece_jointe'] ?? 'NULL') . PHP_EOL;
}
?>
