<?php
require_once __DIR__ . '/../../controller/ReclamationController.php';

$controller = new ReclamationController();
$reclamations = $controller->getReclamations();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ShareRide BackOffice</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h1>ShareRide</h1>
        <div class="menu-item active"><i class="fas fa-tachometer-alt"></i> Dashboard</div>
        <div class="menu-item"><i class="fas fa-file-alt"></i> Réclamations</div>
        <div class="menu-item"><i class="fas fa-users"></i> Utilisateurs</div>
        <div class="menu-item"><i class="fas fa-cog"></i> Paramètres</div>
    </div>

    <!-- Dashboard -->
    <div class="dashboard">
        <div class="header">
            <h2>Gestion des Réclamations</h2>
            <div class="search">
                <input type="text" id="searchInput" placeholder="Rechercher une réclamation...">
            </div>
        </div>

        <!-- Table Controls -->
        <button class="toggle-table-btn" onclick="toggleTable()">
            <i class="fas fa-chevron-up"></i> Réduire/Agrandir le tableau
        </button>

        <!-- Reclamations Table -->
        <div class="collapsible-table" id="reclamationTable">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Nom du Chauffeur</th>
                        <th>Date du Trajet</th>
                        <th>Sujet</th>
                        <th>Gravité</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="reclamationTableBody">
                    <?php foreach ($reclamations as $reclamation): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reclamation['id']); ?></td>
                            <td><?php echo htmlspecialchars($reclamation['type']); ?></td>
                            <td><?php echo htmlspecialchars($reclamation['nom_chauffeur']); ?></td>
                            <td><?php echo htmlspecialchars($reclamation['date_trajet']); ?></td>
                            <td><?php echo htmlspecialchars($reclamation['sujet']); ?></td>
                            <td><?php echo htmlspecialchars($reclamation['gravite']); ?></td>
                            <td><?php echo htmlspecialchars($reclamation['statut']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-primary" onclick="openResponsePanel(<?php echo $reclamation['id']; ?>)">
                                        <i class="fas fa-reply"></i> Répondre
                                    </button>
                                    <button class="btn btn-info" onclick="openDetailsModal(<?php echo $reclamation['id']; ?>, '<?php echo htmlspecialchars(addslashes($reclamation['description'])); ?>')">
                                        <i class="fas fa-info-circle"></i> Détails
                                    </button>
                                    <button class="btn btn-danger" onclick="deleteReclamation(<?php echo $reclamation['id']; ?>)">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Response Panel -->
    <!-- Note: The Response Panel section is missing in the provided code. If needed, it can be added back. -->

    <!-- Details Modal -->
    <div class="details-modal" id="detailsModal">
        <div class="details-modal-content">
            <button class="close-modal" onclick="closeDetailsModal()">×</button>
            <h3>Détails de la Réclamation</h3>
            <div class="form-group">
                <label>Description</label>
                <p id="detailsDescription"></p>
            </div>
        </div>
    </div>

    <!-- Overlays -->
    <div class="overlay" id="overlay" onclick="closeResponsePanel()"></div>
    <div class="modal-overlay" id="modalOverlay" onclick="closeDetailsModal()"></div>

    <!-- JavaScript -->
    <script>
        // Toggle Table Collapse
        function toggleTable() {
            const table = document.getElementById('reclamationTable');
            const toggleBtn = document.querySelector('.toggle-table-btn i');
            table.classList.toggle('collapsed');
            toggleBtn.classList.toggle('fa-chevron-up');
            toggleBtn.classList.toggle('fa-chevron-down');
        }

        // Open Response Panel
        function openResponsePanel(id) {
            fetch(`get_reclamation.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('responsePanel').classList.add('active');
                    document.getElementById('overlay').classList.add('active');
                    document.getElementById('reclamationId').value = data.id;
                    document.getElementById('responseType').textContent = data.type;
                    document.getElementById('responseNomChauffeur').textContent = data.nom_chauffeur;
                    document.getElementById('responseDateTrajet').textContent = data.date_trajet;
                    document.getElementById('responseSujet').textContent = data.sujet;
                    document.getElementById('responseDescription').textContent = data.description;
                    document.getElementById('responseGravite').textContent = data.gravite;
                    document.getElementById('responseStatut').value = data.statut;
                    document.getElementById('reponse').value = data.reponse || '';
                });
        }

        // Close Response Panel
        function closeResponsePanel() {
            document.getElementById('responsePanel').classList.remove('active');
            document.getElementById('overlay').classList.remove('active');
        }

        // Open Details Modal
        function openDetailsModal(id, description) {
            document.getElementById('detailsDescription').textContent = description;
            document.getElementById('detailsModal').classList.add('active');
            document.getElementById('modalOverlay').classList.add('active');
        }

        // Close Details Modal
        function closeDetailsModal() {
            document.getElementById('detailsModal').classList.remove('active');
            document.getElementById('modalOverlay').classList.remove('active');
        }

        // Delete Reclamation
        function deleteReclamation(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?')) {
                fetch('delete_reclamation.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erreur: ' + data.message);
                    }
                });
            }
        }

        // Search Functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#reclamationTableBody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
</body>
</html>