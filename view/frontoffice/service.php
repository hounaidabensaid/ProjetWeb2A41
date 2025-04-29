<?php
// Inclure le contrôleur et le modèle
require_once '../../controller/VoitureController.php';
require_once '../../model/Voiture.php';

// Créer une instance du contrôleur VoitureController
$voitureController = new VoitureController();

// Récupérer toutes les voitures
$voitures = $voitureController->getAllVoitures();

$voituresParPage = 6; // nombre de voitures par page
$pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$indexDepart = ($pageActuelle - 1) * $voituresParPage;

// Total voitures
$totalVoitures = count($voitures);
$pagesTotal = ceil($totalVoitures / $voituresParPage);

// Découper les voitures à afficher pour la page en cours
$voituresPage = array_slice($voitures, $indexDepart, $voituresParPage);

$filtreMarque = isset($_GET['marque']) ? strtolower(trim($_GET['marque'])) : '';
$filtreStatut = isset($_GET['statut']) ? strtolower(trim($_GET['statut'])) : '';
$filtrePlaces = isset($_GET['places']) ? (int)$_GET['places'] : 0;

// Appliquer les filtres
$voitures = array_filter($voitures, function($voiture) use ($filtreMarque, $filtreStatut, $filtrePlaces) {
    $match = true;
    if ($filtreMarque && strtolower($voiture->getMarque()) !== $filtreMarque) {
        $match = false;
    }
    if ($filtreStatut && strtolower($voiture->getStatut()) !== $filtreStatut) {
        $match = false;
    }
    if ($filtrePlaces > 0 && $voiture->getNbPlace() < $filtrePlaces) {
        $match = false;
    }
    return $match;
});

// Réindexer le tableau
$voitures = array_values($voitures);

// Gestion pagination après filtrage
$totalVoitures = count($voitures);
$pagesTotal = ceil($totalVoitures / $voituresParPage);
$voituresPage = array_slice($voitures, $indexDepart, $voituresParPage);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>CarServ - Car Repair HTML Template</title>
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
</head>
<style>
.pagination-danger .page-link {
    color: #dc3545;
    border-color: #dc3545;
}
.pagination-danger .page-item.active .page-link {
    background-color: #dc3545;
    border-color: #dc3545;
}


/* Conteneur principal */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Style des cartes */
.voiture-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background-color: #fff;
    overflow: hidden;
}

.voiture-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
}

/* Image de la voiture */
.card-img-top {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    height: 200px;
    object-fit: cover;
}

/* Corps de la carte */
.card-body {
    padding: 20px;
}

.card-title {
    font-size: 1.4rem;
    font-weight: bold;
    color: #333;
}

.card-text {
    font-size: 1rem;
    color: #666;
    margin-bottom: 15px;
}

.card-text strong {
    color: #333;
}

/* Bouton "Voir Détails" */
.btn {
    background-color: #D81324;
    color: white;
    border-radius: 5px;
    padding: 10px 20px;
    text-align: center;
    display: inline-block;
    text-decoration: none;
    font-size: 1rem;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #D81324;
    text-decoration: none;
}

/* Filtres */
.select-filter, #btn-filter {
    padding: 10px 15px;
    border-radius: 5px;
    margin: 5px;
}

/* Responsive design */
@media (max-width: 768px) {
    .col-md-4 {
        flex: 0 0 50%;
        max-width: 50%;
    }
}

@media (max-width: 576px) {
    .col-md-4 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}

.voiture-card:hover {
    transform: translateY(-5px);
    transition: all 0.3s ease-in-out;
    box-shadow: 0 8px 20px rgba(220, 53, 69, 0.15);
}

</style>

<body>
   


    <!-- Topbar Start -->
    <div class="container-fluid bg-light p-0">
        <div class="row gx-0 d-none d-lg-flex">
            <div class="col-lg-7 px-5 text-start">
                <div class="h-100 d-inline-flex align-items-center py-3 me-4">
                    <small class="fa fa-map-marker-alt text-primary me-2"></small>
                    <small>123 Street, New York, USA</small>
                </div>
                <div class="h-100 d-inline-flex align-items-center py-3">
                    <small class="far fa-clock text-primary me-2"></small>
                    <small>Mon - Fri : 09.00 AM - 09.00 PM</small>
                </div>
            </div>
            <div class="col-lg-5 px-5 text-end">
                <div class="h-100 d-inline-flex align-items-center py-3 me-4">
                    <small class="fa fa-phone-alt text-primary me-2"></small>
                    <small>+012 345 6789</small>
                </div>
                <div class="h-100 d-inline-flex align-items-center">
                    <a class="btn btn-sm-square bg-white text-primary me-1" href=""><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-sm-square bg-white text-primary me-1" href=""><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-sm-square bg-white text-primary me-1" href=""><i class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-sm-square bg-white text-primary me-0" href=""><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="index.html" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary"><i class="fa fa-car me-3"></i>CarServ</h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="index.html" class="nav-item nav-link">Home</a>
                <a href="about.html" class="nav-item nav-link">About</a>
                <a href="service.php" class="nav-item nav-link active">Nos Véhicules</a>
                <a href="reservation.php" class="nav-item nav-link active">Réservation</a>

                <a href="contact.html" class="nav-item nav-link">Contact</a>
            </div>
            <a href="" class="btn btn-primary py-4 px-lg-5 d-none d-lg-block">Get A Quote<i class="fa fa-arrow-right ms-3"></i></a>
        </div>
    </nav>
    <!-- Navbar End -->


    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 p-0" style="background-image: url(img/carousel-bg-2.jpg);">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center">
                <h1 class="display-3 text-white mb-3 animated slideInDown">Services</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Pages</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Services</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


  



<!-- List Voiture Start -->
<div class="container-xxl service py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="text-primary text-uppercase">Notre Parc Automobile</h6>
            <h1 class="mb-5">Découvrez Nos Véhicules</h1>
        </div>
        
      <form method="GET" class="row mb-4 wow fadeInUp" data-wow-delay="0.2s">
    <div class="col-md-3">
        <select name="marque" class="form-select">
            <option value="">Toutes les marques</option>
            <!-- Exemple statique, idéalement généré dynamiquement -->
            <option value="Toyota" <?= isset($_GET['marque']) && $_GET['marque'] == 'Toyota' ? 'selected' : '' ?>>Toyota</option>
            <option value="BMW" <?= isset($_GET['marque']) && $_GET['marque'] == 'BMW' ? 'selected' : '' ?>>BMW</option>
        </select>
    </div>
    <div class="col-md-3">
        <select name="statut" class="form-select">
            <option value="">Tous les statuts</option>
            <option value="disponible" <?= isset($_GET['statut']) && $_GET['statut'] == 'disponible' ? 'selected' : '' ?>>Disponible</option>
            <option value="en service" <?= isset($_GET['statut']) && $_GET['statut'] == 'en service' ? 'selected' : '' ?>>En service</option>
            <option value="réservé" <?= isset($_GET['statut']) && $_GET['statut'] == 'réservé' ? 'selected' : '' ?>>Réservé</option>
        </select>
    </div>
    <div class="col-md-3">
        <input type="number" name="places" class="form-control" placeholder="Nombre de places min" value="<?= isset($_GET['places']) ? $_GET['places'] : '' ?>">
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-danger w-100">Filtrer</button>
    </div>
</form>


        
  <!-- Liste des voitures -->
<div class="row">
<?php foreach ($voituresPage as $voiture): ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow border-0 voiture-card h-100" style="background-color: #fff5f5; border-left: 5px solid #dc3545;">
                <div class="card-body">
                    <h5 class="card-title text-danger">Voiture #<?= $voiture->getId() ?></h5>
                    <ul class="list-unstyled mb-3">
                        <li><strong>Matricule:</strong> <?= $voiture->getMatricule() ?></li>
                        <li><strong>Marque:</strong> <?= $voiture->getMarque() ?></li>
                        <li><strong>Couleur:</strong> <?= $voiture->getCouleur() ?></li>
                        <li><strong>Places:</strong> <?= $voiture->getNbPlace() ?></li>
                        <li>
                            <strong>Statut:</strong>
                            <span class="badge 
                                <?= $voiture->getStatut() === 'disponible' ? 'bg-success' : ($voiture->getStatut() === 'en service' ? 'bg-warning text-dark' : 'bg-danger') ?>">
                                <?= ucfirst($voiture->getStatut()) ?>
                            </span>
                        </li>
                    </ul>

                    <a href="reserver.php?id=<?= $voiture->getId() ?>" class="btn btn-outline-danger w-100 fw-bold">
                        <i class="fas fa-calendar-plus me-1"></i> Réserver
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
	<div class="d-flex justify-content-center mt-4">
    <nav>
        <ul class="pagination pagination-danger">
            <?php for ($i = 1; $i <= $pagesTotal; $i++): 
                $queryParams = $_GET;
                $queryParams['page'] = $i;
                $queryString = http_build_query($queryParams);
            ?>
                <li class="page-item <?= ($i == $pageActuelle) ? 'active' : '' ?>">
                    <a class="page-link" href="?<?= $queryString ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

</div>



    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Address</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>123 Street, New York, USA</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@example.com</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Opening Hours</h4>
                    <h6 class="text-light">Monday - Friday:</h6>
                    <p class="mb-4">09.00 AM - 09.00 PM</p>
                    <h6 class="text-light">Saturday - Sunday:</h6>
                    <p class="mb-0">09.00 AM - 12.00 PM</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Services</h4>
                    <a class="btn btn-link" href="">Diagnostic Test</a>
                    <a class="btn btn-link" href="">Engine Servicing</a>
                    <a class="btn btn-link" href="">Tires Replacement</a>
                    <a class="btn btn-link" href="">Oil Changing</a>
                    <a class="btn btn-link" href="">Vacuam Cleaning</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Newsletter</h4>
                    <p>Dolor amet sit justo amet elitr clita ipsum elitr est.</p>
                    <div class="position-relative mx-auto" style="max-width: 400px;">
                        <input class="form-control border-0 w-100 py-3 ps-4 pe-5" type="text" placeholder="Your email">
                        <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">SignUp</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">Your Site Name</a>, All Right Reserved.

                        <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                        Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="footer-menu">
                            <a href="">Home</a>
                            <a href="">Cookies</a>
                            <a href="">Help</a>
                            <a href="">FQAs</a>
                        </div>
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

</body>

</html>