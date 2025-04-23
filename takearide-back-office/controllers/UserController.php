<?php
require_once '../config.php';

class UserController
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = config::getConnexion();
    }

    // 🔹 Récupérer tous les utilisateurs (utile pour les listes déroulantes)
    public function getAllUsers()
    {
        $sql = "SELECT id, nom, prenom, email FROM user";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    // 🔹 Ajouter un utilisateur
    public function addUser($nom, $prenom, $email, $password)
    {
        $sql = "INSERT INTO user (nom, prenom, email, password) 
                VALUES (:nom, :prenom, :email, :password)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom'      => $nom,
            ':prenom'   => $prenom,
            ':email'    => $email,
            ':password' => password_hash($password, PASSWORD_BCRYPT)  // Sécurisation du mot de passe
        ]);
    }

    // 🔹 Récupérer un utilisateur par ID
    public function getUserById($id)
    {
        $sql = "SELECT * FROM user WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // 🔹 Mettre à jour un utilisateur
    public function updateUser($id, $nom, $prenom, $email)
    {
        $sql = "UPDATE user 
                SET nom = :nom, prenom = :prenom, email = :email
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom'    => $nom,
            ':prenom' => $prenom,
            ':email'  => $email,
            ':id'     => $id
        ]);
    }

    // 🔹 Supprimer un utilisateur
    public function deleteUser($id)
    {
        $sql = "DELETE FROM user WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
?>
