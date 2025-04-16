<?php
require_once '../../config.php';
require_once '../../model/Voiture.php';

class VoitureController
{
    private $pdo;

    public function __construct()
    {
        // Récupération de la connexion PDO via Config
        $this->pdo = Config::getConnexion();
    }

    // CRUD Methods

   public function getAllVoitures() {
    try {
        $stmt = $this->pdo->prepare("SELECT * FROM voiture");
        $stmt->execute();
        
        $voitures = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Vérification explicite de l'existence des clés
            $id = isset($row['id_voiture']) ? $row['id_voiture'] : null;
            $matricule = isset($row['matricule']) ? $row['matricule'] : '';
            $marque = isset($row['marque']) ? $row['marque'] : '';
            $modele = isset($row['modele']) ? $row['modele'] : '';
            $couleur = isset($row['couleur']) ? $row['couleur'] : '';
            $nb_place = isset($row['nb_place']) ? $row['nb_place'] : 0;
            $statut = isset($row['statut']) ? $row['statut'] : 'inconnu';
            
            $voitures[] = new Voiture($id, $matricule, $marque, $modele, $couleur, $nb_place, $statut);
        }
        return $voitures;
    } catch (PDOException $e) {
        error_log("Erreur dans getAllVoitures: " . $e->getMessage());
        return [];
    }
}

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
                    $row['statut']
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
                                        (matricule, marque, modele, couleur, nb_place, statut) 
                                        VALUES (?, ?, ?, ?, ?, ?)");
            
            return $stmt->execute([
                $voiture->getMatricule(),
                $voiture->getMarque(),
                $voiture->getModele(),
                $voiture->getCouleur(),
                $voiture->getNbPlace(),
                $voiture->getStatut()
            ]);
        } catch (PDOException $e) {
            error_log("Erreur dans addVoiture: " . $e->getMessage());
            return false;
        }
    }

    public function updateVoiture(Voiture $voiture)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE voiture SET 
                                       matricule = ?, 
                                       marque = ?, 
                                       modele = ?, 
                                       couleur = ?, 
                                       nb_place = ?, 
                                       statut = ? 
                                       WHERE id_voiture = ?");
            
            return $stmt->execute([
                $voiture->getMatricule(),
                $voiture->getMarque(),
                $voiture->getModele(),
                $voiture->getCouleur(),
                $voiture->getNbPlace(),
                $voiture->getStatut(),
                $voiture->getId()
            ]);
        } catch (PDOException $e) {
            error_log("Erreur dans updateVoiture: " . $e->getMessage());
            return false;
        }
    }

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