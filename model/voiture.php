<?php
class Voiture {
    private $id_voiture;
    private $matricule;
    private $marque;
    private $modele;
    private $couleur;
    private $nb_place;
    private $statut;
	
private $image;


 
    public function __construct($id_voiture, $matricule, $marque, $modele, $couleur, $nb_place, $statut, $image = null) {
        $this->id_voiture = $id_voiture;
        $this->matricule = $matricule;
        $this->marque = $marque;
        $this->modele = $modele;
        $this->couleur = $couleur;
        $this->nb_place = $nb_place;
        $this->statut = $statut;
    $this->image = $image;
    }
	

    // Getters
	
public function getImage() {
    return $this->image;
}

public function setImage($image) {
    $this->image = $image;
}

    public function getId() { return $this->id_voiture; }
    public function getMatricule() { return $this->matricule; }
    public function getMarque() { return $this->marque; }
    public function getModele() { return $this->modele; }
    public function getCouleur() { return $this->couleur; }
    public function getNbPlace() { return $this->nb_place; }
    public function getStatut() { return $this->statut; }

    // Setters
    public function setMatricule($matricule) { $this->matricule = $matricule; }
    public function setMarque($marque) { $this->marque = $marque; }
    public function setModele($modele) { $this->modele = $modele; }
    public function setCouleur($couleur) { $this->couleur = $couleur; }
    public function setNbPlace($nb_place) { $this->nb_place = $nb_place; }
    public function setStatut($statut) { $this->statut = $statut; }
}
?>