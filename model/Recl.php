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
    private $email;

    public function __construct($type, $nom_chauffeur, $date_trajet, $sujet, $description, $gravite, $piece_jointe = null, $statut = null, $email, $id = null) {
        $this->id = $id;
        $this->type = $type;
        $this->nom_chauffeur = $nom_chauffeur;
        $this->date_trajet = $date_trajet;
        $this->sujet = $sujet;
        $this->description = $description;
        $this->gravite = $gravite;
        $this->piece_jointe = $piece_jointe;
        $this->statut = $statut ?? 'en_attente';
        $this->email = $email;
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

    public function getEmail() {
        return $this->email;
    }

    // Setters
    public function setId($id) {
        if (!is_null($id) && (!is_numeric($id) || $id <= 0)) {
            throw new InvalidArgumentException("L'ID doit être un entier positif ou null.");
        }
        $this->id = $id;
    }

    public function setType($type) {
        if (empty($type)) {
            throw new InvalidArgumentException("Le type ne peut pas être vide.");
        }
        $this->type = $type;
    }

    public function setNomChauffeur($nom_chauffeur) {
        if (empty($nom_chauffeur)) {
            throw new InvalidArgumentException("Le nom du chauffeur ne peut pas être vide.");
        }
        $this->nom_chauffeur = $nom_chauffeur;
    }

    public function setDateTrajet($date_trajet) {
        if (empty($date_trajet)) {
            throw new InvalidArgumentException("La date du trajet ne peut pas être vide.");
        }
        $this->date_trajet = $date_trajet;
    }

    public function setSujet($sujet) {
        if (empty($sujet)) {
            throw new InvalidArgumentException("Le sujet ne peut pas être vide.");
        }
        $this->sujet = $sujet;
    }

    public function setDescription($description) {
        if (empty($description)) {
            throw new InvalidArgumentException("La description ne peut pas être vide.");
        }
        $this->description = $description;
    }

    public function setGravite($gravite) {
        if (empty($gravite)) {
            throw new InvalidArgumentException("La gravité ne peut pas être vide.");
        }
        $this->gravite = $gravite;
    }

    public function setPieceJointe($piece_jointe) {
        $this->piece_jointe = $piece_jointe;
    }

    public function setStatut($statut) {
        if (empty($statut)) {
            throw new InvalidArgumentException("Le statut ne peut pas être vide.");
        }
        $this->statut = $statut;
    }

    public function setEmail($email) {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("L'email doit être valide.");
        }
        $this->email = $email;
    }
}
?>