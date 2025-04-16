<?php
// controllers/EventController.php

require_once __DIR__ . '/../models/Event.php';

class EventController
{
    public function showAddForm()
    {
        include __DIR__ . '/../views/events/add.php';
    }

    public function save()
    { echo "Dans EventController::save()<br>";
        print_r($_POST); // pour voir si les données sont bien là

        $titre = $_POST['titre'] ?? '';
        $description = $_POST['description'] ?? '';
        $lieu = $_POST['lieu'] ?? '';
        $date = $_POST['date'] ?? '';

        // Contrôle de saisie
        if (empty($titre) || empty($description) || empty($lieu) || empty($date)) {
            $error = "Tous les champs sont obligatoires.";
            include __DIR__ . '/../includes/header.php';
            include __DIR__ . '/../views/events/add.php';
            include __DIR__ . '/../includes/footer.php';
            return;
        }

        // Vérification de la date (à partir d'avril 2025)
        $minDate = strtotime('2025-04-01');
        $eventDate = strtotime($date);

        if ($eventDate < $minDate) {
            $error = "La date doit être à partir d'avril 2025.";
            include __DIR__ . '/../includes/header.php';
            include __DIR__ . '/../views/events/add.php';
            include __DIR__ . '/../includes/footer.php';
            return;
        }

        $event = new Event();
        $event->save($titre, $description, $lieu, $date);
        $_SESSION['flash'] = "Événement ajouté avec succès.";

        header("Location: index.php?action=list");
        exit();
    }


    public function index()
    {
        $eventModel = new Event();
        $events = $eventModel->getAll();

        include __DIR__ . '/../includes/header.php';
        include __DIR__ . '/../views/events/index.php';
        include __DIR__ . '/../includes/footer.php';
    }

    public function edit()
{
    $id = $_GET['id'] ?? null;

    if ($id) {
        $eventModel = new Event();
        $event = $eventModel->getById($id);

        if ($event) {
            include __DIR__ . '/../includes/header.php';
            include __DIR__ . '/../views/events/edit.php';
            include __DIR__ . '/../includes/footer.php';
        } else {
            echo "Événement introuvable.";
        }
    } else {
        echo "ID non spécifié.";
    }
}


public function update()
{ //echo "Appel de update()<br>";
   // print_r($_POST); // pour vérifier les valeurs envoyées

    $id = $_POST['id'];
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $lieu = $_POST['lieu'];
    $date = $_POST['date'];
// Contrôle de saisie
    if (empty($titre) || empty($description) || empty($lieu) || empty($date)) {
        $error = "Tous les champs sont obligatoires.";
        $event = compact('id', 'titre', 'description', 'lieu', 'date'); // Pour réafficher le formulaire rempli
        include __DIR__ . '/../includes/header.php';
        include __DIR__ . '/../views/events/edit.php';
        include __DIR__ . '/../includes/footer.php';
        return;
    }
// Vérification de la date (à partir d'avril 2025)
    $minDate = strtotime('2025-04-15');
    $eventDate = strtotime($date);
    if ($eventDate < $minDate) {
        $error = "La date doit être à partir d'aujourd'hui.";
        $event = compact('id', 'titre', 'description', 'lieu', 'date');
        include __DIR__ . '/../includes/header.php';
        include __DIR__ . '/../views/events/edit.php';
        include __DIR__ . '/../includes/footer.php';
        return;
    }

// Sauvegarde
    $eventModel = new Event();
    $eventModel->update($id, $titre, $description, $lieu, $date);
    $_SESSION['flash'] = "Événement modifié avec succès.";

    header("Location: index.php?action=list");
    exit();
}


    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $eventModel = new Event();
            $eventModel->delete($id);
        }
        $_SESSION['flash'] = "Événement supprimé avec succès.";

        header("Location: index.php?action=list");
        exit();
    }

}
