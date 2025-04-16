<?php
// controllers/ParticipantController.php

require_once __DIR__ . '/../models/Participant.php';
require_once __DIR__ . '/../models/Event.php';

class ParticipantController
{
    public function showAddForm()
    {
        $eventModel = new Event();
        $evenements = $eventModel->getAll();

        include __DIR__ . '/../includes/header.php';
        include __DIR__ . '/../views/participants/add.php';
        include __DIR__ . '/../includes/footer.php';
    }

    public function save()
    {
        $nom = $_POST['nom'] ?? '';
        $email = $_POST['email'] ?? '';
        $evenement_id = $_POST['evenement_id'] ?? null;

        $participantModel = new Participant();
        $participantModel->save($nom, $email, $evenement_id);

        header("Location: index.php?action=listParticipants");
        exit();
    }

    public function index()
    {
        $participantModel = new Participant();
        $participants = $participantModel->getAll();

        include __DIR__ . '/../includes/header.php';
        include __DIR__ . '/../views/participants/index.php';
        include __DIR__ . '/../includes/footer.php';
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $participantModel = new Participant();
            $eventModel = new Event();

            $participant = $participantModel->getById($id);
            $evenements = $eventModel->getAll();

            include __DIR__ . '/../includes/header.php';
            include __DIR__ . '/../views/participants/edit.php';
            include __DIR__ . '/../includes/footer.php';
        }
    }

    public function update()
    {
        $id = $_POST['id'] ?? null;
        $nom = $_POST['nom'] ?? '';
        $email = $_POST['email'] ?? '';
        $evenement_id = $_POST['evenement_id'] ?? null;

        $participantModel = new Participant();
        $participantModel->update($id, $nom, $email, $evenement_id);

        header("Location: index.php?action=listParticipants");
        exit();
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $participantModel = new Participant();
            $participantModel->delete($id);
        }

        header("Location: index.php?action=listParticipants");
        exit();
    }
}
