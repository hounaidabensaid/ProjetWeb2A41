<?php
session_start();
require_once '../../config.php';
require_once '../../controller/UserController.php';

$controller = new UserController();
$users = $controller->listUser();
$users = $users->fetchAll(); // Convertir en tableau

$totalUsers = count($users);
$admins = array_filter($users, fn($u) => $u['role'] === 'admin');
$clients = array_filter($users, fn($u) => $u['role'] === 'client');
$totalAdmins = count($admins);
$totalClients = count($clients);

// Calcul des pourcentages
$adminPercentage = $totalUsers > 0 ? round(($totalAdmins / $totalUsers) * 100, 2) : 0;
$clientPercentage = $totalUsers > 0 ? round(($totalClients / $totalUsers) * 100, 2) : 0;

// Préparer les données pour le graphique circulaire
$pieLabels = json_encode(['Admins', 'Clients']);
$pieValues = json_encode([$totalAdmins, $totalClients]);

include 'header.php';
?>

<div class="container py-5">
    <h2 class="text-center mb-4">📊 Statistiques des Utilisateurs</h2>

    <div class="row text-center mb-4">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Utilisateurs</h5>
                    <p class="fs-3 fw-bold text-primary"><?= $totalUsers ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Admins</h5>
                    <p class="fs-3 fw-bold text-success"><?= $totalAdmins ?> (<?= $adminPercentage ?>%)</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Clients</h5>
                    <p class="fs-3 fw-bold text-danger"><?= $totalClients ?> (<?= $clientPercentage ?>%)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">📊 Répartition des Utilisateurs</h5>
                    <canvas id="userPieChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
    // Graphique circulaire (Pie Chart)
    const pieCtx = document.getElementById('userPieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: <?= $pieLabels ?>,
            datasets: [{
                label: 'Répartition des utilisateurs',
                data: <?= $pieValues ?>,
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)', // Couleur pour Admins
                    'rgba(255, 99, 132, 0.2)'  // Couleur pour Clients
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',   // Couleur pour Admins
                    'rgba(255, 99, 132, 1)'   // Couleur pour Clients
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                datalabels: {
                    formatter: (value, context) => {
                        const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(2);
                        return percentage + '%'; // Affiche le pourcentage
                    },
                    color: '#000',
                    font: {
                        weight: 'bold',
                        size: 14
                    }
                }
            }
        },
        plugins: [ChartDataLabels] // Activer le plugin Data Labels
    });
</script>

<?php include 'footer.php'; ?>