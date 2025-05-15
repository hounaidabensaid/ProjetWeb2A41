<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/Recl.php';

class ReclamationController {
    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    public function getReclamations() {
        $sql = "SELECT id, type, nom_chauffeur, email, date_trajet, sujet, description, gravite, piece_jointe, statut FROM reclamations";
        try {
            $query = $this->db->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Erreur: ' . $e->getMessage());
        }
    }

    public function addReclamation($reclamation) {
        $sql = "INSERT INTO reclamations (type, nom_chauffeur, email, date_trajet, sujet, description, gravite, piece_jointe) 
                VALUES (:type, :nom_chauffeur, :email, :date_trajet, :sujet, :description, :gravite, :piece_jointe)";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':type', $reclamation->getType());
            $query->bindValue(':nom_chauffeur', $reclamation->getNomChauffeur());
            $query->bindValue(':email', $reclamation->getEmail());
            $query->bindValue(':date_trajet', $reclamation->getDateTrajet());
            $query->bindValue(':sujet', $reclamation->getSujet());
            $query->bindValue(':description', $reclamation->getDescription());
            // Ensure gravite is not NULL; use a default if necessary
            $gravite = $reclamation->getGravite() ?? 'moyenne';
            $query->bindValue(':gravite', $gravite);
            $query->bindValue(':piece_jointe', $reclamation->getPieceJointe(), PDO::PARAM_STR | PDO::PARAM_NULL);
            $query->execute();
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception('Erreur lors de l\'ajout de la réclamation: ' . $e->getMessage());
        }
    }

    public function getReclamationById($id) {
        $query = "SELECT id, type, nom_chauffeur, email, date_trajet, sujet, description, gravite, piece_jointe, statut 
                  FROM reclamations WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateReclamation(Reclamation $reclamation) {
        $query = "UPDATE reclamations 
                  SET type = :type, nom_chauffeur = :nom_chauffeur, email = :email, 
                      date_trajet = :date_trajet, sujet = :sujet, 
                      description = :description, gravite = :gravite,
                      piece_jointe = :piece_jointe, statut = :statut
                  WHERE id = :id";
        $stmt = $this->db->prepare($query);

        $id = $reclamation->getId();
        $type = $reclamation->getType();
        $nomChauffeur = $reclamation->getNomChauffeur();
        $email = $reclamation->getEmail();
        $dateTrajet = $reclamation->getDateTrajet();
        $sujet = $reclamation->getSujet();
        $description = $reclamation->getDescription();
        $gravite = $reclamation->getGravite() ?? 'moyenne'; // Ensure gravite is not NULL
        $pieceJointe = $reclamation->getPieceJointe();
        $statut = $reclamation->getStatut();

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':nom_chauffeur', $nomChauffeur);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':date_trajet', $dateTrajet);
        $stmt->bindParam(':sujet', $sujet);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':gravite', $gravite);
        $stmt->bindParam(':piece_jointe', $pieceJointe);
        $stmt->bindParam(':statut', $statut);

        if (!$stmt->execute()) {
            throw new Exception("Échec de la mise à jour de la réclamation.");
        }
    }

    public function deleteReclamation($id) {
        try {
            $query = $this->db->prepare('DELETE FROM reclamations WHERE id=:id');
            $query->bindValue(':id', $id);
            $query->execute();
            return true;
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    public function updateReclamationStatus($id, $status) {
        try {
            $query = $this->db->prepare('UPDATE reclamations SET statut = :statut WHERE id = :id');
            $query->bindValue(':id', $id);
            $query->bindValue(':statut', $status);
            $query->execute();
            return true;
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la mise à jour du statut: ' . $e->getMessage());
        }
    }
}
?>