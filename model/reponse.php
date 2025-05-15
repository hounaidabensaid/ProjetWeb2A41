<?php
class Reponse {
    private $id;
    private $reclamation_id;
    private $admin_id;
    private $contenu;
    private $piece_jointe;
    private $date_creation;

    public function __construct($reclamation_id, $admin_id, $contenu, $piece_jointe = null, $date_creation = null) {
        $this->reclamation_id = $reclamation_id;
        $this->admin_id = $admin_id;
        $this->contenu = $contenu;
        $this->piece_jointe = $piece_jointe;
        $this->date_creation = $date_creation;
    }

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
        return $this; // Optional: allows method chaining
    }

    public function getReclamationId() {
        return $this->reclamation_id;
    }

    public function getAdminId() {
        return $this->admin_id;
    }

    public function getContenu() {
        return $this->contenu;
    }

    public function getPieceJointe() {
        return $this->piece_jointe;
    }

    public function getDateCreation() {
        return $this->date_creation;
    }

    // Setters for updating data
    public function setContenu($contenu) {
        $this->contenu = $contenu;
    }

    public function setPieceJointe($piece_jointe) {
        $this->piece_jointe = $piece_jointe;
    }

    public function setDateCreation($date_creation) {
        $this->date_creation = $date_creation;
    }
}
