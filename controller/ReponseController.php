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

    public function addReponse($reponse) {
        // Get admin_id, defaulting to 1 if it's not set
        $adminId = $reponse->getAdminId();
        if (is_null($adminId) || $adminId == '') {
            $adminId = 1;  // Default to admin with ID = 1 (since sara is the only admin)
        }
    
        // Check if admin exists in the database
        $sql = "SELECT COUNT(*) FROM admins WHERE id = :admin_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':admin_id', $adminId, PDO::PARAM_INT);
        $stmt->execute();
        
        $adminExists = $stmt->fetchColumn();
        if ($adminExists == 0) {
            throw new Exception('Admin with ID ' . $adminId . ' does not exist.');
        }
    
        // Prepare the date_creation
        $dateCreation = $reponse->getDateCreation() ?: date('Y-m-d H:i:s'); // Ensure a valid date
    
        // Proceed with inserting the response
        $sql = "INSERT INTO reponse (reclamation_id, admin_id, contenu, date_creation, piece_jointe) 
                VALUES (:reclamation_id, :admin_id, :contenu, :date_creation, :piece_jointe)";
    
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':reclamation_id', $reponse->getReclamationId());
            $query->bindValue(':admin_id', $adminId, PDO::PARAM_INT);
            $query->bindValue(':contenu', $reponse->getContenu());
            $query->bindValue(':date_creation', $dateCreation); // Bind date_creation here
            $query->bindValue(':piece_jointe', $reponse->getPieceJointe(), PDO::PARAM_STR);
    
            $query->execute();
            header("Location: view_reponse.php"); // Redirect to view page after inserting
            exit();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
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
            die('Erreur: ' . $e->getMessage());
        }
    }
}
?>
