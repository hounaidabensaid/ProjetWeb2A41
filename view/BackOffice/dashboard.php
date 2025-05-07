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
    <title>Dashboard - ShareRide BackOffice</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .search {
            position: relative;
            display: flex;
            align-items: center;
        }

        .sort-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            margin-left: 5px;
            font-size: 1.2em;
            color: #333;
        }

        .sort-btn:hover {
            color: rgb(170, 8, 8);
        }
        
        .reset-sort-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            margin-left: 5px;
            font-size: 1.2em;
            color: #333;
        }

        .reset-sort-btn:hover {
            color: rgb(170, 8, 8);
        }
        .sort-menu {
            position: absolute;
            top: 40px;
            right: 0;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        .sort-menu label {
            display: block;
            margin-bottom: 5px;
            font-size: 0.9em;
        }

        .sort-menu select, .sort-menu button {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .sort-menu button {
            background: rgb(170, 8, 8);
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .sort-menu button:hover {
            background: rgb(170, 8, 8);
        }
    </style>
</head>
<body>
    <!-- Barre latérale -->
    <div class="sidebar">
        <h1>Share a ride</h1>
        <div class="menu-item active"><i class="fas fa-tachometer-alt"></i> Dashboard</div>
        <div class="menu-item"><i class="fas fa-file-alt"></i> Réclamations</div>
        <div class="menu-item"><i class="fas fa-users"></i> Utilisateurs</div>
        <div class="menu-item"><i class="fas fa-chart-pie"></i> <a href="statistics.php" style="color: white; text-decoration: none;">Statistiques</a></div>
        <div class="menu-item"><i class="fas fa-cog"></i> Paramètres</div>
    </div>

    <!-- Dashboard -->
    <div class="dashboard">
        <!-- Overlay pour assombrir l'arrière-plan -->
        <div class="overlay" id="overlay" onclick="closeResponsePanel()"></div>

        <div class="header">
            <h2>Gestion des Réclamations</h2>
            <div class="search">
                <input type="text" id="searchInput" placeholder="Rechercher une réclamation...">
                <button class="sort-btn" onclick="toggleSortMenu()">
                    <i class="fas fa-sort-amount-down"></i>
                </button>
                <button class="reset-sort-btn" onclick="resetSort()" title="Réinitialiser le tri">
                    <i class="fas fa-undo"></i>
                </button>
                <div class="sort-menu" id="sortMenu" style="display: none;">
                    <label for="sortColumn">Trier par :</label>
                    <select id="sortColumn">
                        <option value="0" data-type="number">ID</option>
                        <option value="1" data-type="string">Type</option>
                        <option value="2" data-type="string">Nom du Chauffeur</option>
                        <option value="3" data-type="date">Date du Trajet</option>
                        <option value="4" data-type="string">Sujet</option>
                        <option value="5" data-type="string">Gravité</option>
                        <option value="6" data-type="string">Statut</option>
                        <option value="7" data-type="string">Email</option> <!-- Nouvelle option pour email -->
                    </select>
                    <label for="sortOrder">Ordre :</label>
                    <select id="sortOrder">
                        <option value="asc">Croissant</option>
                        <option value="desc">Décroissant</option>
                    </select>
                    <button onclick="applySort()">Appliquer</button>
                </div>
            </div>
        </div>

        <!-- Contrôles du tableau -->
        <button class="toggle-table-btn" onclick="toggleTable()">
            <i class="fas fa-chevron-up"></i> Réduire/Agrandir le tableau
        </button>
        <button class="toggle-table-btn">
           <a href="view_reponse.php"><i class="fas fa-info-circle"></i> Liste réponses</a> 
        </button>
        <a href="generate_reclamations_pdf.php" target="_blank">
            <button class="toggle-table-btn">
                <i class="fas fa-file-pdf"></i> Exporter en PDF
            </button>
        </a>

        <!-- Tableau des réclamations -->
        <div class="collapsible-table" id="reclamationTable">
            <table>
                <thead>
                    <tr>
                        <th onclick="sortTable(0, 'number')">ID <i class="fas fa-sort"></i></th>
                        <th onclick="sortTable(1, 'string')">Type <i class="fas fa-sort"></i></th>
                        <th onclick="sortTable(2, 'string')">Nom du Chauffeur <i class="fas fa-sort"></i></th>
                        <th onclick="sortTable(3, 'date')">Date du Trajet <i class="fas fa-sort"></i></th>
                        <th onclick="sortTable(4, 'string')">Sujet <i class="fas fa-sort"></i></th>
                        <th onclick="sortTable(5, 'string')">Gravité <i class="fas fa-sort"></i></th>
                        <th onclick="sortTable(6, 'string')">Statut <i class="fas fa-sort"></i></th>
                        <th onclick="sortTable(7, 'string')">Email <i class="fas fa-sort"></i></th> <!-- Nouvelle colonne email -->
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
                            <td><?php echo htmlspecialchars($reclamation['email']); ?></td> <!-- Affichage de l'email -->
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-primary" onclick="window.location.href='add_reponse.php?reclamation_id=<?php echo $reclamation['id']; ?>'">
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

    <!-- Modal des détails -->
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
        // Basculer l'état du tableau
        function toggleTable() {
            const table = document.getElementById('reclamationTable');
            const toggleBtn = document.querySelector('.toggle-table-btn i');
            table.classList.toggle('collapsed');
            toggleBtn.classList.toggle('fa-chevron-up');
            toggleBtn.classList.toggle('fa-chevron-down');
        }

        // Ouvre le panneau de réponse (en passant l'ID de la réclamation)
        function openResponsePanel2(id) {
            const responsePanel = document.getElementById('responsePanel');
            const overlay = document.getElementById('overlay');
            responsePanel.style.display = 'block';
            overlay.style.display = 'block';
            document.getElementById('reclamationId').value = id;
            document.getElementById('responseForm').reset();
        }

        // Ferme le panneau de réponse
        function closeResponsePanel2() {
            const responsePanel = document.getElementById('responsePanel');
            const overlay = document.getElementById('overlay');
            responsePanel.style.display = 'none';
            overlay.style.display = 'none';
        }

        // Ouvre le panneau de réponse
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

        // Ferme le panneau de réponse
        function closeResponsePanel() {
            document.getElementById('responsePanel').classList.remove('active');
            document.getElementById('overlay').classList.remove('active');
        }

        // Ouvre le modal des détails
        function openDetailsModal(id, description) {
            document.getElementById('detailsDescription').textContent = description;
            document.getElementById('detailsModal').classList.add('active');
            document.getElementById('modalOverlay').classList.add('active');
        }

        // Ferme le modal des détails
        function closeDetailsModal() {
            document.getElementById('detailsModal').classList.remove('active');
            document.getElementById('modalOverlay').classList.remove('active');
        }

        // Génère le PDF
        function generatePDF() {
            const originalText = event.target.innerHTML;
            event.target.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération PDF...';
            const pdfWindow = window.open('generate_reclamations_pdf.php', '_blank');
            pdfWindow.onload = function() {
                event.target.innerHTML = originalText;
            };
            setTimeout(() => {
                event.target.innerHTML = originalText;
            }, 3000);
        }

        // Supprime une réclamation
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
                        alert('Erreur : ' + data.message);
                    }
                });
            }
        }

        // Fonctionnalité de recherche
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#reclamationTableBody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Fonctionnalité de tri
        let sortDirection = {};
        
        function sortTable(columnIndex, dataType) {
            const table = document.getElementById('reclamationTableBody');
            const rows = Array.from(table.querySelectorAll('tr'));
            
            // Basculer la direction du tri
            sortDirection[columnIndex] = !sortDirection[columnIndex];
            const direction = sortDirection[columnIndex] ? 1 : -1;
            
            // Mettre à jour les icônes de tri
            document.querySelectorAll('th i').forEach(icon => icon.className = 'fas fa-sort');
            const currentTh = document.querySelector(`th:nth-child(${columnIndex + 1}) i`);
            currentTh.className = `fas ${sortDirection[columnIndex] ? 'fa-sort-up' : 'fa-sort-down'}`;
            
            // Trier les lignes
            rows.sort((rowA, rowB) => {
                let valueA = rowA.cells[columnIndex].textContent.trim();
                let valueB = rowB.cells[columnIndex].textContent.trim();
                
                // Gérer différents types de données
                if (dataType === 'number') {
                    valueA = parseFloat(valueA) || 0;
                    valueB = parseFloat(valueB) || 0;
                } else if (dataType === 'date') {
                    valueA = new Date(valueA);
                    valueB = new Date(valueB);
                    // Gérer les dates invalides
                    if (isNaN(valueA)) valueA = new Date(0);
                    if (isNaN(valueB)) valueB = new Date(0);
                } else {
                    valueA = valueA.toLowerCase();
                    valueB = valueB.toLowerCase();
                }
                
                if (valueA < valueB) return -1 * direction;
                if (valueA > valueB) return 1 * direction;
                return 0;
            });
            
            // Effacer et ré-ajouter les lignes
            table.innerHTML = '';
            rows.forEach(row => table.appendChild(row));
        }

        // Basculer le menu de tri
        function toggleSortMenu() {
            const sortMenu = document.getElementById('sortMenu');
            sortMenu.style.display = sortMenu.style.display === 'none' ? 'block' : 'none';
        }

        // Appliquer le tri
        function applySort() {
            const sortColumn = document.getElementById('sortColumn');
            const columnIndex = sortColumn.value;
            const dataType = sortColumn.options[sortColumn.selectedIndex].getAttribute('data-type');
            const sortOrder = document.getElementById('sortOrder').value;
            
            // Définir la direction du tri en fonction de l'ordre sélectionné
            sortDirection[columnIndex] = sortOrder === 'asc';
            
            // Appeler la fonction de tri existante
            sortTable(columnIndex, dataType);
            
            // Mettre à jour l'icône de tri dans l'en-tête du tableau
            document.querySelectorAll('th i').forEach(icon => icon.className = 'fas fa-sort');
            const currentTh = document.querySelector(`th:nth-child(${parseInt(columnIndex) + 1}) i`);
            currentTh.className = `fas ${sortOrder === 'asc' ? 'fa-sort-up' : 'fa-sort-down'}`;
            
            // Fermer le menu de tri
            toggleSortMenu();
        }

        // Fermer le menu de tri en cliquant à l'extérieur
        document.addEventListener('click', function(event) {
            const sortMenu = document.getElementById('sortMenu');
            const sortBtn = document.querySelector('.sort-btn');
            if (!sortMenu.contains(event.target) && !sortBtn.contains(event.target)) {
                sortMenu.style.display = 'none';
            }
        });
        
        // Réinitialiser le tri
        function resetSort() {
            const table = document.getElementById('reclamationTableBody');
            const rows = Array.from(table.querySelectorAll('tr'));
            
            // Réinitialiser la direction du tri
            sortDirection = {};
            
            // Réinitialiser les icônes de tri
            document.querySelectorAll('th i').forEach(icon => icon.className = 'fas fa-sort');
            
            // Restaurer l'ordre original (basé sur l'ID croissant)
            table.innerHTML = '';
            rows.sort((a, b) => {
                const idA = parseFloat(a.cells[0].textContent) || 0;
                const idB = parseFloat(b.cells[0].textContent) || 0;
                return idA - idB;
            }).forEach(row => table.appendChild(row));
        }
    </script>
</body>
</html>