<?php
require_once(__DIR__ . "/../config.php");

class userController {

    // 🔍 Obtenir tous les utilisateurs sauf celui connecté, avec option de recherche
    public function getOtherUsers($username, $search = '')
    {
        $db = config::getConnexion();
        try {
            if (!empty($search)) {
                $sql = "SELECT * FROM user 
                        WHERE nom != :username 
                        AND (nom LIKE :search OR email LIKE :search)";
                $stmt = $db->prepare($sql);
                $searchWildcard = "%" . $search . "%";
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt->bindParam(':search', $searchWildcard, PDO::PARAM_STR);
            } else {
                $sql = "SELECT * FROM user WHERE nom != :username";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }

    // 👤 Obtenir un utilisateur par ID
    public function getUserById($id)
    {
        $db = config::getConnexion();
        try {
            $stmt = $db->prepare("SELECT * FROM user WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return null;
        }
    }

    // 🔎 Obtenir un utilisateur par son nom (nom = username dans ta table)
    public function getUserByUsername($username)
    {
        $db = config::getConnexion();
        try {
            $stmt = $db->prepare("SELECT * FROM user WHERE nom = :username");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return null;
        }
    }

    // ✉️ Obtenir un utilisateur par email
    public function getUserByEmail($email)
    {
        $db = config::getConnexion();
        try {
            $stmt = $db->prepare("SELECT * FROM user WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return null;
        }
    }
}
