<?php

class Reservation {
    private $id_reservation;
    private $id_voiture;
    private $id_user;
    private $date_début;
    private $date_fin;
    private $statut;

    // Getters
    public function getIdReservation() {
        return $this->id_reservation;
    }

    public function getIdVoiture() {
        return $this->id_voiture;
    }

    public function getIdUser() {
        return $this->id_user;
    }

    public function getDateDébut() {
        return $this->date_début;
    }

    public function getDateFin() {
        return $this->date_fin;
    }

    public function getStatut() {
        return $this->statut;
    }

    // Setters
    public function setIdReservation($id_reservation) {
        $this->id_reservation = $id_reservation;
    }

    public function setIdVoiture($id_voiture) {
        $this->id_voiture = $id_voiture;
    }

    public function setIdUser($id_user) {
        $this->id_user = $id_user;
    }

    public function setDateDébut($date_début) {
        $this->date_début = $date_début;
    }

    public function setDateFin($date_fin) {
        $this->date_fin = $date_fin;
    }

    public function setStatut($statut) {
        $this->statut = $statut;
    }
}
?>
