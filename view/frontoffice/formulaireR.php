<?php
// No session_start() or login checks since we’re not tracking users
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Formulaire de Réclamation - ShareRide</title>
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

    <!-- CSS for Validation Messages -->
    <style>
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
                    <small>123 Rue de Paris, France</small>
                </div>
                <div class="h-100 d-inline-flex align-items-center py-3">
                    <small class="far fa-clock text-primary me-2"></small>
                    <small>24/7 - Service de réclamations</small>
                </div>
            </div>
            <div class="col-lg-5 px-5 text-end">
                <div class="h-100 d-inline-flex align-items-center py-3 me-4">
                    <small class="fa fa-phone-alt text-primary me-2"></small>
                    <small>+33 6 12 34 56 78</small>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="index.html" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary"><i class="fa fa-car me-3"></i>ShareRide</h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="index.html" class="nav-item nav-link">Accueil</a>
                <a href="formulaireR.php" class="nav-item nav-link active">Faire une réclamation</a>
                <a href="mes-reclamations.php" class="nav-item nav-link">Mes réclamations</a>
                <a href="contact.html" class="nav-item nav-link">Contact</a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 p-0">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center">
                <h1 class="display-3 text-white mb-3 animated slideInDown">Formulaire de Réclamation</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item"><a href="index.html">Accueil</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Réclamation</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Feedback Messages -->
    <div id="feedbackMessages" class="container"></div>

    <!-- Formulaire Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="text-primary text-uppercase">// Formulaire //</h6>
                    <h1 class="mb-4">Déposer une réclamation</h1>
                    <p class="mb-4">Remplissez ce formulaire pour signaler un problème rencontré lors de votre trajet. Notre équipe traitera votre demande dans les plus brefs délais.</p>
                    <div class="bg-light p-4">
                        <h5 class="mb-3">Informations importantes</h5>
                        <p><i class="fa fa-check text-primary me-3"></i>Tous les champs marqués d'un * sont obligatoires</p>
                        <p><i class="fa fa-check text-primary me-3"></i>Votre réclamation sera traitée sous 24h</p>
                        <p><i class="fa fa-check text-primary me-3"></i>Vous recevrez une confirmation par email</p>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                    <form id="reclamationForm" method="POST" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="" selected disabled>Sélectionnez un type</option>
                                        <option value="occupation">Problème d'occupation</option>
                                        <option value="incident">Incident pendant le trajet</option>
                                        <option value="feedback">Retour d'expérience</option>
                                        <option value="other">Autre problème</option>
                                    </select>
                                    <label for="type">Type de réclamation *</label>
                                    <span id="typeError" class="error-message"></span>
                                    <span id="typeValid" class="valid-message"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="nom_chauffeur" name="nom_chauffeur" placeholder="Nom de chauffeur" required>
                                    <label for="nom_chauffeur">Nom de chauffeur *</label>
                                    <span id="nom_chauffeurError" class="error-message"></span>
                                    <span id="nom_chauffeurValid" class="valid-message"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="date_trajet" name="date_trajet" required>
                                    <label for="date_trajet">Date du trajet *</label>
                                    <span id="date_trajetError" class="error-message"></span>
                                    <span id="date_trajetValid" class="valid-message"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="sujet" name="sujet" placeholder="Sujet de la réclamation" required>
                                    <label for="sujet">Sujet *</label>
                                    <span id="sujetError" class="error-message"></span>
                                    <span id="sujetValid" class="valid-message"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Décrivez votre problème en détail" id="description" name="description" style="height: 150px" required></textarea>
                                    <label for="description">Description détaillée *</label>
                                    <span id="descriptionError" class="error-message"></span>
                                    <span id="descriptionValid" class="valid-message"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <select class="form-select" id="gravite" name="gravite" required>
                                        <option value="faible">Faible</option>
                                        <option value="moyenne" selected>Moyenne</option>
                                        <option value="elevee">Élevée</option>
                                        <option value="urgent">Urgent</option>
                                    </select>
                                    <label for="gravite">Niveau de gravité *</label>
                                    <span id="graviteError" class="error-message"></span>
                                    <span id="graviteValid" class="valid-message"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="fichier">Pièce jointe (max 2MB, facultatif)</label>
                                    <input type="file" class="form-control" id="fichier" name="fichier" accept="image/*,.pdf">
                                    <span id="fichierError" class="error-message"></span>
                                    <span id="fichierValid" class="valid-message"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">Envoyer la réclamation</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Formulaire End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Adresse</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>123 Rue de Paris, France</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+33 6 12 34 56 78</p>
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
                        © <a class="border-bottom" href="#">ShareRide</a>, Tous droits réservés.
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

    <!-- Form Validation and Submission Script -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Validation en temps réel
        document.getElementById("type").addEventListener("change", validateType);
        document.getElementById("nom_chauffeur").addEventListener("input", validateNomChauffeur);
        document.getElementById("date_trajet").addEventListener("change", validateDateTrajet);
        document.getElementById("sujet").addEventListener("input", validateSujet);
        document.getElementById("description").addEventListener("input", validateDescription);
        document.getElementById("gravite").addEventListener("change", validateGravite);
        document.getElementById("fichier").addEventListener("change", validateFileUpload);

        function validateType() {
            const type = document.getElementById("type").value;
            const errorElement = document.getElementById("typeError");
            const validElement = document.getElementById("typeValid");

            if (!type) {
                errorElement.textContent = "Veuillez sélectionner un type de réclamation.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("type").classList.add("is-invalid");
                document.getElementById("type").classList.remove("is-valid");
                return false;
            } else {
                errorElement.classList.remove("show");
                validElement.textContent = "✔ Champ valide";
                validElement.classList.add("show");
                document.getElementById("type").classList.remove("is-invalid");
                document.getElementById("type").classList.add("is-valid");
                return true;
            }
        }

        function validateNomChauffeur() {
            const nom = document.getElementById("nom_chauffeur").value.trim();
            const errorElement = document.getElementById("nom_chauffeurError");
            const validElement = document.getElementById("nom_chauffeurValid");

            if (!/^[a-zA-ZÀ-ÿ\s-]{3,}$/.test(nom)) {
                errorElement.textContent = "Le nom doit contenir uniquement des lettres, espaces et tirets (min 3 caractères).";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("nom_chauffeur").classList.add("is-invalid");
                document.getElementById("nom_chauffeur").classList.remove("is-valid");
                return false;
            } else {
                errorElement.classList.remove("show");
                validElement.textContent = "✔ Champ valide";
                validElement.classList.add("show");
                document.getElementById("nom_chauffeur").classList.remove("is-invalid");
                document.getElementById("nom_chauffeur").classList.add("is-valid");
                return true;
            }
        }

        function validateDateTrajet() {
            const date = document.getElementById("date_trajet").value;
            const errorElement = document.getElementById("date_trajetError");
            const validElement = document.getElementById("date_trajetValid");
            const now = new Date();
            const selectedDate = new Date(date);
            const oneYearAgo = new Date();
            oneYearAgo.setFullYear(now.getFullYear() - 1);

            if (!date) {
                errorElement.textContent = "Veuillez sélectionner une date.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("date_trajet").classList.add("is-invalid");
                document.getElementById("date_trajet").classList.remove("is-valid");
                return false;
            } else if (selectedDate > now) {
                errorElement.textContent = "La date ne peut pas être dans le futur.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("date_trajet").classList.add("is-invalid");
                document.getElementById("date_trajet").classList.remove("is-valid");
                return false;
            } else if (selectedDate < oneYearAgo) {
                errorElement.textContent = "La date ne peut pas être antérieure à 1 an.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("date_trajet").classList.add("is-invalid");
                document.getElementById("date_trajet").classList.remove("is-valid");
                return false;
            } else {
                errorElement.classList.remove("show");
                validElement.textContent = "✔ Champ valide";
                validElement.classList.add("show");
                document.getElementById("date_trajet").classList.remove("is-invalid");
                document.getElementById("date_trajet").classList.add("is-valid");
                return true;
            }
        }

        function validateSujet() {
            const sujet = document.getElementById("sujet").value.trim();
            const errorElement = document.getElementById("sujetError");
            const validElement = document.getElementById("sujetValid");

            if (sujet.length < 5) {
                errorElement.textContent = "Le sujet doit contenir au moins 5 caractères.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("sujet").classList.add("is-invalid");
                document.getElementById("sujet").classList.remove("is-valid");
                return false;
            } else if (sujet.length > 100) {
                errorElement.textContent = "Le sujet ne doit pas dépasser 100 caractères.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("sujet").classList.add("is-invalid");
                document.getElementById("sujet").classList.remove("is-valid");
                return false;
            } else {
                errorElement.classList.remove("show");
                validElement.textContent = "✔ Champ valide";
                validElement.classList.add("show");
                document.getElementById("sujet").classList.remove("is-invalid");
                document.getElementById("sujet").classList.add("is-valid");
                return true;
            }
        }

        function validateDescription() {
            const description = document.getElementById("description").value.trim();
            const errorElement = document.getElementById("descriptionError");
            const validElement = document.getElementById("descriptionValid");
            const wordCount = description ? description.split(/\s+/).length : 0;

            if (wordCount < 10) {
                errorElement.textContent = "La description doit contenir au moins 10 mots.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("description").classList.add("is-invalid");
                document.getElementById("description").classList.remove("is-valid");
                return false;
            } else if (wordCount > 500) {
                errorElement.textContent = `La description ne doit pas dépasser 500 mots (${wordCount}/500).`;
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("description").classList.add("is-invalid");
                document.getElementById("description").classList.remove("is-valid");
                return false;
            } else {
                errorElement.classList.remove("show");
                validElement.textContent = `✔ Champ valide (${wordCount}/500 mots)`;
                validElement.classList.add("show");
                document.getElementById("description").classList.remove("is-invalid");
                document.getElementById("description").classList.add("is-valid");
                return true;
            }
        }

        function validateGravite() {
            const gravite = document.getElementById("gravite").value;
            const errorElement = document.getElementById("graviteError");
            const validElement = document.getElementById("graviteValid");

            if (!gravite) {
                errorElement.textContent = "Veuillez sélectionner un niveau de gravité.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                document.getElementById("gravite").classList.add("is-invalid");
                document.getElementById("gravite").classList.remove("is-valid");
                return false;
            } else {
                errorElement.classList.remove("show");
                validElement.textContent = "✔ Champ valide";
                validElement.classList.add("show");
                document.getElementById("gravite").classList.remove("is-invalid");
                document.getElementById("gravite").classList.add("is-valid");
                return true;
            }
        }

        function validateFileUpload() {
            const fileInput = document.getElementById("fichier");
            const errorElement = document.getElementById("fichierError");
            const validElement = document.getElementById("fichierValid");

            if (fileInput.files.length === 0) {
                errorElement.classList.remove("show");
                validElement.classList.remove("show");
                fileInput.classList.remove("is-invalid", "is-valid");
                return true; // File is optional
            }

            const file = fileInput.files[0];
            const validTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            const maxSize = 2 * 1024 * 1024; // 2MB

            if (!validTypes.includes(file.type)) {
                errorElement.textContent = "Seuls les fichiers JPG, PNG et PDF sont acceptés.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                fileInput.classList.add("is-invalid");
                fileInput.classList.remove("is-valid");
                return false;
            } else if (file.size > maxSize) {
                errorElement.textContent = "Le fichier ne doit pas dépasser 2MB.";
                errorElement.classList.add("show");
                validElement.classList.remove("show");
                fileInput.classList.add("is-invalid");
                fileInput.classList.remove("is-valid");
                return false;
            } else {
                errorElement.classList.remove("show");
                validElement.textContent = "✔ Fichier valide";
                validElement.classList.add("show");
                fileInput.classList.remove("is-invalid");
                fileInput.classList.add("is-valid");
                return true;
            }
        }

        // Soumission du formulaire via AJAX
        document.getElementById("reclamationForm").addEventListener("submit", function(e) {
            e.preventDefault(); // Empêche la soumission par défaut

            let isValid = true;

            // Valider tous les champs
            if (!validateType()) isValid = false;
            if (!validateNomChauffeur()) isValid = false;
            if (!validateDateTrajet()) isValid = false;
            if (!validateSujet()) isValid = false;
            if (!validateDescription()) isValid = false;
            if (!validateGravite()) isValid = false;
            if (!validateFileUpload()) isValid = false;

            if (isValid) {
                const submitBtn = document.querySelector("#reclamationForm button[type='submit']");
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
                submitBtn.disabled = true;

                const formData = new FormData(this);
                fetch('addreclamation.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    submitBtn.innerHTML = 'Envoyer la réclamation';
                    submitBtn.disabled = false;

                    const feedbackMessages = document.getElementById('feedbackMessages');
                    feedbackMessages.innerHTML = ''; // Clear previous messages

                    if (data.success) {
                        // Afficher le message de succès
                        feedbackMessages.innerHTML = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                ${data.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `;
                        // Réinitialiser le formulaire
                        document.getElementById("reclamationForm").reset();
                        // Réinitialiser les styles de validation
                        document.querySelectorAll('.is-valid, .is-invalid').forEach(el => {
                            el.classList.remove('is-valid', 'is-invalid');
                        });
                        document.querySelectorAll('.error-message, .valid-message').forEach(el => {
                            el.classList.remove('show');
                            el.textContent = '';
                        });
                    } else {
                        // Afficher les erreurs
                        data.errors.forEach(error => {
                            feedbackMessages.innerHTML += `
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    ${error}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `;
                        });
                    }
                })
                .catch(error => {
                    submitBtn.innerHTML = 'Envoyer la réclamation';
                    submitBtn.disabled = false;
                    document.getElementById('feedbackMessages').innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Erreur: ${error.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                });
            }
        });
    });
    </script>
</body>
</html>