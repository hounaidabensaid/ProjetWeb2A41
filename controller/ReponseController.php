<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/Reponse.php';

class ReponseController {
    private $db;

    public function __construct() {
        $this->db = config::getConnexion();
    }

    // Get all responses
    public function getReponses() {
        $sql = "SELECT * FROM reponse";
        try {
            $query = $this->db->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Erreur: ' . $e->getMessage());
        }
    }

    // Get reclamation details by ID
    public function getReclamationById($reclamationId) {
        $sql = "SELECT id, sujet, email FROM reclamations WHERE id = :reclamation_id";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':reclamation_id', $reclamationId, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la récupération de la réclamation: ' . $e->getMessage());
        }
    }

    public function addReponse($reponse) {
        // Get admin_id, defaulting to 1 if it's not set
        $id = $reponse->getid();
        if (is_null($id) || $id == '') {
            $id = 1; // Default to admin with ID = 1 (since sara is the only admin)
        }

        // Check if admin exists in the database
        $sql = "SELECT COUNT(*) FROM user WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $adminExists = $stmt->fetchColumn();
        if ($adminExists == 0) {
            throw new Exception('Admin with ID ' . $id . ' does not exist.');
        }

        // Prepare the date_creation
        $dateCreation = $reponse->getDateCreation() ?: date('Y-m-d H:i:s');

        // Insert the response
        $sql = "INSERT INTO reponse (reclamation_id, admin_id, contenu, date_creation, piece_jointe) 
                VALUES (:reclamation_id, :admin_id, :contenu, :date_creation, :piece_jointe)";
        
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':reclamation_id', $reponse->getReclamationId());
            $query->bindValue(':admin_id', $id, PDO::PARAM_INT);
            $query->bindValue(':contenu', $reponse->getContenu());
            $query->bindValue(':date_creation', $dateCreation);
            $query->bindValue(':piece_jointe', $reponse->getPieceJointe(), PDO::PARAM_STR | PDO::PARAM_NULL);
            $query->execute();
        } catch (Exception $e) {
            throw new Exception('Erreur lors de l\'enregistrement de la réponse: ' . $e->getMessage());
        }
    }

    // Get a response by ID
    public function getReponseById($id) {
        $query = "SELECT * FROM reponse WHERE id = :id";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Erreur: ' . $e->getMessage());
        }
    }

    public function updateReponse(Reponse $reponse) {
        $sql = "UPDATE reponse SET 
                contenu = :contenu, 
                piece_jointe = :piece_jointe 
                WHERE id = :id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':contenu', $reponse->getContenu(), PDO::PARAM_STR);
            $stmt->bindValue(':piece_jointe', $reponse->getPieceJointe(), PDO::PARAM_STR);
            $stmt->bindValue(':id', $reponse->getId(), PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Update error: " . $e->getMessage());
            return false;
        }
    }

    // Delete a response
    public function deleteReponse($id) {
        try {
            $query = $this->db->prepare('DELETE FROM reponse WHERE id = :id');
            $query->bindValue(':id', $id);
            $query->execute();
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la suppression: ' . $e->getMessage());
        }
    }
}
?>