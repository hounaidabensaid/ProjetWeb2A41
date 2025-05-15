<?php
// Calculate total counts for percentages
$totalDepart = array_sum(array_column($stats['depart'], 'total'));
$totalArrivee = array_sum(array_column($stats['arrivee'], 'total'));
?>

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
