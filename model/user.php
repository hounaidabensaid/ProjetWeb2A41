<?php

class User {
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $mot_de_passe;
    private $date_naissance;
    private $role;

    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getPrenom() { return $this->prenom; }
    public function getEmail() { return $this->email; }
    public function getMotDePasse() { return $this->mot_de_passe; }
    public function getDateNaissance() { return $this->date_naissance; }
    public function getRole() { return $this->role; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setNom($nom) { $this->nom = $nom; }
    public function setPrenom($prenom) { $this->prenom = $prenom; }
    public function setEmail($email) { $this->email = $email; }
    public function setMotDePasse($mot_de_passe) { $this->mot_de_passe = $mot_de_passe; }
    public function setDateNaissance($date_naissance) { $this->date_naissance = $date_naissance; }
    public function setRole($role) { $this->role = $role; }
}
?>
