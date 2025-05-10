<?php
require_once('../../model/User.php');
require_once('../../config.php');

class UserController
{
    public function getUserById($id) {
        $db = config::getConnexion();
        $stmt = $db->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new User(
                $data['id'],
                $data['nom'],
                $data['email'],
                $data['tel'],
                $data['mdp'],
                $data['role'],
                new DateTime($data['date_creation']),
                $data['photo'],
                $data['status']
            );
        }
        return null;
    }

    public function getUserByEmail($email) {
        $db = config::getConnexion();
        $stmt = $db->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new User(
                $row['id'],
                $row['nom'],
                $row['email'],
                $row['tel'],
                $row['mdp'],
                $row['role'],
                new DateTime($row['date_creation']),
                $row['photo'],
                $row['status']
            );
        }
        return null;
    }

    public function listUser() {
        $db = config::getConnexion();
        try {
            $sql = "SELECT * FROM user";
            return $db->query($sql);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function showUser($id) {
        $db = config::getConnexion();
        $stmt = $db->prepare("SELECT * FROM user WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteUser($id) {
        $db = config::getConnexion();
        $stmt = $db->prepare("DELETE FROM user WHERE id = :id");
        try {
            $stmt->execute(['id' => $id]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function updateUser($userId, $data, $currentUserRole) {
        $db = config::getConnexion();
        $allowedFields = ['nom', 'email', 'tel', 'mdp', 'photo'];
        $updates = [];
        $params = [];

        if ($currentUserRole === 'client' && !empty($data['mdp'])) {
            $data['mdp'] = password_hash($data['mdp'], PASSWORD_DEFAULT);
        }

        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $updates[] = "$key = ?";
                $params[] = $value;
            }
        }

        if (empty($updates)) return false;

        $sql = "UPDATE user SET " . implode(', ', $updates) . " WHERE id = ?";
        $params[] = $userId;

        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    public function addUser($user) {
        $db = config::getConnexion();
        $sql = "INSERT INTO user (nom, email, tel, mdp, role, date_creation, photo, status) 
                VALUES (:nom, :email, :tel, :mdp, :role, :date_creation, :photo, :status)";
        $stmt = $db->prepare($sql);

        try {
            $stmt->execute([
                'nom' => $user->getNom(),
                'email' => $user->getEmail(),
                'tel' => $user->getTel(),
                'mdp' => $user->getMdp(),
                'role' => $user->getRole(),
                'date_creation' => $user->getDateCreation()->format('Y-m-d H:i:s'),
                'photo' => $user->getPhoto(),
                'status' => $user->getStatus()
            ]);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function banUser($userId) {
        $db = config::getConnexion();
        $stmt = $db->prepare("UPDATE user SET status = 'banned' WHERE id = :id");
        $stmt->execute(['id' => $userId]);
    }

    public function unbanUser($userId) {
        $db = config::getConnexion();
        $stmt = $db->prepare("UPDATE user SET status = 'active' WHERE id = :id");
        $stmt->execute(['id' => $userId]);
    }

    public function saveResetToken($userId, $token) {
        $db = config::getConnexion();
        $expiry = (new DateTime())->modify('+15 minutes')->format('Y-m-d H:i:s');
        $stmt = $db->prepare("UPDATE user SET reset_token = :token, reset_token_expiry = :expiry WHERE id = :id");
        $stmt->execute([
            'token' => $token,
            'expiry' => $expiry,
            // 'id' => $userId
        ]);
    }

    public function validateResetToken($token) {
        $db = config::getConnexion();
        $stmt = $db->prepare("SELECT * FROM user WHERE reset_token = :token AND reset_token_expiry > NOW()");
        $stmt->execute(['token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function clearResetToken($userId) {
        $db = config::getConnexion();
        $stmt = $db->prepare("UPDATE user SET reset_token = NULL, reset_token_expiry = NULL WHERE id = :id");
        $stmt->execute(['id' => $userId]);
    }

    public function emailExists($email) {
        $db = config::getConnexion();
        $stmt = $db->prepare("SELECT COUNT(*) FROM user WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    public function updatePassword($userId, $newPassword) {
        $db = config::getConnexion();
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE user SET mdp = :mdp WHERE id = :id");
        $stmt->execute([
            'mdp' => $hashedPassword,
            'id' => $userId
        ]);
    }

    public function searchUsers($query) {
        $db = config::getConnexion();
        $stmt = $db->prepare("SELECT * FROM user WHERE nom LIKE :query OR email LIKE :query");
        $stmt->execute(['query' => '%' . $query . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUserRole($userId, $newRole) {
        $db = config::getConnexion();
        $stmt = $db->prepare("UPDATE user SET role = :role WHERE id = :id");
        $stmt->execute([
            'role' => $newRole,
            'id' => $userId
        ]);
    }
}