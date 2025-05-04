<?php
class CovoiturageModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Récupérer une demande par son ID
    public function getDemandeById($id_demande) {
        $sql = "SELECT * FROM demande WHERE id_demande = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_demande]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer toutes les annonces
    public function getAllAnnonces()
    {
        $sql = "SELECT * FROM `123`";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter une annonce
    public function addAnnonce($nom, $prenom, $villeDepart, $villeArrivee, $date, $prix, $matricule, $typeVehicule, $placesDisponibles, $details)
    {
        $sql = "INSERT INTO `123` (nom, prenom, villeDepart, villeArrivee, date, prix, matricule, typeVehicule, placesDisponibles, details) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nom, $prenom, $villeDepart, $villeArrivee, $date, $prix, $matricule, $typeVehicule, $placesDisponibles, $details]);
    }

    // Modifier une annonce
    public function updateAnnonce($id, $nom, $prenom, $villeDepart, $villeArrivee, $date, $prix, $matricule, $typeVehicule, $placesDisponibles, $details)
    {
        $sql = "UPDATE `123` SET nom = ?, prenom = ?, villeDepart = ?, villeArrivee = ?, date = ?, prix = ?, matricule = ?, typeVehicule = ?, placesDisponibles = ?, details = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nom, $prenom, $villeDepart, $villeArrivee, $date, $prix, $matricule, $typeVehicule, $placesDisponibles, $details, $id]);
    }

    // Supprimer une annonce
    public function deleteAnnonce($id)
    {
        $sql = "DELETE FROM `123` WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Récupérer une annonce par son ID
    public function getAnnonceById($id)
    {
        $sql = "SELECT * FROM `123` WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function addDemande($id_annonce, $nom, $prenom, $telephone, $email, $places) {
        try {
            $this->pdo->beginTransaction();
            $sql = "INSERT INTO demande (id_annonce, nom, prenom, telephone, email, places) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_annonce, $nom, $prenom, $telephone, $email, $places]);
            $lastId = $this->pdo->lastInsertId();
            $this->pdo->commit();
            return $lastId;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Erreur insertion demande: " . $e->getMessage());
            throw $e;
        }
    }

    public function getDemandesByAnnonceId($id_annonce) {
        $sql = "SELECT * FROM demande WHERE id_annonce = ? ORDER BY id_demande DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_annonce]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updatePlacesDisponibles($id_annonce, $nouvelles_places) {
        $sql = "UPDATE `123` SET placesDisponibles = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nouvelles_places, $id_annonce]);
    }

    public function searchAnnonces($villeDepart, $villeArrivee, $date = null) {
        $sql = "SELECT * FROM `123` WHERE villeDepart LIKE ? AND villeArrivee LIKE ?";
        $params = ["%$villeDepart%", "%$villeArrivee%"];
        
        if ($date) {
            $sql .= " AND date = ?";
            $params[] = $date;
        }
        
        $sql .= " ORDER BY date ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteDemande($id_demande) {
        $sql = "DELETE FROM demande WHERE id_demande = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_demande]);
    }

    public function updateDemande($id_demande, $nom, $prenom, $telephone, $email, $places) {
        $sql = "UPDATE demande SET nom = ?, prenom = ?, telephone = ?, email = ?, places = ? WHERE id_demande = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nom, $prenom, $telephone, $email, $places, $id_demande]);
    }
    // Statistiques par ville de départ
    public function getStatsByVilleDepart()
    {
        $sql = "SELECT villeDepart, COUNT(*) as total FROM `123` GROUP BY villeDepart ORDER BY total DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Statistiques par ville d'arrivée
    public function getStatsByVilleArrivee()
    {
        $sql = "SELECT villeArrivee, COUNT(*) as total FROM `123` GROUP BY villeArrivee ORDER BY total DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
