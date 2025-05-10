<?php

class User {
    private ?int $id;
    private ?string $nom;
    private ?string $email;
    private ?string $tel;
    private ?string $mdp;
    private ?string $role;
    private ?DateTime $date_creation;
    private ?string $photo; // Attribute for user photo
    private ?string $status; // Attribute for user status

    // Constructor
    public function __construct(
        ?int $id,
        ?string $nom,
        ?string $email,
        ?string $tel,
        ?string $mdp,
        ?string $role,
        ?DateTime $date_creation,
        ?string $photo = null, // Default value for photo
        ?string $status = 'active' // Default value for status
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
        $this->tel = $tel;
        $this->mdp = $mdp;
        $this->role = $role;
        $this->date_creation = $date_creation;
        $this->photo = $photo;
        $this->status = $status;
    }

    // Getters and Setters

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function getNom(): ?string {
        return $this->nom;
    }

    public function setNom(?string $nom): void {
        $this->nom = $nom;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(?string $email): void {
        $this->email = $email;
    }

    public function getTel(): ?string {
        return $this->tel;
    }

    public function setTel(?string $tel): void {
        $this->tel = $tel;
    }

    public function getMdp(): ?string {
        return $this->mdp;
    }

    public function setMdp(?string $mdp): void {
        $this->mdp = $mdp;
    }

    public function getRole(): ?string {
        return $this->role;
    }

    public function setRole(?string $role): void {
        $this->role = $role;
    }

    public function getDateCreation(): ?DateTime {
        return $this->date_creation;
    }

    public function setDateCreation(?DateTime $date_creation): void {
        $this->date_creation = $date_creation;
    }

    public function getPhoto(): ?string {
        return $this->photo;
    }

    public function setPhoto(?string $photo): void {
        $this->photo = $photo;
    }

    public function getStatus(): ?string {
        return $this->status;
    }

    public function setStatus(?string $status): void {
        $this->status = $status;
    }
}

?>