<?php
require_once __DIR__ . '/../../controller/ReclamationController.php';

$controller = new ReclamationController();
$reclamations = $controller->getReclamations();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Mes Réclamations - Share a ride</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@600;700&family=Ubuntu:wght@400;500&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Custom Styles for Table and Modal -->
    <style>
        .table-container {
            margin: 0 auto;
            max-width: 1200px;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: rgb(170, 8, 8);
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .action-buttons .btn {
            margin-right: 5px;
        }
        .details-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .details-modal.active {
            display: flex;
        }
        .details-modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            position: relative;
        }
        .close-modal {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
            background: none;
            border: none;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group textarea {
            height: 100px;
            resize: vertical;
        }
        .error-message {
            color: #e74c3c;
            font-size: 0.8rem;
            margin-top: 5px;
            display: none;
        }
        .valid-message {
            color: #2ecc71;
            font-size: 0.8rem;
            margin-top: 5px;
            display: none;
        }
        .error-message.show {
            display: block;
        }
        .valid-message.show {
            display: block;
        }
        .is-invalid {
            border-color: #e74c3c !important;
        }
        .is-valid {
            border-color: #2ecc71 !important;
        }
        .error-container {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-bottom: 15px;
            display: none;
        }
        .error-container.show {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Topbar Start -->
    <div class="container-fluid bg-light p-0">
        <div class="row gx-0 d-none d-lg-flex">
            <div class="col-lg-7 px-5 text-start">
                <div class="h-100 d-inline-flex align-items-center py-3 me-4">
                    <small class="fa fa-map-marker-alt text-primary me-2"></small>
                    <small>123 Rue de Marseille, Tunis</small>
                </div>
                <div class="h-100 d-inline-flex align-items-center py-3">
                    <small class="far fa-clock text-primary me-2"></small>
                    <small>24/7 - Service de réclamations</small>
                </div>
            </div>
            <div class="col-lg-5 px-5 text-end">
                <div class="h-100 d-inline-flex align-items-center py-3 me-4">
                    <small class="fa fa-phone-alt text-primary me-2"></small>
                    <small>+216 99 245 854</small>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="index.html" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary"><i class="fa fa-car me-3"></i>Share a ride</h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="acceuil.php" class="nav-item nav-link">Accueil</a>
				<a href="covoiturage.php" class="nav-item nav-link active">Co-voiturage</a>
                <a href="formulaireR.php" class="nav-item nav-link">Faire une réclamation</a>
                <a href="mes-reclamations.php" class="nav-item nav-link active">Mes réclamations</a>
				                        <a href="profil.php" class="nav-item nav-link active">Mon profil</a>

            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 p-0">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center">
                <h1 class="display-3 text-white mb-3 animated slideInDown">Mes Réclamations</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item"><a href="index.html">Accueil</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Mes Réclamations</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Reclamations Table -->
    <div class="table-container">
        <h2 class="mb-4">Liste des réclamations</h2>
        <?php if (empty($reclamations)): ?>
            <p>Aucune réclamation trouvée.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Nom du Chauffeur</th>
                        <th>Email</th>
                        <th>Date du Trajet</th>
                        <th>Sujet</th>
                        <th>Gravité</th>
                        <th>Pièce jointe</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reclamations as $reclamation): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reclamation['id']); ?></td>
                            <td><?php echo htmlspecialchars($reclamation['type']); ?></td>
                            <td><?php echo htmlspecialchars($reclamation['nom_chauffeur']); ?></td>
                            <td><?php echo htmlspecialchars($reclamation['email']); ?></td>
                            <td><?php echo htmlspecialchars($reclamation['date_trajet']); ?></td>
                            <td><?php echo htmlspecialchars($reclamation['sujet']); ?></td>
                            <td><?php echo htmlspecialchars($reclamation['gravite']); ?></td>
                            <td>
                                <?php if (!empty($reclamation['piece_jointe'])): ?>
                                    <?php
                                        $filePath = 'uploads/' . $reclamation['piece_jointe'];
                                        $fileExt = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                        if (in_array($fileExt, ['jpg', 'jpeg', 'png'])):
                                    ?>
                                        <img src="<?php echo htmlspecialchars($filePath); ?>" alt="Pièce jointe" style="max-width: 100px; max-height: 100px;">
                                    <?php elseif ($fileExt === 'pdf'): ?>
                                        <a href="<?php echo htmlspecialchars($filePath); ?>" target="_blank">Voir PDF</a>
                                    <?php else: ?>
                                        <a href="<?php echo htmlspecialchars($filePath); ?>" target="_blank">Télécharger</a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    Aucun fichier
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($reclamation['statut']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-warning edit-reclamation" data-reclamation='<?php echo json_encode($reclamation, JSON_HEX_APOS | JSON_HEX_QUOT); ?>'>
                                        <i class="fas fa-edit"></i> Modifier
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Edit Modal -->
    <div class="details-modal" id="editModal">
        <div class="details-modal-content">
            <button class="close-modal" onclick="closeEditModal()">×</button>
            <h3>Modifier la Réclamation</h3>
            <div class="error-container" id="formErrors"></div>
            <form id="editReclamationForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="editId" name="id">
                <div class="form-group">
                    <label for="editType">Type de réclamation *</label>
                    <select class="form-select" id="editType" name="type" required>
                        <option value="occupation">Problème d'occupation</option>
                        <option value="incident">Incident pendant le trajet</option>
                        <option value="feedback">Retour d'expérience</option>
                        <option value="other">Autre problème</option>
                    </select>
                    <span id="editTypeError" class="error-message"></span>
                    <span id="editTypeValid" class="valid-message"></span>
                </div>
                <div class="form-group">
                    <label for="editNomChauffeur">Nom de chauffeur *</label>
                    <input type="text" class="form-control" id="editNomChauffeur" name="nom_chauffeur" required>
                    <span id="editNomChauffeurError" class="error-message"></span>
                    <span id="editNomChauffeurValid" class="valid-message"></span>
                </div>
                <div class="form-group">
                    <label for="editEmail">Email *</label>
                    <input type="email" class="form-control" id="editEmail" name="email" required>
                    <span id="editEmailError" class="error-message"></span>
                    <span id="editEmailValid" class="valid-message"></span>
                </div>
                <div class="form-group">
                    <label for="editDateTrajet">Date du trajet *</label>
                    <input type="date" class="form-control" id="editDateTrajet" name="date_trajet" required>
                    <span id="editDateTrajetError" class="error-message"></span>
                    <span id="editDateTrajetValid" class="valid-message"></span>
                </div>
                <div class="form-group">
                    <label for="editSujet">Sujet *</label>
                    <input type="text" class="form-control" id="editSujet" name="sujet" required>
                    <span id="editSujetError" class="error-message"></span>
                    <span id="editSujetValid" class="valid-message"></span>
                </div>
                <div class="form-group">
                    <label for="editDescription">Description détaillée *</label>
                    <textarea class="form-control" id="editDescription" name="description" required></textarea>
                    <span id="editDescriptionError" class="error-message"></span>
                    <span id="editDescriptionValid" class="valid-message"></span>
                </div>
                <div class="form-group">
                    <label for="editGravite">Niveau de gravité *</label>
                    <select class="form-select" id="editGravite" name="gravite" required>
                        <option value="faible">Faible</option>
                        <option value="moyenne">Moyenne</option>
                        <option value="elevee">Élevée</option>
                        <option value="urgent">Urgent</option>
                    </select>
                    <span id="editGraviteError" class="error-message"></span>
                    <span id="editGraviteValid" class="valid-message"></span>
                </div>
                <div class="form-group">
                    <label for="editPieceJointe">Pièce jointe (optionnel, JPG/PNG/PDF, max 2MB)</label>
                    <input type="file" class="form-control" id="editPieceJointe" name="fichier" accept=".jpg,.jpeg,.png,.pdf">
                    <span id="editPieceJointeError" class="error-message"></span>
                    <span id="editPieceJointeValid" class="valid-message"></span>
                </div>
                <div class="form-group">
                    <label for="editStatut">Statut *</label>
                    <select class="form-select" id="editStatut" name="statut" required>
                        <option value="nouveau">Nouveau</option>
                        <option value="en_cours">En cours</option>
                        <option value="resolu">Résolu</option>
                        <option value="ferme">Fermé</option>
                    </select>
                    <span id="editStatutError" class="error-message"></span>
                    <span id="editStatutValid" class="valid-message"></span>
                </div>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Adresse</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>123 Rue de Marseille, Tunis</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+216 99 245 854</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>reclamations@shareride.fr</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Horaires</h4>
                    <h6 class="text-light">Service réclamations :</h6>
                    <p class="mb-4">24h/24 - 7j/7</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Liens rapides</h4>
                    <a class="btn btn-link" href="formulaireR.php">Faire une réclamation</a>
                    <a class="btn btn-link" href="mes-reclamations.php">Suivre ma réclamation</a>
                    <a class="btn btn-link" href="faq.html">FAQ</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Newsletter</h4>
                    <p>Abonnez-vous pour recevoir les actualités</p>
                    <div class="position-relative mx-auto" style="max-width: 400px;">
                        <input class="form-control border-0 w-100 py-3 ps-4 pe-5" type="text" placeholder="Votre email">
                        <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">S'inscrire</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        © <a class="border-bottom" href="#">Share a ride</a>, Tous droits réservés.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <!-- JavaScript for Modal and Form Handling -->
    <script>
    function openEditModal(reclamation) {
        try {
            document.getElementById('editId').value = reclamation.id || '';
            document.getElementById('editType').value = reclamation.type || '';
            document.getElementById('editNomChauffeur').value = reclamation.nom_chauffeur || '';
            document.getElementById('editEmail').value = reclamation.email || '';
            document.getElementById('editDateTrajet').value = reclamation.date_trajet || '';
            document.getElementById('editSujet').value = reclamation.sujet || '';
            document.getElementById('editDescription').value = reclamation.description || '';
            document.getElementById('editGravite').value = reclamation.gravite || '';
            document.getElementById('editStatut').value = reclamation.statut || '';
            document.getElementById('formErrors').classList.remove('show');
            document.getElementById('editModal').classList.add('active');
        } catch (e) {
            console.error('Error in openEditModal:', e);
            alert('Erreur lors de l\'ouverture du formulaire de modification.');
        }
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('active');
        document.querySelectorAll('.is-valid, .is-invalid').forEach(el => {
            el.classList.remove('is-valid', 'is-invalid');
        });
        document.querySelectorAll('.error-message, .valid-message').forEach(el => {
            el.classList.remove('show');
            el.textContent = '';
        });
        document.getElementById('formErrors').classList.remove('show');
        document.getElementById('editReclamationForm').reset();
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Event delegation for edit buttons
        document.querySelectorAll('.edit-reclamation').forEach(button => {
            button.addEventListener('click', function() {
                try {
                    const reclamation = JSON.parse(this.getAttribute('data-reclamation'));
                    openEditModal(reclamation);
                } catch (e) {
                    console.error('Error parsing reclamation data:', e);
                    alert('Erreur lors du chargement des données de la réclamation.');
                }
            });
        });

        // Form validation
        document.getElementById("editType").addEventListener("change", validateEditType);
        document.getElementById("editNomChauffeur").addEventListener("input", validateEditNomChauffeur);
        document.getElementById("editEmail").addEventListener("input", validateEditEmail);
        document.getElementById("editDateTrajet").addEventListener("change", validateEditDateTrajet);
        document.getElementById("editSujet").addEventListener("input", validateEditSujet);
        document.getElementById("editDescription").addEventListener("input", validateEditDescription);
        document.getElementById("editGravite").addEventListener("change", validateEditGravite);
        document.getElementById("editPieceJointe").addEventListener("change", validateEditPieceJointe);
        document.getElementById("editStatut").addEventListener("change", validateEditStatut);

        function validateEditType() {
            const type = document.getElementById("editType").value;
            const errorElement = document.getElementById("editTypeError");
            const validElement = document.getElementById("editTypeValid");

            if (!['occupation', 'incident', 'feedback', 'other'].includes(type)) {
                errorElement.textContent = "Veuillez sélectionner un type de réclamation valide.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("editType").classList.add("is-invalid");
                document.getElementById("editType").classList.remove("is-valid");
                return false;
            } else {
                errorElement.classList.remove("show");
                validElement.textContent = "✔ Champ valide";
                validElement.classList.add("show");
                document.getElementById("editType").classList.remove("is-invalid");
                document.getElementById("editType").classList.add("is-valid");
                return true;
            }
        }

        function validateEditNomChauffeur() {
            const nom = document.getElementById("editNomChauffeur").value.trim();
            const errorElement = document.getElementById("editNomChauffeurError");
            const validElement = document.getElementById("editNomChauffeurValid");

            if (!/^[a-zA-ZÀ-ÿ\s-]{3,}$/.test(nom)) {
                errorElement.textContent = "Le nom doit contenir au moins 3 caractères (lettres, espaces, tirets).";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("editNomChauffeur").classList.add("is-invalid");
                document.getElementById("editNomChauffeur").classList.remove("is-valid");
                return false;
            } else {
                errorElement.classList.remove("show");
                validElement.textContent = "✔ Champ valide";
                validElement.classList.add("show");
                document.getElementById("editNomChauffeur").classList.remove("is-invalid");
                document.getElementById("editNomChauffeur").classList.add("is-valid");
                return true;
            }
        }

        function validateEditEmail() {
            const email = document.getElementById("editEmail").value.trim();
            const errorElement = document.getElementById("editEmailError");
            const validElement = document.getElementById("editEmailValid");
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!email) {
                errorElement.textContent = "L'email est requis.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("editEmail").classList.add("is-invalid");
                document.getElementById("editEmail").classList.remove("is-valid");
                return false;
            } else if (!emailRegex.test(email)) {
                errorElement.textContent = "Veuillez entrer un email valide.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("editEmail").classList.add("is-invalid");
                document.getElementById("editEmail").classList.remove("is-valid");
                return false;
            } else {
                errorElement.classList.remove("show");
                validElement.textContent = "✔ Email valide";
                validElement.classList.add("show");
                document.getElementById("editEmail").classList.remove("is-invalid");
                document.getElementById("editEmail").classList.add("is-valid");
                return true;
            }
        }

        function validateEditDateTrajet() {
            const date = document.getElementById("editDateTrajet").value;
            const errorElement = document.getElementById("editDateTrajetError");
            const validElement = document.getElementById("editDateTrajetValid");
            const now = new Date();
            const selectedDate = new Date(date);
            const oneYearAgo = new Date();
            oneYearAgo.setFullYear(now.getFullYear() - 1);

            if (!date) {
                errorElement.textContent = "La date du trajet est requise.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("editDateTrajet").classList.add("is-invalid");
                document.getElementById("editDateTrajet").classList.remove("is-valid");
                return false;
            } else if (selectedDate > now) {
                errorElement.textContent = "La date ne peut pas être dans le futur.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("editDateTrajet").classList.add("is-invalid");
                document.getElementById("editDateTrajet").classList.remove("is-valid");
                return false;
            } else if (selectedDate < oneYearAgo) {
                errorElement.textContent = "La date ne peut pas être antérieure à 1 an.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("editDateTrajet").classList.add("is-invalid");
                document.getElementById("editDateTrajet").classList.remove("is-valid");
                return false;
            } else {
                errorElement.classList.remove("show");
                validElement.textContent = "✔ Champ valide";
                validElement.classList.add("show");
                document.getElementById("editDateTrajet").classList.remove("is-invalid");
                document.getElementById("editDateTrajet").classList.add("is-valid");
                return true;
            }
        }

        function validateEditSujet() {
            const sujet = document.getElementById("editSujet").value.trim();
            const errorElement = document.getElementById("editSujetError");
            const validElement = document.getElementById("editSujetValid");

            if (sujet.length < 5) {
                errorElement.textContent = "Le sujet doit contenir au moins 5 caractères.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("editSujet").classList.add("is-invalid");
                document.getElementById("editSujet").classList.remove("is-valid");
                return false;
            } else if (sujet.length > 255) {
                errorElement.textContent = "Le sujet ne doit pas dépasser 255 caractères.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("editSujet"). projective.add("is-invalid");
                document.getElementById("editSujet").classList.remove("is-valid");
                return false;
            } else {
                errorElement.classList.remove("show");
                validElement.textContent = "✔ Champ valide";
                validElement.classList.add("show");
                document.getElementById("editSujet").classList.remove("is-invalid");
                document.getElementById("editSujet").classList.add("is-valid");
                return true;
            }
        }

        function validateEditDescription() {
            const description = document.getElementById("editDescription").value.trim();
            const errorElement = document.getElementById("editDescriptionError");
            const validElement = document.getElementById("editDescriptionValid");
            const wordCount = description ? description.split(/\s+/).filter(word => word.length > 0).length : 0;

            if (wordCount < 10) {
                errorElement.textContent = "La description doit contenir au moins 10 mots.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("editDescription").classList.add("is-invalid");
                document.getElementById("editDescription").classList.remove("is-valid");
                return false;
            } else if (wordCount > 500) {
                errorElement.textContent = `La description ne doit pas dépasser 500 mots (${wordCount}/500).`;
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("editDescription").classList.add("is-invalid");
                document.getElementById("editDescription").classList.remove("is-valid");
                return false;
            } else {
                errorElement.classList.remove("show");
                validElement.textContent = `✔ Champ valide (${wordCount}/500 mots)`;
                validElement.classList.add("show");
                document.getElementById("editDescription").classList.remove("is-invalid");
                document.getElementById("editDescription").classList.add("is-valid");
                return true;
            }
        }

        function validateEditGravite() {
            const gravite = document.getElementById("editGravite").value;
            const errorElement = document.getElementById("editGraviteError");
            const validElement = document.getElementById("editGraviteValid");

            if (!['faible', 'moyenne', 'elevee', 'urgent'].includes(gravite)) {
                errorElement.textContent = "Veuillez sélectionner un niveau de gravité valide.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("editGravite").classList.add("is-invalid");
                document.getElementById("editGravite").classList.remove("is-valid");
                return false;
            } else {
                errorElement.classList.remove("show");
                validElement.textContent = "✔ Champ valide";
                validElement.classList.add("show");
                document.getElementById("editGravite").classList.remove("is-invalid");
                document.getElementById("editGravite").classList.add("is-valid");
                return true;
            }
        }

        function validateEditPieceJointe() {
            const file = document.getElementById("editPieceJointe").files[0];
            const errorElement = document.getElementById("editPieceJointeError");
            const validElement = document.getElementById("editPieceJointeValid");

            if (!file) {
                errorElement.classList.remove("show");
                validElement.classList.remove("show");
                document.getElementById("editPieceJointe").classList.remove("is-invalid", "is-valid");
                return true; // File is optional
            }

            const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            const maxSize = 2 * 1024 * 1024; // 2MB

            if (!allowedTypes.includes(file.type)) {
                errorElement.textContent = "Seuls les fichiers JPG, PNG ou PDF sont acceptés.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("editPieceJointe").classList.add("is-invalid");
                document.getElementById("editPieceJointe").classList.remove("is-valid");
                return false;
            } else if (file.size > maxSize) {
                errorElement.textContent = "Le fichier ne doit pas dépasser 2MB.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("editPieceJointe").classList.add("is-invalid");
                document.getElementById("editPieceJointe").classList.remove("is-valid");
                return false;
            } else {
                errorElement.classList.remove("show");
                validElement.textContent = "✔ Fichier valide";
                validElement.classList.add("show");
                document.getElementById("editPieceJointe").classList.remove("is-invalid");
                document.getElementById("editPieceJointe").classList.add("is-valid");
                return true;
            }
        }

        function validateEditStatut() {
            const statut = document.getElementById("editStatut").value;
            const errorElement = document.getElementById("editStatutError");
            const validElement = document.getElementById("editStatutValid");

            if (!['nouveau', 'en_cours', 'resolu', 'ferme'].includes(statut)) {
                errorElement.textContent = "Veuillez sélectionner un statut valide.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("editStatut").classList.add("is-invalid");
                document.getElementById("editStatut").classList.remove("is-valid");
                return false;
            } else {
                errorElement.classList.remove("show");
                validElement.textContent = "✔ Champ valide";
                validElement.classList.add("show");
                document.getElementById("editStatut").classList.remove("is-invalid");
                document.getElementById("editStatut").classList.add("is-valid");
                return true;
            }
        }

        document.getElementById("editReclamationForm").addEventListener("submit", function(e) {
            e.preventDefault();

            let isValid = true;
            document.getElementById('formErrors').classList.remove('show');

            if (!validateEditType()) isValid = false;
            if (!validateEditNomChauffeur()) isValid = false;
            if (!validateEditEmail()) isValid = false;
            if (!validateEditDateTrajet()) isValid = false;
            if (!validateEditSujet()) isValid = false;
            if (!validateEditDescription()) isValid = false;
            if (!validateEditGravite()) isValid = false;
            if (!validateEditPieceJointe()) isValid = false;
            if (!validateEditStatut()) isValid = false;

            if (isValid) {
                const submitBtn = document.querySelector("#editReclamationForm button[type='submit']");
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mise à jour...';
                submitBtn.disabled = true;

                const formData = new FormData(this);
                fetch('update_reclamation.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    submitBtn.innerHTML = 'Mettre à jour';
                    submitBtn.disabled = false;

                    if (data.success) {
                        closeEditModal();
                        alert(data.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        const errorContainer = document.getElementById('formErrors');
                        errorContainer.innerHTML = '<strong>Erreurs :</strong><ul>' + 
                            (data.errors ? data.errors.map(error => `<li>${error}</li>`).join('') : `<li>${data.message}</li>`) +
                            '</ul>';
                        errorContainer.classList.add('show');
                    }
                })
                .catch(error => {
                    submitBtn.innerHTML = 'Mettre à jour';
                    submitBtn.disabled = false;
                    const errorContainer = document.getElementById('formErrors');
                    errorContainer.innerHTML = '<strong>Erreur réseau :</strong> ' + error.message;
                    errorContainer.classList.add('show');
                });
            }
        });
    });
    </script>
</body>
</html>