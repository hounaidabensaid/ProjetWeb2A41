<?php
require_once(__DIR__ . "/../config.php");

class messageController {

   public function listeMessages($idsender, $idreceiver) {
    $db = config::getConnexion();
    $stmt = $db->prepare("
        SELECT 
            m.id AS message_id,
            m.content,
            m.filePath,
            m.is_voice,
            m.isread,
            m.reaction,
            m.created_at AS message_created_at,
            c.idsender,
            c.idreciever
        FROM messages m
        JOIN chatbox c ON m.idChatbox = c.idChatbox
        WHERE (c.idsender = :idsender AND c.idreciever = :idreceiver)
           OR (c.idsender = :idreceiver AND c.idreciever = :idsender)
        ORDER BY m.created_at ASC
    ");

    $stmt->bindParam(':idsender', $idsender, PDO::PARAM_INT);
    $stmt->bindParam(':idreceiver', $idreceiver, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function addMessage($content, $idChatbox, $filePath = null, $isVoice = 0) {
    $db = config::getConnexion();
    $stmt = $db->prepare("INSERT INTO messages (content, filePath, is_voice, created_at, idChatbox)
                          VALUES (:content, :filePath, :isVoice, NOW(), :idChatbox)");
    $stmt->bindValue(':content', $content, PDO::PARAM_STR);
    $stmt->bindValue(':filePath', $filePath, PDO::PARAM_STR);
    $stmt->bindValue(':isVoice', $isVoice, PDO::PARAM_BOOL);
    $stmt->bindValue(':idChatbox', $idChatbox, PDO::PARAM_INT);
    return $stmt->execute();
}


    

    public function getMessageById($id) {
        $db = config::getConnexion();
        $stmt = $db->prepare("SELECT * FROM messages WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateMessage($id, $content) {
        $db = config::getConnexion();
        $stmt = $db->prepare("UPDATE messages SET content = :content WHERE id = :id");
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteMessage($id) {
        $db = config::getConnexion();
        $stmt = $db->prepare("DELETE FROM messages WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function updateMessageReaction($messageId, $reaction) {
        
            $db = config::getConnexion();
            try{
                $stmt = $db->prepare("UPDATE messages SET reaction = :reaction WHERE id = :messageId");
                $stmt->bindParam(':reaction', $reaction, PDO::PARAM_STR);
                $stmt->bindParam(':messageId', $messageId, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
            catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
                return false;
            }
           

        }
        public function markMessagesAsRead($idsender, $idreceiver) {
            $db = config::getConnexion();
            $stmt = $db->prepare("UPDATE messages SET isread = 1 WHERE idChatbox IN (SELECT idChatbox FROM chatbox WHERE idsender = :idsender AND idreciever = :idreceiver)");
            $stmt->bindParam(':idsender', $idsender, PDO::PARAM_INT);
            $stmt->bindParam(':idreceiver', $idreceiver, PDO::PARAM_INT);
            return $stmt->execute();
        }
}
?>
