<?php
// Calculate total counts for percentages
$totalDepart = array_sum(array_column($stats['depart'], 'total'));
$totalArrivee = array_sum(array_column($stats['arrivee'], 'total'));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Statistiques des villes de départ et d'arrivée</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #b30000;
            --primary-dark: #800000;
            --primary-light: #990000;
            --secondary-color: #f8f9fa;
            --text-color: #333;
            --light-gray: #f5f5f5;
        }
        html, body {
            height: 100%;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
        }
        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 30px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }
        .sidebar {
            width: 250px;
            background-color: var(--primary-color);
            color: white;
            min-height: 100vh;
            position: fixed;
        }
        .sidebar-header {
            padding: 20px;
            background-color: var(--primary-dark);
        }
        .sidebar-menu {
            padding: 0;
            list-style: none;
        }
        .sidebar-menu li a {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
        }
        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background-color: var(--primary-dark);
        }
        .sidebar-menu li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .card-header {
            background-color: var(--primary-color);
            color: white;
        }
        .table th {
            background-color: var(--primary-color);
            color: white;
        }
        input[type="text"] {
            padding: 8px;
            font-size: 14px;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button[type="submit"] {
            padding: 8px 16px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
        }
        .charts-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 40px;
        }
        .chart-box {
            background-color: #800000;
            border-radius: 15px;
            padding: 20px;
            width: 400px;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.7);
        }
        canvas {
            display: block;
            margin: 0 auto;
        }
        .chart-title {
            text-align: center;
            margin-bottom: 15px;
            font-size: 1.3em;
            font-weight: bold;
        }
    </style>
</head>
<body>
        <div class="sidebar">
            <div class="sidebar-header">
                <h3 class="text-center">Admin Panel</h3>
            </div>
            <ul class="sidebar-menu">
                <li><a href="#"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                <li><a href="index_voiture.php?page=users"><i class="fas fa-users"></i> Utilisateurs</a></li>
                <li><a href="index_voiture.php?page=voiture"><i class="fas fa-car"></i> Gestion covoiturage</a></li>
                <li><a href="statistiques_villes_controller.php" class="active"><i class="fas fa-chart-bar"></i> Statistiques villes</a></li>
                <li><a href="#"><i class="fas fa-calendar-check"></i> Réservations</a></li>
                <li><a href="#"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
            </ul>
        </div>
        <div class="main-content">
        <h1 class="page-title">Statistiques des villes de départ et d'arrivée</h1>
        <div class="charts-container">
            <div class="chart-box">
                <div class="chart-title">Villes de départ</div>
                <canvas id="departChart" width="350" height="350"></canvas>
            </div>
            <div class="chart-box">
                <div class="chart-title">Villes d'arrivée</div>
                <canvas id="arriveeChart" width="350" height="350"></canvas>
            </div>
        </div>

    <script>
        const departLabels = <?= json_encode(array_column($stats['depart'], 'villeDepart')) ?>;
        const departData = <?= json_encode(array_map(function($item) use ($totalDepart) {
            return round(($item['total'] / $totalDepart) * 100, 2);
        }, $stats['depart'])) ?>;

        const arriveeLabels = <?= json_encode(array_column($stats['arrivee'], 'villeArrivee')) ?>;
        const arriveeData = <?= json_encode(array_map(function($item) use ($totalArrivee) {
            return round(($item['total'] / $totalArrivee) * 100, 2);
        }, $stats['arrivee'])) ?>;

        const departColors = [
            '#ff4d4d', '#ff6666', '#ff8080', '#ff9999', '#ffb3b3', '#ffcccc', '#ffe6e6', '#ff1a1a', '#e60000', '#cc0000'
        ];
        const arriveeColors = [
            '#ff4d4d', '#ff6666', '#ff8080', '#ff9999', '#ffb3b3', '#ffcccc', '#ffe6e6', '#ff1a1a', '#e60000', '#cc0000'
        ];

        const departCtx = document.getElementById('departChart').getContext('2d');
        const departChart = new Chart(departCtx, {
            type: 'bar',
            data: {
                labels: departLabels,
                datasets: [{
                    label: 'Pourcentage',
                    data: departData,
                    backgroundColor: departColors,
                    borderColor: '#800000',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                    label: function(context) {
                        if (typeof context.parsed === 'object') {
                            return context.label + ': ' + context.parsed.y + '%';
                        }
                        return context.label + ': ' + context.parsed + '%';
                    }
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: 'white',
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            }
        });

        const arriveeCtx = document.getElementById('arriveeChart').getContext('2d');
        const arriveeChart = new Chart(arriveeCtx, {
            type: 'doughnut',
            data: {
                labels: arriveeLabels,
                datasets: [{
                    label: 'Pourcentage',
                    data: arriveeData,
                    backgroundColor: arriveeColors,
                    borderColor: '#800000',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + '%';
                            }
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: 'white',
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
