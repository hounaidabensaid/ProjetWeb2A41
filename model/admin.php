<?php
class Admin {
    private $id;
    private $nom;

    public function __construct($nom) {
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
        $this->id = $id;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }
}
?>
