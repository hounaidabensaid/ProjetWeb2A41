<?php
// models/Participant.php

require_once __DIR__ . '/../config/db.php';

class Participant
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function getAll()
    {
        $query = "SELECT p.*, e.titre AS evenement_titre 
                  FROM participant p 
                  LEFT JOIN evenement e ON p.evenement_id = e.id";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM participant WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Nouvelle méthode : trouver ou créer un participant
    public function findOrCreate($nom, $email)
    {
        // Vérifie si un participant existe déjà avec cet email
        $stmt = $this->conn->prepare("SELECT id FROM participant WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Participant existant, on le retourne
            return $result->fetch_assoc()['id'];
        } else {
            // Créer un nouveau participant
            $stmt = $this->conn->prepare("INSERT INTO participant (nom, email) VALUES (?, ?)");
            $stmt->bind_param("ss", $nom, $email);
            $stmt->execute();
            return $this->conn->insert_id; // Retourne l'id du nouveau participant
        }
    }

    public function save($nom, $email, $evenement_id)
    {
        $stmt = $this->conn->prepare("INSERT INTO participant (nom, email, evenement_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nom, $email, $evenement_id);
        $stmt->execute();
    }

    public function update($id, $nom, $email, $evenement_id)
    {
        $stmt = $this->conn->prepare("UPDATE participant SET nom = ?, email = ?, evenement_id = ? WHERE id = ?");
        $stmt->bind_param("ssii", $nom, $email, $evenement_id, $id);
        $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM participant WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    public function getEvenements()
    {
        $result = $this->conn->query("SELECT id, titre FROM evenement");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
