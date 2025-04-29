<?php
require_once '../../config.php';
require_once '../../model/User.php';

class UserController {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Ajouter un utilisateur
    public function addUser(User $user) {
        $sql = "INSERT INTO user (nom, prenom, email, mot_de_passe, date_naissance, role)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $user->getNom(),
            $user->getPrenom(),
            $user->getEmail(),
            $user->getMotDePasse(),
            $user->getDateNaissance(),
            $user->getRole()
        ]);
    }

    // Récupérer tous les utilisateurs
    public function getAllUsers() {
        $sql = "SELECT * FROM user";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un utilisateur par ID
    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Modifier un utilisateur
    public function updateUser(User $user) {
        $sql = "UPDATE user SET nom = ?, prenom = ?, email = ?, mot_de_passe = ?, date_naissance = ?, role = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $user->getNom(),
            $user->getPrenom(),
            $user->getEmail(),
            $user->getMotDePasse(),
            $user->getDateNaissance(),
            $user->getRole(),
            $user->getId()
        ]);
    }

    // Supprimer un utilisateur
    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("DELETE FROM user WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
