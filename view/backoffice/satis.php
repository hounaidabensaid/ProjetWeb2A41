<?php
require_once __DIR__ . '/../../controller/ReclamationController.php';

$controller = new ReclamationController();
$reclamations = $controller->getReclamations();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - ShareRide BackOffice</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #f0f2f5;
        }
        
        .statistics-container {
            padding: 30px;
            margin-left: 250px;
        }
        
        .stats-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .stats-header h2 {
            color: #2c3e50;
            font-weight: 700;
            margin: 0;
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            padding: 25px;
            background: #ffffff;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-value {
            font-size: 2.5em;
            font-weight: bold;
            color: rgb(170, 8, 8);
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 1em;
            color: #666;
        }
        
        .charts-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 25px;
            margin-bottom: 25px;
        }
        
        .chart-box {
            padding: 20px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            height: 400px;
            display: flex;
            flex-direction: column;
        }
        
        .chart-box h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #2c3e50;
            font-weight: 600;
        }
        
        .chart-container {
            flex-grow: 1;
            position: relative;
        }
        
        .chart-box canvas {
            width: 100% !important;
            height: 100% !important;
        }
        
        .back-btn {
            padding: 10px 20px;
            background: rgb(170, 8, 8);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: background 0.3s ease;
        }
        
        .back-btn:hover {
            background: rgb(140, 8, 8);
        }
        
        /* Sidebar styles */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: rgb(170, 8, 8);
            color: white;
            padding: 20px;
            box-sizing: border-box;
        }
        
        .sidebar h1 {
            font-size: 1.5em;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .menu-item {
            padding: 12px 15px;
            margin-bottom: 10px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .menu-item:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .menu-item.active {
            background: rgba(255,255,255,0.2);
        }
        
        .menu-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .statistics-container {
                margin-left: 0;
                padding: 20px;
            }
            
            .stats-container {
                grid-template-columns: 1fr 1fr;
            }
            
            .charts-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
      <div class="sidebar">
        <div class="sidebar-header">
            <h3 class="text-center">Admin Panel</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard_user.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="index_voiture.php"><i class="fas fa-car"></i> Gestion Voitures</a></li>
            <li><a href="index_user.php" ><i class="fas fa-users"></i> Utilisateurs</a></li>
            <li><a href="index_reservation.php" ><i class="fas fa-calendar-check"></i> Réservations</a></li>
			            <li><a href="http://localhost/ttttttesttttt/view/takearide-back-office/views/event.php" ><i class="fas fa-calendar-check"></i> Evenement</a></li>
			            <li><a href="http://localhost/ttttttesttttt/view/takearide-back-office/views/reservation_event.php" ><i class="fas fa-calendar-check"></i> Réservations Evenement</a></li>

			<li><a href="dashboard.php"><i class="fas fa-calendar-check"></i> Réclamations</a></li>
			<li><a href="http://localhost/ttttttesttttt/view/BackOffice/satis.php"><i class="fas fa-calendar-check"></i> Statistiques</a></li>
              <li><a href="index_voiture1.php"><i class="fas fa-car"></i> Gestion covoiturage</a></li>
                <li><a href="statistiques_villes_controller.php" class="active"><i class="fas fa-chart-bar"></i> Statistiques villes</a></li>


			<li><a href="view_reponse.php"><i class="fas fa-calendar-check"></i> Réponses</a></li>

			<li><a href="http://localhost/ttttttesttttt/view/frontoffice/chatbox/view/backoffice/chatbox.php"><i class="fas fa-calendar-check"></i> Contact</a></li>

            <li><a href="../frontoffice/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </div>

    <!-- Contenu principal -->
    <div class="statistics-container">
        <div class="stats-header">
            <h2>Statistiques des Réclamations</h2>
            <a href="dashboard.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Retour au Dashboard
            </a>
        </div>
        
        <!-- Cartes de statistiques -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-value" id="total-reclamations">0</div>
                <div class="stat-label">Total Réclamations</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="reclamations-resolues">0</div>
                <div class="stat-label">Résolues</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="reclamations-en-cours">0</div>
                <div class="stat-label">En cours</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="gravite-elevee">0</div>
                <div class="stat-label">Haute Gravité</div>
            </div>
        </div>
        
        <!-- Graphiques -->
        <div class="charts-container">
            <div class="chart-box">
                <h3>Répartition par Type</h3>
                <div class="chart-container">
                    <canvas id="typeChart"></canvas>
                </div>
            </div>
            <div class="chart-box">
                <h3>Répartition par Statut</h3>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
            <div class="chart-box">
                <h3>Répartition par Gravité</h3>
                <div class="chart-container">
                    <canvas id="graviteChart"></canvas>
                </div>
            </div>
            <div class="chart-box">
                <h3>Évolution mensuelle</h3>
                <div class="chart-container">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stats = calculateStatistics();
            document.getElementById('total-reclamations').textContent = stats.total;
            document.getElementById('reclamations-resolues').textContent = stats.resolved;
            document.getElementById('reclamations-en-cours').textContent = stats.inProgress;
            document.getElementById('gravite-elevee').textContent = stats.highSeverity;
            createCharts(stats);
        });

        function calculateStatistics() {
            const reclamations = <?php echo json_encode($reclamations); ?>;
            let stats = {
                total: reclamations.length,
                resolved: 0,
                inProgress: 0,
                highSeverity: 0,
                types: {},
                statuses: {},
                severities: {},
                months: {}
            };
            reclamations.forEach(reclamation => {
                const status = reclamation.statut.toLowerCase();
                if (status.includes('résolu') || status.includes('resolu')) {
                    stats.resolved++;
                } else {
                    stats.inProgress++;
                }
                
                const severity = reclamation.gravite.toLowerCase();
                if (severity.includes('élevé') || severity.includes('eleve') || severity.includes('haute')) {
                    stats.highSeverity++;
                }
                
                stats.types[reclamation.type] = (stats.types[reclamation.type] || 0) + 1;
                stats.statuses[reclamation.statut] = (stats.statuses[reclamation.statut] || 0) + 1;
                stats.severities[reclamation.gravite] = (stats.severities[reclamation.gravite] || 0) + 1;
                
                if (reclamation.date_trajet) {
                    const date = new Date(reclamation.date_trajet);
                    const monthYear = `${date.getMonth()+1}/${date.getFullYear()}`;
                    stats.months[monthYear] = (stats.months[monthYear] || 0) + 1;
                }
            });
            return stats;
        }

        function createCharts(stats) {
            const typeCtx = document.getElementById('typeChart').getContext('2d');
            new Chart(typeCtx, {
                type: 'pie',
                data: {
                    labels: Object.keys(stats.types),
                    datasets: [{
                        data: Object.values(stats.types),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
            
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(stats.statuses),
                    datasets: [{
                        label: 'Nombre de réclamations',
                        data: Object.values(stats.statuses),
                        backgroundColor: 'rgba(170, 8, 8, 0.7)',
                        borderColor: 'rgba(170, 8, 8, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
            
            const graviteCtx = document.getElementById('graviteChart').getContext('2d');
            new Chart(graviteCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(stats.severities),
                    datasets: [{
                        data: Object.values(stats.severities),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
            
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: Object.keys(stats.months).sort(),
                    datasets: [{
                        label: 'Réclamations par mois',
                        data: Object.keys(stats.months).sort().map(key => stats.months[key]),
                        backgroundColor: 'rgba(170, 8, 8, 0.2)',
                        borderColor: 'rgba(170, 8, 8, 1)',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
   <style>
        :root {
            --primary-color: #b30000;
            --primary-dark: #800000;
            --primary-light: #990000;
            --secondary-color: #f8f9fa;
            --text-color: #333;
            --light-gray: #f5f5f5;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-color);
        }

        .sidebar {
            width: 250px;
            background-color: var(--primary-color);
            color: white;
            min-height: 100vh;
            position: fixed;
            transition: all 0.3s;
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
            transition: all 0.3s;
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
            transition: all 0.3s;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border: none;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table th {
            background-color: var(--primary-color);
            color: white;
        }

        .error-message {
            color: red;
            font-size: 12px;
            margin-bottom: 5px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                min-height: auto;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>