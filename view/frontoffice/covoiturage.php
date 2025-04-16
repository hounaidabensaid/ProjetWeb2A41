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
            background: linear-gradient(135deg, #0a97b0 0%, #04233b 100%);
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
            color: rgb(169, 219, 203);
        }

        .hero h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .hero p {
            color: #e5e7eb;
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
            color: #04233b;
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
            border-color: #0a97b0;
            box-shadow: 0 0 0 3px rgba(10, 151, 176, 0.1);
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
            background-color: #0a97b0;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0881a3;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary {
            background-color: #111149;
            color: white;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #4a4a50;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-outline {
            border: 2px solid #0a97b0;
            color: #0a97b0;
            background-color: transparent;
        }

        .btn-outline:hover {
            background-color: #0a97b0;
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
    <div class="container">
        <section class="hero">
            <h1>Covoiturez en toute simplicité</h1>
            <p>Proposez ou cherchez un trajet en quelques clics avec Track My Bus.</p>
            <div class="hero-buttons">
                <a href="#proposer" class="btn btn-primary">Proposer un trajet</a>
                <a href="#rechercher" class="btn btn-outline">Rechercher un trajet</a>
                <a href="#demander" class="btn btn-secondary">Demander un covoiturage</a>
            </div>
        </section>

        <section id="proposer" class="form-section">
            <h2>🚗 Proposer un covoiturage</h2>
            <form id="covoiturageForm" method="post" action="traitement_covoiturage.php">
                <div class="form-group">
                    <input type="text" name="nom" placeholder="Nom" class="form-input" required />
                    <input type="text" name="prenom" placeholder="Prénom" class="form-input" required />
                </div>
                <input type="text" name="villeDepart" placeholder="Ville de départ" class="form-input" required />
                <input type="text" name="villeArrivee" placeholder="Ville d'arrivée" class="form-input" required />
                <input type="date" name="date" class="form-input" required />
                <input type="number" name="prix" placeholder="Prix par personne (en DH)" class="form-input" required />
                <input type="text" name="matricule" placeholder="Matricule du véhicule" class="form-input" required />
                <input type="text" name="typeVehicule" placeholder="Type de véhicule" class="form-input" required />
                <input type="number" name="placesDisponibles" placeholder="Nombre de places disponibles" class="form-input" required />
                <textarea name="details" placeholder="Détails (point de rendez-vous, conditions...)" class="form-input" rows="4"></textarea>
                <button type="submit" class="btn btn-submit btn-blue">Publier l'annonce</button>
                <input type="hidden" name="id" />
            </form>
        </section>

        <section id="annonces" class="annonces">
            <h2>📋 Annonces publiées</h2>
            <div id="annoncesList">
                <?php
                // Connexion à la base de données
                try {
                    $bdd = new PDO('mysql:host=localhost;dbname=123;charset=utf8', 'root', '');
                    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch(Exception $e) {
                    die('Erreur : '.$e->getMessage());
                }

                // Récupération des annonces depuis la table 123
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
                                </div>
                            </div>
                            <div class="annonce-details">
                                <div class="annonce-info">
                                    <strong>Date:</strong> '.htmlspecialchars($annonce['date']).'
                                </div>
                                <div class="annonce-info">
                                    <strong>Prix:</strong> '.htmlspecialchars($annonce['prix']).' DH
                                </div>
                                <div class="annonce-info">
                                    <strong>Places:</strong> '.htmlspecialchars($annonce['placesDisponibles']).'
                                </div>
                                <div class="annonce-info">
                                    <strong>Conducteur:</strong> '.htmlspecialchars($annonce['prenom']).' '.htmlspecialchars($annonce['nom']).'
                                </div>
                            </div>
                            <div class="annonce-info">
                                <strong>Véhicule:</strong> '.htmlspecialchars($annonce['typeVehicule']).' ('.htmlspecialchars($annonce['matricule']).')
                            </div>';
                        
                        if (!empty($annonce['details'])) {
                            echo '
                            <div class="annonce-info" style="margin-top: 0.5rem;">
                                <strong>Détails:</strong> '.htmlspecialchars($annonce['details']).'
                            </div>';
                        }
                        
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </section>

        <section id="rechercher" class="form-section">
            <h2>🔍 Rechercher un trajet</h2>
            <form method="get" action="recherche_covoiturage.php">
                <input type="text" name="depart" placeholder="Ville de départ" class="form-input" required />
                <input type="text" name="arrivee" placeholder="Ville d'arrivée" class="form-input" required />
                <input type="date" name="date" class="form-input" required />
                <button type="submit" class="btn btn-submit btn-blue">Rechercher</button>
            </form>
        </section>
        <?php
if (isset($_GET['depart'], $_GET['arrivee'], $_GET['date'])) {
    $depart = $_GET['depart'];
    $arrivee = $_GET['arrivee'];
    $date = $_GET['date'];

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=123;charset=utf8', 'root', '');
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $bdd->prepare('SELECT * FROM `123` WHERE villeDepart = ? AND villeArrivee = ? AND date = ?');
        $stmt->execute([$depart, $arrivee, $date]);

        echo '<section class="annonces"><h2>🔎 Résultats de recherche</h2>';

        if ($stmt->rowCount() == 0) {
            echo '<p class="text-gray-500">Aucun trajet trouvé pour cette recherche.</p>';
        } else {
            while ($annonce = $stmt->fetch()) {
                echo '
                <div class="annonce">
                    <div class="annonce-title">'.htmlspecialchars($annonce["villeDepart"]).' → '.htmlspecialchars($annonce["villeArrivee"]).'</div>
                    <div class="annonce-details">
                        <div><strong>Date :</strong> '.$annonce["date"].'</div>
                        <div><strong>Prix :</strong> '.$annonce["prix"].' DH</div>
                        <div><strong>Places :</strong> '.$annonce["placesDisponibles"].'</div>
                        <div><strong>Conducteur :</strong> '.$annonce["prenom"].' '.$annonce["nom"].'</div>
                    </div>
                </div>';
            }
        }

        echo '</section>';
    } catch (Exception $e) {
        echo 'Erreur : '.$e->getMessage();
    }
}
?>


        <section id="demander" class="form-section">
            <h2>🙋‍♀️ Demander un covoiturage</h2>
            <form method="post" action="demande_covoiturage.php">
                <div class="form-group">
                    <input type="text" name="nom" placeholder="Nom" class="form-input" required />
                    <input type="text" name="prenom" placeholder="Prénom" class="form-input" required />
                </div>
                <input type="text" name="villeDepart" placeholder="Ville de départ" class="form-input" required />
                <input type="text" name="villeArrivee" placeholder="Ville d'arrivée" class="form-input" required />
                <input type="date" name="date" class="form-input" required />
                <input type="number" name="prix_max" placeholder="Prix maximum souhaité (en DH)" class="form-input" />
                <textarea name="remarques" placeholder="Remarques (heure souhaitée, bagages, etc.)" class="form-input" rows="4"></textarea>
                <button type="submit" class="btn btn-submit btn-green">Envoyer la demande</button>
            </form>
        </section>
    </div>
    <?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=123;charset=utf8', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $demande = $bdd->query('SELECT * FROM demande ORDER BY date DESC');

    echo '<section class="annonces"><h2>📬 Demandes envoyées</h2>';

    if ($demande->rowCount() == 0) {
        echo '<p class="text-gray-500">Aucune demande pour le moment.</p>';
    } else {
        while ($row = $demande->fetch()) {
            echo '
            <div class="annonce">
                <div class="annonce-title">'.htmlspecialchars($row["villeDepart"]).' → '.htmlspecialchars($row["villeArrivee"]).'</div>
                <div class="annonce-details">
                    <div><strong>Date :</strong> '.$row["date"].'</div>
                    <div><strong>Prix max :</strong> '.($row["prix_max"] ?? 'Non précisé').' DH</div>
                    <div><strong>Demandeur :</strong> '.$row["prenom"].' '.$row["nom"].'</div>
                </div>';
            if (!empty($row["remarques"])) {
                echo '<div><strong>Remarques :</strong> '.htmlspecialchars($row["remarques"]).'</div>';
            }
            echo '</div>';
        }
    }

    echo '</section>';
} catch (Exception $e) {
    echo 'Erreur : '.$e->getMessage();
}
?>


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