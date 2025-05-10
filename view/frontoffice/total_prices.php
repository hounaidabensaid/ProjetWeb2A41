<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controller/covoituragecontroller.php';

try {
    $pdo = Config::getConnexion();
    $controller = new CovoiturageController($pdo);

    $annonces = $controller->showAllAnnonces();

    $totalPrices = [];
    foreach ($annonces as $annonce) {
        $totalPriceData = $controller->calculateTotalPriceForAnnonce($annonce['id']);
        $totalPrices[] = [
            'id' => $annonce['id'],
            'villeDepart' => $annonce['villeDepart'],
            'villeArrivee' => $annonce['villeArrivee'],
            'date' => $annonce['date'],
            'conducteur' => $annonce['prenom'] . ' ' . $annonce['nom'],
            'totalPrice' => $totalPriceData['total']
        ];
    }
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Total Prix des Annonces</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 0;
        }
        header {
            background: linear-gradient(135deg, #c20303 0%, #3b0604 100%);
            color: #d6cdcd;
            padding: 3rem 4rem;
            display: flex;
            align-items: center;
            gap: 3rem;
        }
        header img {
            height: 200px;
            width: 200px;
        }
        header h1 {
            font-size: 4rem;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #c20303;
            text-shadow: 3px 3px 8px rgba(0, 0, 0, 0.8);
        }
        main {
            max-width: 900px;
            margin: 2rem auto;
            background: #1a1a1a;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 8px 20px rgba(194, 3, 3, 0.7);
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 12px;
            font-size: 1.1rem;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(194, 3, 3, 0.8);
            background: #2b0000;
            color: #f0dede;
        }
        th, td {
            padding: 1rem 1.5rem;
            text-align: left;
        }
        th {
            background-color: #660000;
            color: #f0dede;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: inset 0 -3px 5px rgba(255, 255, 255, 0.1);
        }
        tbody tr {
            background: #3d0000;
            border-radius: 15px;
            box-shadow: 0 2px 12px rgba(194, 3, 3, 0.6);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        tbody tr:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(255, 75, 43, 0.8);
            background: #660000;
            color: #fff0f0;
        }
        caption {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #c20303;
            text-align: center;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);
        }
    </style>
</head>
<body>
    <div id="animationOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: black; display: flex; flex-direction: column; justify-content: center; align-items: center; z-index: 9999; color: white; overflow: hidden;">
        <img id="loadingGif" src="images/car_trip.gif" alt="Loading Animation" style="width: 300px; height: 300px; animation: zoomInOut 3s ease-in-out infinite;">
        <div id="loadingMessage" style="font-size: 2.5rem; font-weight: bold; margin-top: 2rem; opacity: 0; transform: translateY(100px); animation: messageSlideIn 3s forwards;">
            Why drive alone broke when you can carpool and cash in!
        </div>
    </div>
    <header style="opacity: 0; transition: opacity 1s ease;">
        <img src="images/car_trip.gif" alt="Car Trip" />
        <h1>Total Prix des Annonces</h1>
        <input type="text" id="searchConducteur" placeholder="Rechercher par nom du conducteur" style="margin-top: 1rem; padding: 0.5rem; font-size: 1rem; border-radius: 0.5rem; border: 1px solid #c20303; width: 100%; max-width: 400px; box-sizing: border-box;" />
    </header>
    <main style="opacity: 0; transition: opacity 1s ease;">
        <table>
            <caption>Liste des annonces avec le total des prix</caption>
            <thead>
                <tr>
                    <th>Ville de départ</th>
                    <th>Ville d'arrivée</th>
                    <th>Date</th>
                    <th>Conducteur</th>
                    <th>Total Prix (D)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($totalPrices as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['villeDepart']) ?></td>
                    <td><?= htmlspecialchars($item['villeArrivee']) ?></td>
                    <td><?= htmlspecialchars($item['date']) ?></td>
                    <td><?= htmlspecialchars($item['conducteur']) ?></td>
                    <td><?= htmlspecialchars($item['totalPrice']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <script>
        // Animations keyframes
        const style = document.createElement('style');
        style.innerHTML = `
            @keyframes zoomInOut {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.2); }
            }
            @keyframes messageSlideIn {
                0% { opacity: 0; transform: translateY(100px); }
                100% { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);

        window.addEventListener('load', () => {
            const overlay = document.getElementById('animationOverlay');
            const header = document.querySelector('header');
            const main = document.querySelector('main');

            // Fade out overlay and fade in content
            setTimeout(() => {
                overlay.style.display = 'none';
                header.style.opacity = '1';
                main.style.opacity = '1';
            }, 3000); // 3 seconds animation
        });

        // Dynamic search by conducteur name
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchConducteur');
            const table = document.querySelector('table tbody');
            searchInput.addEventListener('input', () => {
                const filter = searchInput.value.toLowerCase();
                const rows = table.getElementsByTagName('tr');
                for (let i = 0; i < rows.length; i++) {
                    const conducteurCell = rows[i].getElementsByTagName('td')[3];
                    if (conducteurCell) {
                        const conducteurText = conducteurCell.textContent || conducteurCell.innerText;
                        if (conducteurText.toLowerCase().indexOf(filter) > -1) {
                            rows[i].style.display = '';
                        } else {
                            rows[i].style.display = 'none';
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
