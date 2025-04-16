<?php
class Reclamation {
    private $id;
    private $type;
    private $nom_chauffeur;
    private $date_trajet;
    private $sujet;
    private $description;
    private $gravite;
    private $piece_jointe;
    private $statut;

    public function __construct($type, $nom_chauffeur, $date_trajet, $sujet, $description, $gravite, $piece_jointe = null, $statut = 'nouveau') {
        $this->type = $type;
        $this->nom_chauffeur = $nom_chauffeur;
        $this->date_trajet = $date_trajet;
        $this->sujet = $sujet;
        $this->description = $description;
        $this->gravite = $gravite;
        $this->piece_jointe = $piece_jointe;
        $this->statut = $statut;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getType() {
        return $this->type;
    }

    public function getNomChauffeur() {
        return $this->nom_chauffeur;
    }

    public function getDateTrajet() {
        return $this->date_trajet;
    }

    public function getSujet() {
        return $this->sujet;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getGravite() {
        return $this->gravite;
    }

    public function getPieceJointe() {
        return $this->piece_jointe;
    }

    public function getStatut() {
        return $this->statut;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setNomChauffeur($nom_chauffeur) {
        $this->nom_chauffeur = $nom_chauffeur;
    }

    public function setDateTrajet($date_trajet) {
        $this->date_trajet = $date_trajet;
    }

    public function setSujet($sujet) {
        $this->sujet = $sujet;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setGravite($gravite) {
        $this->gravite = $gravite;
    }

    public function setPieceJointe($piece_jointe) {
        $this->piece_jointe = $piece_jointe;
    }

    public function setStatut($statut) {
        $this->statut = $statut;
    }
}
?>