<?php
require_once '../../config.php';
require_once '../../model/Voiture.php';
require_once '../../controller/VoitureController.php';




$controller = new VoitureController();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id_voiture'] ?? null;
        $matricule = trim($_POST['matricule']);
        $marque = trim($_POST['marque']);
        $modele = trim($_POST['modele']);
        $couleur = trim($_POST['couleur']);
        $nb_place = intval($_POST['nb_place']);
        $statut = trim($_POST['statut']);

        // Validation des données
        if (empty($matricule) || empty($marque) || empty($modele) || empty($couleur) || $nb_place <= 0) {
            throw new Exception("Tous les champs sont obligatoires");
        }

        $voiture = new Voiture($id, $matricule, $marque, $modele, $couleur, $nb_place, $statut);

        if ($id) {
            $controller->updateVoiture($voiture);
            $_SESSION['message'] = "Voiture mise à jour avec succès";
        } else {
            $controller->addVoiture($voiture);
            $_SESSION['message'] = "Voiture ajoutée avec succès";
        }
        
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Suppression
if (isset($_GET['delete'])) {
    try {
        $controller->deleteVoiture($_GET['delete']);
        $_SESSION['message'] = "Voiture supprimée avec succès";
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Pré-remplissage pour modification
$voitureToEdit = null;
if (isset($_GET['edit'])) {
    $voitureToEdit = $controller->getVoitureById($_GET['edit']);
    if (!$voitureToEdit) {
        $_SESSION['error'] = "Voiture introuvable";
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
    }
}

// Liste des voitures
$voituress = $controller->getAllVoitures();include 'header.php'
?>
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="mb-0">Gestion des Voitures</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Voitures</li>
                        </ol>
                    </nav>
                </div>
            </div>

              
            <div class="row">
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><?= $voitureToEdit ? 'Modifier' : 'Ajouter' ?> une Voiture</h5>
                        </div>
                        <div class="card-body">
						
                           <form method="POST" id="voiture-form" novalidate>
    <input type="hidden" id="id_voiture" name="id_voiture" value="<?= $voitureToEdit ? $voitureToEdit->getId() : '' ?>">

    <div class="mb-3">
        <label for="matricule" class="form-label">Matricule</label>
        <input type="text" class="form-control" id="matricule" name="matricule"
               value="<?= $voitureToEdit ? htmlspecialchars($voitureToEdit->getMatricule()) : '' ?>" required>
        <div class="text-danger error-message" id="matricule-error"></div>
    </div>

    <div class="mb-3">
        <label for="marque" class="form-label">Marque</label>
        <input type="text" class="form-control" id="marque" name="marque"
               value="<?= $voitureToEdit ? htmlspecialchars($voitureToEdit->getMarque()) : '' ?>" required>
        <div class="text-danger error-message" id="marque-error"></div>
    </div>

    <div class="mb-3">
        <label for="modele" class="form-label">Modèle</label>
        <input type="text" class="form-control" id="modele" name="modele"
               value="<?= $voitureToEdit ? htmlspecialchars($voitureToEdit->getModele()) : '' ?>" required>
        <div class="text-danger error-message" id="modele-error"></div>
    </div>

    <div class="mb-3">
        <label for="couleur" class="form-label">Couleur</label>
        <input type="text" class="form-control" id="couleur" name="couleur"
               value="<?= $voitureToEdit ? htmlspecialchars($voitureToEdit->getCouleur()) : '' ?>" required>
        <div class="text-danger error-message" id="couleur-error"></div>
    </div>

    <div class="mb-3">
        <label for="nb_place" class="form-label">Nombre de places</label>
        <input type="number" class="form-control" id="nb_place" name="nb_place" min="1"
               value="<?= $voitureToEdit ? htmlspecialchars($voitureToEdit->getNbPlace()) : '' ?>" required>
        <div class="text-danger error-message" id="nb_place-error"></div>
    </div>

    <div class="mb-3">
        <label for="statut" class="form-label">Statut</label>
        <select class="form-select" id="statut" name="statut" required>
            <option value="">-- Choisir un statut --</option>
            <option value="disponible" <?= ($voitureToEdit && $voitureToEdit->getStatut() === "disponible") ? 'selected' : '' ?>>Disponible</option>
            <option value="en service" <?= ($voitureToEdit && $voitureToEdit->getStatut() === "en service") ? 'selected' : '' ?>>En service</option>
            <option value="réservé" <?= ($voitureToEdit && $voitureToEdit->getStatut() === "réservé") ? 'selected' : '' ?>>Réservé</option>
        </select>
        <div class="text-danger error-message" id="statut-error"></div>
    </div>

    <button type="submit" class="btn btn-primary w-100">
        <i class="fas fa-save me-2"></i><?= $voitureToEdit ? 'Modifier' : 'Ajouter' ?>
    </button>

    <?php if ($voitureToEdit): ?>
        <a href="index_voiture.php" class="btn btn-outline-secondary w-100 mt-2">
            <i class="fas fa-times me-2"></i>Annuler
        </a>
    <?php endif; ?>
</form>

                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Liste des Voitures</h5>
            <span class="badge bg-primary"><?= count($voituress) ?> voitures</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Matricule</th>
                            <th>Marque</th>
                            <th>Modèle</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($voituress)): ?>
                            <tr>
                                <td colspan="5" class="text-center">Aucune voiture enregistrée</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($voituress as $voiture): ?>
                                <tr>
                                    <td><?= htmlspecialchars($voiture->getMatricule()) ?></td>
                                    <td><?= htmlspecialchars($voiture->getMarque()) ?></td>
                                    <td><?= htmlspecialchars($voiture->getModele()) ?></td>
                                    <td>
                                        <?php 
                                            $statut = $voiture->getStatut();
                                            $badgeClass = 'bg-secondary';
                                            if ($statut === 'disponible') $badgeClass = 'bg-success';
                                            elseif ($statut === 'en service') $badgeClass = 'bg-warning text-dark';
                                            elseif ($statut === 'réservé') $badgeClass = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= ucfirst($statut) ?></span>
                                    </td>
                                    <td class="action-btns">
                                       
    <a href="http://localhost/ShareRide1/ShareRide/view/backoffice/index_voiture.php?edit=<?= $voiture->getId() ?>" class="btn btn-sm btn-outline-primary me-1" title="Modifier">
        <i class="fas fa-edit"></i>
    </a>

    <!-- Bouton Supprimer avec confirmation -->
    <a href="http://localhost/ShareRide1/ShareRide/view/backoffice/index_voiture.php?delete=<?= $voiture->getId() ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette voiture ?')" class="btn btn-sm btn-outline-danger" title="Supprimer">
        <i class="fas fa-trash"></i>
    </a>

</td>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script pour gérer les messages flash et autres interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Fermer automatiquement les alertes après 5 secondes
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
            
            // Confirmation avant suppression
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Êtes-vous sûr de vouloir supprimer cette voiture ?')) {
                        e.preventDefault();
                    }
                });
            });
        });
		
		
    </script>
	
	
	<script>
    const validMarques = ['Toyota', 'Peugeot', 'Renault', 'Volkswagen', 'Fiat']; // exemple
    const validColors = ['rouge', 'noir', 'blanc', 'bleu', 'gris', 'vert']; // exemple

    document.getElementById('voiture-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const id = document.getElementById('id_voiture').value;
        const matricule = document.getElementById('matricule').value.trim();
        const marque = document.getElementById('marque').value.trim();
        const modele = document.getElementById('modele').value.trim();
        const couleur = document.getElementById('couleur').value.trim().toLowerCase();
        const nb_place = document.getElementById('nb_place').value.trim();
        const statut = document.getElementById('statut').value.trim();

        document.querySelectorAll('.error-message').forEach(function (elem) {
            elem.textContent = '';
        });

        let errorMessage = false;
        const matriculeRegex = /^\d+TN\d+$/;

        if (!matricule || !matriculeRegex.test(matricule)) {
            document.getElementById('matricule-error').textContent = "Le matricule doit être dans le format: [numéro]TN[numéro] (ex: 12345TN67890).";
            errorMessage = true;
        }

        if (!validMarques.includes(marque)) {
            document.getElementById('marque-error').textContent = `La marque doit être l'une des suivantes : ${validMarques.join(', ')}.`;
            errorMessage = true;
        }

        if (!modele || modele.length < 7) {
            document.getElementById('modele-error').textContent = "Le modèle doit contenir au moins 7 caractères.";
            errorMessage = true;
        }

        if (!validColors.includes(couleur)) {
            document.getElementById('couleur-error').textContent = `La couleur doit être l'une des suivantes : ${validColors.join(', ')}.`;
            errorMessage = true;
        }

        if (!nb_place || isNaN(nb_place) || nb_place < 2 || nb_place > 7) {
            document.getElementById('nb_place-error').textContent = "Le nombre de places doit être un nombre entre 2 et 7.";
            errorMessage = true;
        }

        if (!statut) {
            document.getElementById('statut-error').textContent = "Le statut est obligatoire.";
            errorMessage = true;
        }

        if (!errorMessage) {
            // Soumettre le formulaire en cas de succès
            e.target.submit();
        }
    });
</script>

<?php
require_once 'footer.php';
?>