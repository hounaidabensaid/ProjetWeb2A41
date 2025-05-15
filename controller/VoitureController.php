<?php
require_once '../../config.php';
require_once '../../model/Voiture.php';

class VoitureController
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Config::getConnexion();
    }

    // Récupérer toutes les voitures
    public function getAllVoitures()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM voiture");
            $stmt->execute();

            $voitures = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $voitures[] = new Voiture(
                    $row['id_voiture'] ?? null,
                    $row['matricule'] ?? '',
                    $row['marque'] ?? '',
                    $row['modele'] ?? '',
                    $row['couleur'] ?? '',
                    $row['nb_place'] ?? 0,
                    $row['statut'] ?? 'inconnu',
                    $row['image'] ?? null
                );
            }
            return $voitures;
        } catch (PDOException $e) {
            error_log("Erreur dans getAllVoitures: " . $e->getMessage());
            return [];
        }
    }

    // Récupérer une voiture par son ID
    public function getVoitureById($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM voiture WHERE id_voiture = ?");
            $stmt->execute([$id]);

            $row = $stmt->fetch();
            if ($row) {
                return new Voiture(
                    $row['id_voiture'],
                    $row['matricule'],
                    $row['marque'],
                    $row['modele'],
                    $row['couleur'],
                    $row['nb_place'],
                    $row['statut'],
                    $row['image']
                );
            }
            return null;
        } catch (PDOException $e) {
            error_log("Erreur dans getVoitureById: " . $e->getMessage());
            return null;
        }
    }

   public function addVoiture(Voiture $voiture)
{
    try {
        $stmt = $this->pdo->prepare("INSERT INTO voiture 
            (matricule, marque, modele, couleur, nb_place, statut, image) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");

        $result = $stmt->execute([
            $voiture->getMatricule(),
            $voiture->getMarque(),
            $voiture->getModele(),
            $voiture->getCouleur(),
            $voiture->getNbPlace(),
            $voiture->getStatut(),
            $voiture->getImage()
        ]);

        return $result;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Duplicate entry
            throw new Exception("Le matricule existe déjà.");
        }
        error_log("Erreur dans addVoiture: " . $e->getMessage());
        throw new Exception("Failed to add car: " . $e->getMessage());
    }
}

    // Mettre à jour une voiture
    public function updateVoiture(Voiture $voiture)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE voiture SET 
                matricule = ?, 
                marque = ?, 
                modele = ?, 
                couleur = ?, 
                nb_place = ?, 
                statut = ?, 
                image = ?
                WHERE id_voiture = ?");

            return $stmt->execute([
                $voiture->getMatricule(),
                $voiture->getMarque(),
                $voiture->getModele(),
                $voiture->getCouleur(),
                $voiture->getNbPlace(),
                $voiture->getStatut(),
                $voiture->getImage(),
                $voiture->getId()
            ]);
        } catch (PDOException $e) {
            error_log("Erreur dans updateVoiture: " . $e->getMessage());
            return false;
        }
    }

    // Supprimer une voiture
    public function deleteVoiture($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM voiture WHERE id_voiture = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erreur dans deleteVoiture: " . $e->getMessage());
            return false;
        }
    }
}
?>
