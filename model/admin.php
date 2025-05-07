<?php
class Admin {
    private $id;
    private $nom;

    public function __construct($id, $nom) {
        $this->id = $id;
        $this->nom = $nom;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    // Setters
    public function setId($id) {
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidArgumentException("L'ID doit être un entier positif.");
        }
        $this->id = $id;
    }

    public function setNom($nom) {
        if (empty($nom)) {
            throw new InvalidArgumentException("Le nom ne peut pas être vide.");
        }
        $this->nom = $nom;
    }
}
?>