<?php
require_once __DIR__ . '/../model/CovoiturageModel.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Twilio\Rest\Client;

class CovoiturageController
{
    private $model;
    private $twilioSid = 'US9b6fdc02f98985bef7fbb46aaf8a47a8';
    private $twilioToken = 'f7b5f659dfbd60ea9998d4f15ae5655c';
    private $twilioFromNumber = '+17073563756';

    public function __construct($pdo)
    {
        $this->model = new CovoiturageModel($pdo);
    }

    // Calculer la somme totale du prix pour une annonce donnée
    public function calculateTotalPriceForAnnonce($id_annonce)
    {
        $annonce = $this->model->getAnnonceById($id_annonce);
        if (!$annonce) {
            throw new Exception("Annonce non trouvée");
        }
        $demandes = $this->model->getDemandesByAnnonceId($id_annonce);
        $total = 0;
        foreach ($demandes as $demande) {
            $total += $demande['places'] * $annonce['prix'];
        }
        return ['total' => $total, 'telephone' => $annonce['telephone']];
    }

    // Send SMS using Twilio API
    private function sendSms($phoneNumber, $message)
    {
        error_log("Attempting to send SMS to $phoneNumber with message: $message");
        try {
            $client = new Client($this->twilioSid, $this->twilioToken);
            $messageInstance = $client->messages->create(
                $phoneNumber,
                [
                    'from' => $this->twilioFromNumber,
                    'body' => $message,
                    'statusCallback' => 'https://yourdomain.com/twilio-status-callback.php'
                ]
            );
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['sms_message'] = "SMS envoyé à $phoneNumber : $message. Message SID: " . $messageInstance->sid;
            error_log("SMS envoyé à $phoneNumber : $message. Message SID: " . $messageInstance->sid);
            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de l'envoi du SMS: " . $e->getMessage());
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['sms_error'] = "Erreur lors de l'envoi du SMS: " . $e->getMessage();
            return false;
        }
    }

    // Méthode pour calculer et envoyer le total par SMS
    public function sendTotalPriceSms($id_annonce)
    {
        $result = $this->calculateTotalPriceForAnnonce($id_annonce);
        $message = "Le total des prix pour votre annonce est: " . $result['total'] . " D";
        $phoneNumber = $result['telephone'];
        error_log("sendTotalPriceSms called with phone number: $phoneNumber and message: $message");
        // Ensure phone number has country code +216 prefix
        if (strpos($phoneNumber, '+216') !== 0) {
            $phoneNumber = '+216' . ltrim($phoneNumber, '0');
        }
        error_log("Formatted phone number for SMS: $phoneNumber");
        return $this->sendSms($phoneNumber, $message);
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
    public function addAnnonce($nom, $prenom, $villeDepart, $villeArrivee, $date, $prix, $matricule, $typeVehicule, $placesDisponibles, $details, $telephone, $user_id)
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
            'details' => $details,
            'telephone' => $telephone,
            'user_id' => $user_id
        ];
        $this->validateAnnonceData($data);
        return $this->model->addAnnonce($nom, $prenom, $villeDepart, $villeArrivee, $date, $prix, $matricule, $typeVehicule, $placesDisponibles, $details, $telephone, $user_id);
    }

    // Modifier une annonce
    public function updateAnnonce($id, $nom, $prenom, $villeDepart, $villeArrivee, $date, $prix, $matricule, $typeVehicule, $placesDisponibles, $details, $telephone)
    {
        return $this->model->updateAnnonce($id, $nom, $prenom, $villeDepart, $villeArrivee, $date, $prix, $matricule, $typeVehicule, $placesDisponibles, $details, $telephone);
    }

    // Supprimer une annonce
    public function deleteAnnonce($id)
    {
        return $this->model->deleteAnnonce($id);
    }

    public function addDemande($id_annonce, $nom, $prenom, $telephone, $email, $places, $user_id) {
        error_log("addDemande called with id_annonce: $id_annonce");
        $data = [
            'id_annonce' => $id_annonce,
            'nom' => $nom,
            'prenom' => $prenom,
            'telephone' => $telephone,
            'email' => $email,
            'places' => $places,
            'user_id' => $user_id
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
        $demande_id = $this->model->addDemande($id_annonce, $nom, $prenom, $telephone, $email, $places, $user_id);
        if ($demande_id) {
            error_log("Demande ajoutée avec succès, ID: $demande_id");
            $nouvelles_places = $annonce['placesDisponibles'] - $places;
            $this->model->updatePlacesDisponibles($id_annonce, $nouvelles_places);
            // Trigger sending total price SMS
            error_log("Calling sendTotalPriceSms for id_annonce: $id_annonce");
            $this->sendTotalPriceSms($id_annonce);
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
        $required = ['nom', 'prenom', 'villeDepart', 'villeArrivee', 'date', 'prix', 'matricule', 'typeVehicule', 'placesDisponibles', 'telephone'];
        
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
    // Méthode pour récupérer les statistiques des villes de départ et d'arrivée
    public function getStatsVille()
    {
        $statsDepart = $this->model->getStatsByVilleDepart();
        $statsArrivee = $this->model->getStatsByVilleArrivee();
        return [
            'depart' => $statsDepart,
            'arrivee' => $statsArrivee
        ];
    }
}
?>
