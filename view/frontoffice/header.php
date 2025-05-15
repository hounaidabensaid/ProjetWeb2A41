<!-- filepath: c:\xampp\htdocs\web\view\frontoffice\header.php -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Exemple de notifications (vous pouvez les récupérer depuis une base de données)
$notifications = [
    "Votre paiement de 50€ a été accepté.",
    "Votre mot de passe a été modifié avec succès.",
    "Une nouvelle mise à jour est disponible.",
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Share a Ride</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@600;700&family=Ubuntu:wght@400;500&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheets -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Topbar Start -->
    <div class="container-fluid bg-light p-0">
        <div class="row gx-0 d-none d-lg-flex">
            <div class="col-lg-7 px-5 text-start">
                <div class="h-100 d-inline-flex align-items-center py-3 me-4">
                    <small class="fa fa-map-marker-alt text-primary me-2"></small>
                    <small>Tunis, El Ghazela</small>
                </div>
                <div class="h-100 d-inline-flex align-items-center py-3">
                    <small class="far fa-clock text-primary me-2"></small>
                    <small>7 jours/7, 24 heures/24</small>
                </div>
            </div>
            <div class="col-lg-5 px-5 text-end">
                <div class="h-100 d-inline-flex align-items-center py-3 me-4">
                    <small class="fa fa-phone-alt text-primary me-2"></small>
                    <small>+216 90793927</small>
                </div>
                <div class="h-100 d-inline-flex align-items-center">
                    <a class="btn btn-sm-square bg-white text-primary me-1" href="#"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-sm-square bg-white text-primary me-1" href="#"><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-sm-square bg-white text-primary me-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-sm-square bg-white text-primary me-0" href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="index.php" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 position-relative d-inline-block" style="font-family: 'Poppins', sans-serif;">
                <i class="fa fa-car me-3 animate-bounce"></i>
                <span class="fire-text">Share a Ride</span>
            </h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="acceuil.php" class="nav-item nav-link active">Accueil</a>
                <div class="navbar-nav ms-auto p-4 p-lg-0">
                    <!-- <a href="index.html" class="nav-item nav-link active">Inscription</a> -->
                    <!-- <a href="inscription.html" class="nav-item nav-link active">
                        <i class="fas fa-user me-2 hover-icon"></i>Inscription
                    </a> -->
                    <div class="navbar-nav ms-auto p-4 p-lg-0">
                
                        <a href="covoiturage.php" class="nav-item nav-link active">Co-voiturage</a>
                        <a href="index.html" class="nav-item nav-link active">Evenement</a>
                        <a href="profil.php" class="nav-item nav-link active">Mon profil</a>

                <!-- Notifications Dropdown -->
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle position-relative" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell fa-lg"></i>
                        <?php if (!empty($notifications)): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?= count($notifications) ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                        <?php if (!empty($notifications)): ?>
                            <?php foreach ($notifications as $notification): ?>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-info-circle text-primary me-2"></i><?= htmlspecialchars($notification) ?></a></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><p class="dropdown-item text-muted">Aucune notification pour le moment.</p></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- <a href="logout.php" class="nav-item nav-link"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a> -->
                <?php else: ?>
                    <a href="login.php" class="nav-item nav-link"><i class="fas fa-sign-in-alt me-2"></i>Connexion</a>
                    <a href="register.php" class="nav-item nav-link"><i class="fas fa-user-plus me-2"></i>Inscription</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->