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
            color: #0a97b0;
            background-color: #e6f7fa;
        }

        .btn-edit:hover {
            background-color: #d1f1f7;
        }

        .btn-delete {
            color: #ef4444;
            background-color: #fee2e2;
        }

        .btn-delete:hover {
            background-color: #fecaca;
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
    <h1> Bienvenue sur Track My Bus</h1>
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
        <h2>📌 Proposer un covoiturage</h2>
        <form method="POST" action="traitement_covoiturage.php">
            <div class="form-group">
                <input type="text" name="nom" placeholder="Nom" required>
                <input type="text" name="prenom" placeholder="Prénom" required>
            </div>
            <div class="form-group">
                <input type="text" name="villeDepart" placeholder="Ville de départ" required>
                <input type="text" name="villeArrivee" placeholder="Ville d'arrivée" required>
            </div>
            <div class="form-group">
                <input type="date" name="date" required>
                <input type="number" name="prix" placeholder="Prix" required>
            </div>
            <div class="form-group">
                <input type="text" name="matricule" placeholder="Matricule" required>
                <input type="text" name="typeVehicule" placeholder="Type de véhicule" required>
            </div>
            <div class="form-group">
                <input type="number" name="placesDisponibles" placeholder="Places disponibles" required>
                <textarea name="details" placeholder="Détails supplémentaires"></textarea>
            </div>
            <button type="submit" name="action" value="ajouter_annonce" class="btn-submit">Publier</button>
        </form>
    </section>

<?php elseif ($page === 'demander'): ?>
    <!-- FORMULAIRE POUR DEMANDER UN COVOITURAGE -->
    <section class="form-section">
        <h2>📝 Demander un covoiturage</h2>
        <form method="POST" action="traitement_demande.php">
            <div class="form-group">
                <input type="text" name="nom" placeholder="Nom" required>
                <input type="text" name="prenom" placeholder="Prénom" required>
            </div>
            <div class="form-group">
                <input type="text" name="villeDepart" placeholder="Ville de départ" required>
                <input type="text" name="villeArrivee" placeholder="Ville d'arrivée" required>
            </div>
            <div class="form-group">
                <input type="date" name="date" required>
                <textarea name="message" placeholder="Message ou besoins spécifiques"></textarea>
            </div>
            <button type="submit" name="action" value="ajouter_demande" class="btn-submit">Envoyer la demande</button>
        </form>
    </section>

<?php elseif ($page === 'rechercher'): ?>
    <!-- AFFICHAGE DES ANNONCES -->
    <section id="annonces" class="annonces">
        <h2>📋 Annonces publiées</h2>
        <div id="annoncesList">
            <?php
            try {
                $bdd = new PDO('mysql:host=localhost;dbname=123;charset=utf8', 'root', '');
                $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(Exception $e) {
                die('Erreur : '.$e->getMessage());
            }

            $reponse = $bdd->query('SELECT * FROM `123` ORDER BY date DESC');

            if ($reponse->rowCount() == 0) {
                echo '<p class="text-gray-500 text-center">Aucune annonce publiée pour le moment</p>';
            } else {
                while ($annonce = $reponse->fetch()) {
                    echo '
                    <div class="annonce">
                        <div class="annonce-header">
                            <div class="annonce-title">
                                '.htmlspecialchars($annonce['villeDepart']).' → '.htmlspecialchars($annonce['villeArrivee']).'
                            </div>
                            <div class="annonce-actions">
                                <button onclick="editAnnonce('.$annonce['id'].')" class="btn-action btn-edit">✏️ Modifier</button>
                                <button onclick="deleteAnnonce('.$annonce['id'].')" class="btn-action btn-delete">🗑️ Supprimer</button>
                                <a href="demande_covoiturage.php?id=' . $annonce['id'] . '" class="btn-action btn-request">🚗 Demande de covoiturage</a>
                            </div>
                        </div>
                        <div class="annonce-details">
                            <div class="annonce-info"><strong>Date:</strong> '.htmlspecialchars($annonce['date']).'</div>
                            <div class="annonce-info"><strong>Prix:</strong> '.htmlspecialchars($annonce['prix']).' D</div>
                            <div class="annonce-info"><strong>Places:</strong> '.htmlspecialchars($annonce['placesDisponibles']).'</div>
                            <div class="annonce-info"><strong>Conducteur:</strong> '.htmlspecialchars($annonce['prenom']).' '.htmlspecialchars($annonce['nom']).'</div>
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
    </section>
<?php endif; ?>
<script>
       
        function editAnnonce(id) {
            window.location.href = 'editer_covoiturage.php?id=' + id;
        }

      
        function deleteAnnonce(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')) {
                window.location.href = 'supprimer_covoiturage.php?id=' + id;
            }
        }
    </script>
</body>
</html>