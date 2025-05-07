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

    // Getters
    public function getId() {
        return $this->id;
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

    // Setters
    public function setId($id) {
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidArgumentException("L'ID doit être un entier positif.");
        }
        $this->id = $id;
        return $this;
    }

    public function setReclamationId($reclamation_id) {
        if (!is_numeric($reclamation_id) || $reclamation_id <= 0) {
            throw new InvalidArgumentException("L'ID de la réclamation doit être un entier positif.");
        }
        $this->reclamation_id = $reclamation_id;
    }

    public function setAdminId($admin_id) {
        if (!is_numeric($admin_id) || $admin_id <= 0) {
            throw new InvalidArgumentException("L'ID de l'admin doit être un entier positif.");
        }
        $this->admin_id = $admin_id;
    }

    public function setContenu($contenu) {
        if (empty($contenu)) {
            throw new InvalidArgumentException("Le contenu ne peut pas être vide.");
        }
        $this->contenu = $contenu;
    }

    public function setPieceJointe($piece_jointe) {
        $this->piece_jointe = $piece_jointe;
    }

    public function setDateCreation($date_creation) {
        if (!empty($date_creation) && !strtotime($date_creation)) {
            throw new InvalidArgumentException("La date de création doit être une date valide.");
        }
        $this->date_creation = $date_creation;
    }
}
?>