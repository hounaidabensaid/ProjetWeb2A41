<?php
require_once __DIR__ . '/../model/CovoiturageModel.php';

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
        $data = [
            'nom' => $nom,
            'prenom' => $prenom,
            'villeDepart' => $villeDepart,
            'villeArrivee' => $villeArrivee,
            'date' => $date,
            'prix' => $prix,
            'matricule' => $matricule,
            'typeVehicule' => $typeVehicule,
            'placesDisponibles' => $placesDisponibles,
            'details' => $details
        ];
        $this->validateAnnonceData($data);
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

    public function addDemande($id_annonce, $nom, $prenom, $telephone, $email, $places) {
        $data = [
            'id_annonce' => $id_annonce,
            'nom' => $nom,
            'prenom' => $prenom,
            'telephone' => $telephone,
            'email' => $email,
            'places' => $places
        ];
        
        $this->validateDemandeData($data);
        
        // Vérifier si l'annonce existe et a assez de places
        $annonce = $this->model->getAnnonceById($id_annonce);
        if (!$annonce) {
            error_log("Annonce non trouvée pour id_annonce: $id_annonce");
            throw new Exception("L'annonce n'existe pas");
        }
        
        if ($annonce['placesDisponibles'] < $places) {
            error_log("Pas assez de places disponibles. Demandé: $places, Disponible: ".$annonce['placesDisponibles']);
            throw new Exception("Il n'y a pas assez de places disponibles");
        }
        
        // Ajouter la demande et mettre à jour les places disponibles
        $demande_id = $this->model->addDemande($id_annonce, $nom, $prenom, $telephone, $email, $places);
        if ($demande_id) {
            error_log("Demande ajoutée avec succès, ID: $demande_id");
            $nouvelles_places = $annonce['placesDisponibles'] - $places;
            $this->model->updatePlacesDisponibles($id_annonce, $nouvelles_places);
        } else {
            error_log("Échec de l'ajout de la demande pour id_annonce: $id_annonce");
            throw new Exception("Erreur lors de l'ajout de la demande");
        }
        
        return $demande_id;
    }

    public function getDemandesForAnnonce($id_annonce) {
        if (!is_numeric($id_annonce) || $id_annonce <= 0) {
            throw new InvalidArgumentException("ID d'annonce invalide");
        }
        return $this->model->getDemandesByAnnonceId($id_annonce);
    }

    private function validateAnnonceData($data) {
        $required = ['nom', 'prenom', 'villeDepart', 'villeArrivee', 'date', 'prix', 'matricule', 'typeVehicule', 'placesDisponibles'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new InvalidArgumentException("Le champ $field est requis");
            }
        }
        
        if (!is_numeric($data['prix']) || $data['prix'] <= 0) {
            throw new InvalidArgumentException("Le prix doit être un nombre positif");
        }
        
        if (!is_numeric($data['placesDisponibles']) || $data['placesDisponibles'] <= 0) {
            throw new InvalidArgumentException("Le nombre de places doit être un entier positif");
        }
        
        if (!strtotime($data['date'])) {
            throw new InvalidArgumentException("La date n'est pas valide");
        }
        
        if (strtotime($data['date']) < strtotime('today')) {
            throw new InvalidArgumentException("La date ne peut pas être dans le passé");
        }
    }

    private function validateDemandeData($data) {
        $required = ['nom', 'prenom', 'telephone', 'email', 'places', 'id_annonce'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new InvalidArgumentException("Le champ $field est requis");
            }
        }
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("L'adresse email n'est pas valide");
        }
        
        if (!preg_match('/^[0-9+]{8,15}$/', $data['telephone'])) {
            throw new InvalidArgumentException("Le numéro de téléphone n'est pas valide (8-15 chiffres)");
        }
        
        if (!is_numeric($data['places']) || $data['places'] <= 0 || $data['places'] > 8) {
            throw new InvalidArgumentException("Le nombre de places doit être entre 1 et 8");
        }
        
        if (!is_numeric($data['id_annonce']) || $data['id_annonce'] <= 0) {
            throw new InvalidArgumentException("ID d'annonce invalide");
        }
    }

    public function deleteDemande($id_demande) {
        return $this->model->deleteDemande($id_demande);
    }

    public function updateDemande($id_demande, $nom, $prenom, $telephone, $email, $places) {
        // Get the old demande to calculate difference in places
        $oldDemande = $this->model->getDemandeById($id_demande);
        if (!$oldDemande) {
            throw new Exception("Demande non trouvée");
        }

        $data = [
            'nom' => $nom,
            'prenom' => $prenom,
            'telephone' => $telephone,
            'email' => $email,
            'places' => $places,
            'id_demande' => $id_demande,
            'id_annonce' => $oldDemande['id_annonce']
        ];

        $this->validateDemandeData($data);

        // Update demande
        $updated = $this->model->updateDemande($id_demande, $nom, $prenom, $telephone, $email, $places);

        if ($updated) {
            // Calculate difference in places
            $diff = $oldDemande['places'] - $places;
            if ($diff != 0) {
                // Update placesDisponibles in annonce
                $annonce = $this->model->getAnnonceById($oldDemande['id_annonce']);
                if (!$annonce) {
                    throw new Exception("Annonce non trouvée");
                }
                $newPlacesDisponibles = $annonce['placesDisponibles'] + $diff;
                if ($newPlacesDisponibles < 0) {
                    throw new Exception("Nombre de places disponibles insuffisant");
                }
                $this->model->updatePlacesDisponibles($oldDemande['id_annonce'], $newPlacesDisponibles);
            }
        }

        return $updated;
    }
}
?>

