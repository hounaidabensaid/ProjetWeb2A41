<?php
require_once '../../config.php';
require_once '../../model/Reservation.php';
require_once '../../controller/ReservationController.php';
require_once '../../controller/VoitureController.php';
require_once '../../model/Voiture.php';

// Create controller instances
$voitureController = new VoitureController();
$voitures = $voitureController->getAllVoitures();

$pdo = config::getConnexion();
$controller = new ReservationController($pdo);

$id_user = 1;
$reservation = $controller->getAllReservations();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_voiture_post = $_POST['id_voiture'];
    $id_user_post = $_POST['id_user'];
    $date_debut_post = $_POST['date_debut'];
    $date_fin_post = $_POST['date_fin'];

    // Vérifier s'il existe une réservation confirmée pour la même voiture avec chevauchement
    $existingConflict = array_filter($reservation, function ($r) use ($id_voiture_post, $date_debut_post, $date_fin_post) {
        if ($r['id_voiture'] != $id_voiture_post || $r['statut'] !== 'confirmée') {
            return false;
        }

        // Vérifie si les plages de dates se chevauchent
        return !(
            $date_fin_post < $r['date_début'] || $date_debut_post > $r['date_fin']
        );
    });

    if (!empty($existingConflict)) {
        echo "<script>alert('Impossible : une réservation confirmée chevauche déjà ces dates pour cette voiture.'); window.history.back();</script>";
        exit;
    }

    // Ajouter la nouvelle réservation
    $reservation = new Reservation();
    $reservation->setIdVoiture($id_voiture_post);
    $reservation->setIdUser($id_user_post);
    $reservation->setDateDébut($date_debut_post);
    $reservation->setDateFin($date_fin_post);
    $reservation->setStatut("en attente");

    $controller->addReservation($reservation);
    header('Location: ' . $_SERVER['PHP_SELF'] . '?id=' . $id_voiture_post . '&success=1');
    exit;
}


// Handle deletion
if (isset($_GET['delete'])) {
    $controller->deleteReservation($_GET['delete']);
    header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?') . '?success=delete');
    exit;
}

// Filter reservations by user
$mesReclamations = array_filter($reservation, fn($r) => $r['id_user'] == $id_user);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>CarServ - Reservations</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    
    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-red: #D81324;
            --dark-red: #A00E1C;
            --light-red: #FFE6E8;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand, .nav-link.active {
            color: var(--primary-red) !important;
            font-weight: 600;
        }
        
        .btn-primary {
            background-color: var(--primary-red);
            border-color: var(--primary-red);
        }
        
        .btn-primary:hover {
            background-color: var(--dark-red);
            border-color: var(--dark-red);
        }
        
        .page-header {
            background-position: center;
            background-size: cover;
            position: relative;
        }
        
        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
        }
        
        .page-header-inner {
            position: relative;
            z-index: 1;
        }
        
        .reservation-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            background-color: white;
            transition: transform 0.3s;
        }
        
        .reservation-card:hover {
            transform: translateY(-5px);
        }
        
        .card-header-red {
            background-color: var(--primary-red);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 1.5rem;
        }
        
        .form-control:focus {
            border-color: var(--primary-red);
            box-shadow: 0 0 0 0.25rem rgba(216,19,36,0.25);
        }
        
        .table-danger {
            background-color: var(--primary-red);
            color: white;
        }
        
        .badge-warning {
            background-color: #FFF3CD;
            color: #856404;
        }
        
        .badge-success {
            background-color: #D4EDDA;
            color: #155724;
        }
        
        .badge-danger {
            background-color: #F8D7DA;
            color: #721C24;
        }
        
        .alert-success {
            background-color: #D4EDDA;
            border-color: #C3E6CB;
            color: #155724;
        }
        
        .text-red {
            color: var(--primary-red);
        }
        
        .bg-light-red {
            background-color: var(--light-red);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-car me-2"></i>CarRent
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.html">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="service.php">Our Vehicles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="reservation.php">Reservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.html">Contact</a>
                    </li>
                </ul>
                <a href="#" class="btn btn-primary ms-lg-3 px-4">
                    <i class="fas fa-calendar-check me-2"></i>Book Now
                </a>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header py-5 mb-5">
        <div class="container text-center py-5">
            <h1 class="display-4 text-white mb-3 fw-bold">Vehicle Reservations</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="#" class="text-white">Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Reservations</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Reservation Form -->
    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="reservation-card mb-5">
                    <div class="card-header-red text-center">
                        <h3 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Make a Reservation</h3>
                    </div>
                    <div class="card-body p-4">
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success text-center mb-4">Reservation submitted successfully!</div>
                        <?php endif; ?>
                        
                        <form method="POST" id="reservationForm">
                            <div class="mb-4">
                                <label for="id_voiture" class="form-label fw-bold">Select Vehicle</label>
                                <select class="form-select py-2" name="id_voiture" id="id_voiture" required>
                                    <option value="">-- Choose a vehicle --</option>
                                    <?php foreach ($voitures as $v): ?>
                                        <option value="<?= $v->getId() ?>">
                                            <?= $v->getMatricule() ?> - <?= $v->getMarque() ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback" id="error_voiture">Please select a vehicle</div>
                            </div>
                            
                            <input type="hidden" name="id_user" value="<?= $id_user ?>">
                            
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="date_debut" class="form-label fw-bold">Start Date</label>
                                    <input type="date" class="form-control py-2" name="date_debut" id="date_debut" required>
                                    <div class="invalid-feedback" id="error_debut">Please select a start date</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="date_fin" class="form-label fw-bold">End Date</label>
                                    <input type="date" class="form-control py-2" name="date_fin" id="date_fin" required>
                                    <div class="invalid-feedback" id="error_fin">Please select an end date</div>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                                <i class="fas fa-paper-plane me-2"></i>Submit Reservation
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Reservations -->
    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h3 class="text-center mb-4 fw-bold text-red">
                    <i class="fas fa-clipboard-list me-2"></i>My Reservations
                </h3>
				<div class="text-end mb-3">
    <a href="export_pdf.php" class="btn btn-outline-danger">
        <i class="fas fa-file-pdf me-1"></i> Télécharger PDF
    </a>
</div>

                
                <div class="table-responsive">
                    <table class="table table-hover border">
                        <thead class="table-danger text-white">
                            <tr>
                                <th>#</th>
                                <th>Vehicle</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($mesReclamations)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">No reservations found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($mesReclamations as $r): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($r['id_reservation']) ?></td>
                                        <td><?= htmlspecialchars($r['matricule']) ?> - <?= htmlspecialchars($r['marque']) ?></td>
                                        <td><?= htmlspecialchars($r['date_début']) ?></td>
                                        <td><?= htmlspecialchars($r['date_fin']) ?></td>
                                        <td>
                                            <span class="badge rounded-pill 
                                                <?= $r['statut'] === 'confirmée' ? 'bg-success' : 
                                                   ($r['statut'] === 'annulée' ? 'bg-danger' : 'bg-warning text-dark') ?>">
                                                <?= ucfirst($r['statut']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="?delete=<?= $r['id_reservation'] ?>" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Are you sure you want to delete this reservation?');">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </a>
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

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-4">CarRent</h5>
                    <p>Premium vehicle rental service with the best prices and quality vehicles for all your needs.</p>
                    <div class="social-icons mt-4">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5 class="mb-4">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white">Home</a></li>
                        <li class="mb-2"><a href="#" class="text-white">About</a></li>
                        <li class="mb-2"><a href="#" class="text-white">Vehicles</a></li>
                        <li class="mb-2"><a href="#" class="text-white">Services</a></li>
                        <li><a href="#" class="text-white">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5 class="mb-4">Contact Info</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Street, New York, USA</li>
                        <li class="mb-2"><i class="fas fa-phone-alt me-2"></i> +012 345 67890</li>
                        <li><i class="fas fa-envelope me-2"></i> info@example.com</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="mb-4">Newsletter</h5>
                    <p>Subscribe to our newsletter for the latest offers.</p>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Your Email">
                        <button class="btn btn-primary" type="button">Subscribe</button>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; 2023 CarRent. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">Designed with <i class="fas fa-heart text-danger"></i> by Our Team</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("reservationForm").addEventListener("submit", function(e) {
            const voiture = document.getElementById("id_voiture");
            const debut = document.getElementById("date_debut");
            const fin = document.getElementById("date_fin");
            let valid = true;

            // Reset validation
            voiture.classList.remove('is-invalid');
            debut.classList.remove('is-invalid');
            fin.classList.remove('is-invalid');

            // Validate vehicle selection
            if (!voiture.value) {
                voiture.classList.add('is-invalid');
                document.getElementById('error_voiture').textContent = 'Please select a vehicle';
                valid = false;
            }

            // Validate start date
            if (!debut.value) {
                debut.classList.add('is-invalid');
                document.getElementById('error_debut').textContent = 'Please select a start date';
                valid = false;
            }

            // Validate end date
            if (!fin.value) {
                fin.classList.add('is-invalid');
                document.getElementById('error_fin').textContent = 'Please select an end date';
                valid = false;
            } else if (debut.value && new Date(fin.value) <= new Date(debut.value)) {
                fin.classList.add('is-invalid');
                document.getElementById('error_fin').textContent = 'End date must be after start date';
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
            }
        });

        // Set minimum date for date inputs to today
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date_debut').min = today;
            document.getElementById('date_fin').min = today;
            
            // Update end date min when start date changes
            document.getElementById('date_debut').addEventListener('change', function() {
                document.getElementById('date_fin').min = this.value;
            });
        });
    </script>
</body>
</html>