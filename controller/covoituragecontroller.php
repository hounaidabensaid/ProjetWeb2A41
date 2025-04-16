<?php
require_once 'CovoiturageModel.php';

class CovoiturageController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new CovoiturageModel($pdo);
    }

    // Afficher toutes les annonces
    public function showAllAnnonces()
    {
        return $this->model->getAllAnnonces();
    }

    // Afficher une annonce par son ID
    public function showAnnonceById($id)
    {
        return $this->model->getAnnonceById($id);
    }

    // Ajouter une annonce
    public function addAnnonce($nom, $prenom, $villeDepart, $villeArrivee, $date, $prix, $matricule, $typeVehicule, $placesDisponibles, $details)
    {
        return $this->model->addAnnonce($nom, $prenom, $villeDepart, $villeArrivee, $date, $prix, $matricule, $typeVehicule, $placesDisponibles, $details);
    }

    // Modifier une annonce
    public function updateAnnonce($id, $nom, $prenom, $villeDepart, $villeArrivee, $date, $prix, $matricule, $typeVehicule, $placesDisponibles, $details)
    {
        return $this->model->updateAnnonce($id, $nom, $prenom, $villeDepart, $villeArrivee, $date, $prix, $matricule, $typeVehicule, $placesDisponibles, $details);
    }

    // Supprimer une annonce
    public function deleteAnnonce($id)
    {
        return $this->model->deleteAnnonce($id);
    }
}
?>

