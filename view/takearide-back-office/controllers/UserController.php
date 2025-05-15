<?php
require_once '../config.php';

class UserController
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = config::getConnexion();
    }

    /**
     * 🔹 Récupérer tous les utilisateurs (utile pour les listes déroulantes)
     */
    public function getAllUsers(): array
    {
        $sql = "SELECT id, nom, email FROM user";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 🔹 Récupérer les autres utilisateurs (exclut l'utilisateur connecté)
     */
    public function getOtherUsers(string $currentUserNom, string $search = ''): array
    {
        if (!empty($search)) {
            $sql = "SELECT * FROM user WHERE nom != :nom AND (nom LIKE :search OR email LIKE :search)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':nom' => $currentUserNom,
                ':search' => "%{$search}%"
            ]);
        } else {
            $sql = "SELECT * FROM user WHERE nom != :nom";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':nom' => $currentUserNom]);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 🔹 Récupérer un utilisateur par son ID
     */
    public function getUserById(int $id): ?array
    {
        $sql = "SELECT * FROM user WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * 🔹 Récupérer un utilisateur par son nom
     */
    public function getUserByUsername(string $username): ?array
    {
        $sql = "SELECT * FROM user WHERE nom = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * 🔹 Récupérer un utilisateur par son email
     */
    public function getUserByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM user WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}
?>