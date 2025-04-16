<?php
class CovoiturageModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Récupérer toutes les annonces
    public function getAllAnnonces()
    {
        $sql = "SELECT * FROM annonces";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter une annonce
    public function addAnnonce($nom, $prenom, $villeDepart, $villeArrivee, $date, $prix, $matricule, $typeVehicule, $placesDisponibles, $details)
    {
        $sql = "INSERT INTO annonces (nom, prenom, villeDepart, villeArrivee, date, prix, matricule, typeVehicule, placesDisponibles, details) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nom, $prenom, $villeDepart, $villeArrivee, $date, $prix, $matricule, $typeVehicule, $placesDisponibles, $details]);
    }

    // Modifier une annonce
    public function updateAnnonce($id, $nom, $prenom, $villeDepart, $villeArrivee, $date, $prix, $matricule, $typeVehicule, $placesDisponibles, $details)
    {
        $sql = "UPDATE annonces SET nom = ?, prenom = ?, villeDepart = ?, villeArrivee = ?, date = ?, prix = ?, matricule = ?, typeVehicule = ?, placesDisponibles = ?, details = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nom, $prenom, $villeDepart, $villeArrivee, $date, $prix, $matricule, $typeVehicule, $placesDisponibles, $details, $id]);
    }

    // Supprimer une annonce
    public function deleteAnnonce($id)
    {
        $sql = "DELETE FROM annonces WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Récupérer une annonce par son ID
    public function getAnnonceById($id)
    {
        $sql = "SELECT * FROM annonces WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
