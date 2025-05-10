<?php
session_start();
require_once __DIR__.'/../../config.php';
$bdd = Config::getConnexion();
if (!class_exists('Config')) {
    die("Erreur : La classe Config n'est pas disponible. Vérifiez le chemin : " . __DIR__.'/../../config.php');
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Covoiturage</title>
</head>
<body>

<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Load annonce data for editing if id is provided
$editAnnonce = null;
if (isset($_GET['edit_id'])) {
    try {
        $stmt = $bdd->prepare('SELECT * FROM `123` WHERE id = ?');
        $stmt->execute([$_GET['edit_id']]);
        $editAnnonce = $stmt->fetch();
        if ($editAnnonce) {
            $page = 'proposer'; // Show proposer form for editing
        }
    } catch (Exception $e) {
        // Handle error or ignore
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Covoiturage - Track My Bus</title>
    <style>
        /* Base styles */
        body {
            background-color: #e3e3e9;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Hero section */
        .hero {
            background: linear-gradient(135deg,rgb(194, 3, 3) 0%,rgb(59, 6, 4) 100%);
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
            color: rgb(214, 205, 205);
        }

        .hero h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .hero p {
            color:rgb(226, 189, 189);
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        .hero-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Form sections */
        .form-section {
            background: rgb(224, 224, 255);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-bottom: 2rem;
            max-width: 48rem;
            margin-left: auto;
            margin-right: auto;
        }

        .form-section h2 {
            font-size: 1.8rem;
            font-weight: bold;
            color:rgb(173, 11, 11);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Form group for side by side inputs */
        .form-group {
            display: flex;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        /* Form inputs */
        .form-input {
            width: 100%;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 2px solid #e8ecf5;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color:rgb(216, 9, 9);
            box-shadow: 0 0 0 3px rgba(184, 26, 26, 0.1);
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }

        .btn-primary {
            background-color:rgb(153, 4, 4);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color:rgb(219, 201, 201);
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(129, 119, 119, 0.1);
        }

        .btn-secondary {
            background-color:rgb(102, 1, 1);
            color: white;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #4a4a50;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(95, 92, 92, 0.1);
        }

        .btn-outline {
            border: 2px solidrgb(173, 22, 22);
            color:rgb(201, 198, 198);
            background-color: transparent;
        }

        .btn-outline:hover {
            background-color:rgb(176, 10, 10);
            color: white;
            transform: translateY(-2px);
        }

        .btn-submit {
            width: 100%;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            padding: 1rem;
        }

        .btn-blue {
            background-color: #0a97b0;
            color: white;
        }

        .btn-blue:hover {
            background-color: #0881a3;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-green {
            background-color: #8ebff7;
            color: white;
        }

        .btn-green:hover {
            background-color: #83a4e2;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

       /* Annonces section */
        .annonces {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .annonce {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .annonce-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .annonce-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #04233b;
        }

        .annonce-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            padding: 0.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-edit {
            background-color: #2563eb !important; /* Blue */
            color: white !important;
            padding: 0.5rem 1rem !important;
            border-radius: 0.375rem !important;
            font-weight: 600 !important;
            border: none !important;
            cursor: pointer !important;
            transition: background-color 0.3s ease, box-shadow 0.3s ease !important;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.4) !important;
            text-decoration: none !important;
            display: inline-block !important;
            margin-right: 0.5rem !important;
        }

        .btn-edit:hover {
            background-color: #1e40af !important;
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.6) !important;
            text-decoration: none !important;
            color: white !important;
        }

        .btn-delete {
            background-color: #dc2626 !important; /* Red */
            color: white !important;
            padding: 0.5rem 1rem !important;
            border-radius: 0.375rem !important;
            font-weight: 600 !important;
            border: none !important;
            cursor: pointer !important;
            transition: background-color 0.3s ease, box-shadow 0.3s ease !important;
            box-shadow: 0 2px 6px rgba(220, 38, 38, 0.4) !important;
            text-decoration: none !important;
            display: inline-block !important;
        }
        
        .btn-delete:hover {
            background-color: #991b1b !important;
            box-shadow: 0 4px 12px rgba(153, 27, 27, 0.6) !important;
            text-decoration: none !important;
            color: white !important;
        }
        
        .btn-delete:focus {
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.7) !important;
        }
        
        .btn-green {
            color: white;
            background-color:rgb(47, 67, 97);
            border: 1px solid black;
        }
        
        .btn-green:hover {
            background-color:rgb(66, 108, 121);
        }

        .annonce-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .annonce-info {
            color: #6b7280;
        }
        .form-section {
    background: rgb(224, 224, 255);
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    margin-bottom: 2rem;
    max-width: 60rem; /* Plus large que 48rem */
    margin-left: auto;
    margin-right: auto;
    width: 100%; /* Occupe toute la largeur disponible */
    font-size: 1.1rem; /* Augmenter la taille de la police */
}


/* Pour les champs du formulaire */
.form-input {
    width: 100%;
    padding: 1.2rem;
    margin-bottom: 1.5rem;
    border: 2px solid #e8ecf5;
    border-radius: 0.5rem;
    font-size: 1.1rem;
}

/* Colonne du formulaire pour avoir une belle disposition */
.form-group {
    display: grid;
    grid-template-columns: 1fr 1fr; /* 2 colonnes égales */
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.form-group input,
.form-group textarea {
    width: 100%;
}

/* Améliorer l'apparence du bouton */
.btn-submit {
    width: 100%;
    border: none;
    cursor: pointer;
    font-size: 1.2rem;
    padding: 1rem;
    margin-top: 1.5rem;
}


        /* Responsive design */
        @media (max-width: 640px) {
            .container {
                padding: 1rem;
            }

            .hero {
                padding: 1.5rem;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero-buttons {
                flex-direction: column;
                gap: 1rem;
            }

            .form-group {
                flex-direction: column;
                gap: 0;
            }

            .btn {
                width: 100%;
                text-align: center;
            }

            .form-section {
                padding: 1.5rem;
            }

            .annonce-header {
                flex-direction: column;
                gap: 1rem;
            }

            .annonce-actions {
                width: 100%;
                justify-content: flex-end;
            }
            
        }
    </style>
</head>
<body>


<?php if (isset($_SESSION['erreur'])): ?>
    <div style="background-color: #ffe0e0; color: #d8000c; padding: 10px; border: 1px solid #d8000c; margin: 10px auto; width: 90%; text-align: center;">
        <?= $_SESSION['erreur']; unset($_SESSION['erreur']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div style="background-color: #e0ffe0; color: #007500; padding: 10px; border: 1px solid #007500; margin: 10px auto; width: 90%; text-align: center;">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<section class="hero">
    <h1> Bienvenue sur Share A Ride</h1>
    <p>Choisissez une option ci-dessous pour commencer</p>
    <div class="buttons">
        <a href="covoiturage.php?page=proposer" class="btn btn-primary">Proposer</a>
        <a href="covoiturage.php?page=demander" class="btn btn-secondary">Demander</a>
        <a href="covoiturage.php?page=rechercher" class="btn btn-outline">Rechercher</a>
    </div>
</section>


<hr>

<!-- ✅ Sections dynamiques selon le bouton cliqué -->
<?php if ($page === 'proposer'): ?>
    <!-- FORMULAIRE POUR PROPOSER UN COVOITURAGE -->
            <section class="form-section">
                <h2>📌 <?php echo $editAnnonce ? 'Modifier un covoiturage' : 'Proposer un covoiturage'; ?></h2>
                <form method="POST" action="traitement_covoiturage.php">
                    <?php if ($editAnnonce): ?>
                        <input type="hidden" name="id" value="<?= htmlspecialchars($editAnnonce['id']) ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <input type="text" name="nom" placeholder="Nom" required value="<?= $editAnnonce ? htmlspecialchars($editAnnonce['nom']) : '' ?>">
                        <input type="text" name="prenom" placeholder="Prénom" required value="<?= $editAnnonce ? htmlspecialchars($editAnnonce['prenom']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <input type="text" name="villeDepart" placeholder="Ville de départ" required value="<?= $editAnnonce ? htmlspecialchars($editAnnonce['villeDepart']) : '' ?>">
                        <input type="text" name="villeArrivee" placeholder="Ville d'arrivée" required value="<?= $editAnnonce ? htmlspecialchars($editAnnonce['villeArrivee']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <input type="date" name="date" required value="<?= $editAnnonce ? htmlspecialchars($editAnnonce['date']) : '' ?>">
                        <input type="number" name="prix" placeholder="Prix" required value="<?= $editAnnonce ? htmlspecialchars($editAnnonce['prix']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <input type="text" name="matricule" placeholder="Matricule" required value="<?= $editAnnonce ? htmlspecialchars($editAnnonce['matricule']) : '' ?>">
                        <input type="text" name="typeVehicule" placeholder="Type de véhicule" required value="<?= $editAnnonce ? htmlspecialchars($editAnnonce['typeVehicule']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <input type="tel" name="telephone" placeholder="Téléphone" required value="<?= $editAnnonce ? htmlspecialchars($editAnnonce['telephone']) : '' ?>">
                        <input type="number" name="placesDisponibles" placeholder="Places disponibles" required value="<?= $editAnnonce ? htmlspecialchars($editAnnonce['placesDisponibles']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <textarea name="details" placeholder="Détails supplémentaires"><?= $editAnnonce ? htmlspecialchars($editAnnonce['details']) : '' ?></textarea>
                    </div>
                    <button type="submit" name="action" value="<?= $editAnnonce ? 'modifier_annonce' : 'ajouter_annonce' ?>" class="btn-submit"><?= $editAnnonce ? 'Mettre à jour' : 'Publier' ?></button>
                </form>
            </section>

<?php elseif ($page === 'demander'): ?>
    <?php
    // Fetch all demandes joined with their annonce info
    try {
        $stmt = $bdd->prepare("
            SELECT d.id_demande, d.nom, d.prenom, d.telephone, d.email, d.places,
                   a.villeDepart, a.villeArrivee, a.id as annonce_id
            FROM demande d
            JOIN `123` a ON d.id_annonce = a.id
            WHERE d.nom = :nom
            ORDER BY d.id_demande DESC
        ");
        $stmt->bindValue(':nom', $_SESSION['nom'], PDO::PARAM_STR);
        $stmt->execute();
        $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo '<p>Erreur lors de la récupération des demandes : ' . htmlspecialchars($e->getMessage()) . '</p>';
        $demandes = [];
    }
    ?>
    <section class="form-section">
        <h2>📋 Toutes mes demandes de covoiturage</h2>
        <?php if (empty($demandes)): ?>
            <p>Aucune demande pour le moment.</p>
        <?php else: ?>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #8B0000; color: white;">
                        <th style="padding: 0.5rem; border: 1px solid #ddd;">Ville de départ</th>
                        <th style="padding: 0.5rem; border: 1px solid #ddd;">Ville d'arrivée</th>
                        <th style="padding: 0.5rem; border: 1px solid #ddd;">Nom</th>
                        <th style="padding: 0.5rem; border: 1px solid #ddd;">Prénom</th>
                        <th style="padding: 0.5rem; border: 1px solid #ddd;">Téléphone</th>
                        <th style="padding: 0.5rem; border: 1px solid #ddd;">Email</th>
                        <th style="padding: 0.5rem; border: 1px solid #ddd;">Places demandées</th>
                        <th style="padding: 0.5rem; border: 1px solid #ddd;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($demandes as $demande): ?>
                        <tr>
                            <td style="padding: 0.5rem; border: 1px solid #ddd;"><?= htmlspecialchars($demande['villeDepart']) ?></td>
                            <td style="padding: 0.5rem; border: 1px solid #ddd;"><?= htmlspecialchars($demande['villeArrivee']) ?></td>
                            <td style="padding: 0.5rem; border: 1px solid #ddd;"><?= htmlspecialchars($demande['nom']) ?></td>
                            <td style="padding: 0.5rem; border: 1px solid #ddd;"><?= htmlspecialchars($demande['prenom']) ?></td>
                            <td style="padding: 0.5rem; border: 1px solid #ddd;"><?= htmlspecialchars($demande['telephone']) ?></td>
                            <td style="padding: 0.5rem; border: 1px solid #ddd;"><?= htmlspecialchars($demande['email']) ?></td>
                            <td style="padding: 0.5rem; border: 1px solid #ddd;"><?= htmlspecialchars($demande['places']) ?></td>
                            <td style="padding: 0.5rem; border: 1px solid #ddd;">
                                <a href="editer_demande.php?id=<?= $demande['id_demande'] ?>&annonce_id=<?= $demande['annonce_id'] ?>" class="btn-edit" style="margin-right: 0.5rem;">Modifier</a>
                                <form method="POST" action="traitement_demande_delete.php" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?');">
                                    <input type="hidden" name="id_demande" value="<?= $demande['id_demande'] ?>">
                                    <input type="hidden" name="id_annonce" value="<?= $demande['annonce_id'] ?>">
                                    <input type="hidden" name="action" value="delete_demande">
                                    <button type="submit" class="btn-delete">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>

<?php elseif ($page === 'rechercher'): ?>
    <!-- AFFICHAGE DES ANNONCES -->
    <section id="annonces" class="annonces">
        <h2>📋 Annonces publiées</h2>
        <div style="margin-bottom: 1rem;">
            <label for="villeDepartSearch" style="font-weight: bold; margin-right: 0.5rem;">Ville de départ:</label>
            <input type="text" id="villeDepartSearch" name="villeDepartSearch" placeholder="Rechercher ville de départ" style="padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px solid #ccc; font-size: 1rem; margin-right: 1rem;">
            <label for="villeArriveeSearch" style="font-weight: bold; margin-right: 0.5rem;">Ville d'arrivée:</label>
            <input type="text" id="villeArriveeSearch" name="villeArriveeSearch" placeholder="Rechercher ville d'arrivée" style="padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px solid #ccc; font-size: 1rem;">
            <a href="total_prices.php" class="btn btn-primary" style="margin-left: 15rem;">Afficher prix total</a>
        </div>
        <div id="annoncesList">
            <?php
            try {
                $bdd = new PDO('mysql:host=localhost;dbname=123;charset=utf8', 'root', '');
                $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(Exception $e) {
                die('Erreur : '.$e->getMessage());
            }

$annoncesPerPage = 4;
$currentPage = isset($_GET['annonce_page']) && is_numeric($_GET['annonce_page']) ? (int)$_GET['annonce_page'] : 1;
$offset = ($currentPage - 1) * $annoncesPerPage;

// Get total count of annonces
$totalAnnoncesStmt = $bdd->query('SELECT COUNT(*) FROM `123` WHERE date >= CURDATE()');
$totalAnnonces = $totalAnnoncesStmt->fetchColumn();
$totalPages = ceil($totalAnnonces / $annoncesPerPage);

// Fetch annonces with limit and offset
$reponse = $bdd->prepare('SELECT *, user_id FROM `123` WHERE date >= CURDATE() ORDER BY date DESC LIMIT :limit OFFSET :offset');
$reponse->bindValue(':limit', $annoncesPerPage, PDO::PARAM_INT);
$reponse->bindValue(':offset', $offset, PDO::PARAM_INT);
$reponse->execute();

if ($reponse->rowCount() == 0) {
    echo '<p class="text-gray-500 text-center">Aucune annonce publiée pour le moment</p>';
} else {
    $currentUserId = $_SESSION['user_id'] ?? null;
    while ($annonce = $reponse->fetch()) {
        echo '
        <div class="annonce">
            <div class="annonce-header">
                <div class="annonce-title">
                    '.htmlspecialchars($annonce['villeDepart']).' → '.htmlspecialchars($annonce['villeArrivee']).'
                </div>
                <div class="annonce-actions">';
        if ($currentUserId !== null && $annonce['user_id'] == $currentUserId) {
            echo '<a href="covoiturage.php?page=proposer&edit_id='.$annonce['id'].'" class="btn-action btn-green">✏️ Modifier</a>
                <button onclick="deleteAnnonce('.$annonce['id'].')" class="btn-action btn-delete">🗑️ Supprimer</button>';
        }
        echo '<a href="demande_covoiturage.php?id=' . $annonce['id'] . '" class="btn-action btn-request" style="text-decoration: none; cursor: pointer; background-color: #6b7280; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem;">🚗 Demande de covoiturage</a>
                </div>
            </div>
            <div class="annonce-details">
                <div class="annonce-info"><strong>Date:</strong> '.htmlspecialchars($annonce['date']).'</div>
                <div class="annonce-info"><strong>Prix:</strong> '.htmlspecialchars($annonce['prix']).' D</div>
                <div class="annonce-info"><strong>Places:</strong> '.htmlspecialchars($annonce['placesDisponibles']).'</div>
                <div class="annonce-info"><strong>Conducteur:</strong> '.htmlspecialchars($annonce['prenom']).' '.htmlspecialchars($annonce['nom']).'</div>
                <div class="annonce-info"><strong>Téléphone:</strong> '.htmlspecialchars($annonce['telephone']).'</div>
            </div>
            <div class="annonce-info"><strong>Véhicule:</strong> '.htmlspecialchars($annonce['typeVehicule']).' ('.htmlspecialchars($annonce['matricule']).')</div>';
        
        if (!empty($annonce['details'])) {
            echo '<div class="annonce-info" style="margin-top: 0.5rem;"><strong>Détails:</strong> '.htmlspecialchars($annonce['details']).'</div>';
        }
        echo '</div>';
    }
}
            ?>
        </div>
        <div class="pagination" style="text-align:center; margin-top: 1rem;">
            <?php if ($currentPage > 1): ?>
                <a href="covoiturage.php?page=rechercher&annonce_page=<?= $currentPage - 1 ?>" class="btn btn-outline">Précédent</a>
            <?php endif; ?>

            <?php for ($pageNum = 1; $pageNum <= $totalPages; $pageNum++): ?>
                <?php if ($pageNum == $currentPage): ?>
                    <span class="btn btn-primary" style="pointer-events: none;"><?= $pageNum ?></span>
                <?php else: ?>
                    <a href="covoiturage.php?page=rechercher&annonce_page=<?= $pageNum ?>" class="btn btn-outline"><?= $pageNum ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="covoiturage.php?page=rechercher&annonce_page=<?= $currentPage + 1 ?>" class="btn btn-outline">Suivant</a>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>
<script>
    function editAnnonce(id) {
        window.location.href = 'covoiturage.php?page=proposer&edit_id=' + id;
    }

    function deleteAnnonce(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')) {
            window.location.href = 'supprimer_covoiturage.php?id=' + id;
        }
    }

    // Dynamic search functionality
    const villeDepartInput = document.getElementById('villeDepartSearch');
    const villeArriveeInput = document.getElementById('villeArriveeSearch');
    const annoncesList = document.getElementById('annoncesList');
    const showTotalPriceBtn = document.getElementById('showTotalPriceBtn');

    function fetchFilteredAnnonces() {
        const villeDepart = villeDepartInput.value.trim();
        const villeArrivee = villeArriveeInput.value.trim();

        const params = new URLSearchParams();
        if (villeDepart) params.append('villeDepart', villeDepart);
        if (villeArrivee) params.append('villeArrivee', villeArrivee);

        fetch('search_annonces.php?' + params.toString())
            .then(response => response.text())
            .then(html => {
                annoncesList.innerHTML = html;
            })
            .catch(error => {
                console.error('Erreur lors de la recherche dynamique:', error);
            });
    }

    function fetchTotalPrices() {
        fetch('get_total_prices.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const pricesMap = new Map();
                    data.data.forEach(item => {
                        pricesMap.set(item.id, item.totalPrice);
                    });

                    // Update each annonce div with total price
                    const annonceDivs = annoncesList.querySelectorAll('.annonce');
                    annonceDivs.forEach(div => {
                        const idMatch = div.querySelector('.btn-edit').getAttribute('onclick').match(/editAnnonce\((\d+)\)/);
                        if (idMatch) {
                            const annonceId = parseInt(idMatch[1]);
                            const price = pricesMap.get(annonceId);
                            let priceDiv = div.querySelector('.total-price');
                            if (!priceDiv) {
                                priceDiv = document.createElement('div');
                                priceDiv.className = 'total-price annonce-info';
                                div.appendChild(priceDiv);
                            }
                            priceDiv.textContent = 'Total prix: ' + (price !== undefined ? price + ' D' : 'N/A');
                        }
                    });
                } else {
                    console.error('Erreur lors de la récupération des totaux:', data.error);
                }
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des totaux:', error);
            });
    }

    villeDepartInput.addEventListener('input', fetchFilteredAnnonces);
    villeArriveeInput.addEventListener('input', fetchFilteredAnnonces);
    if (showTotalPriceBtn) {
        showTotalPriceBtn.addEventListener('click', fetchTotalPrices);
    }
</script>
</body>
</html>

