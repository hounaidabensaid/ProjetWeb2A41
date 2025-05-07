<?php
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/../../controller/messageController.php");
require_once(__DIR__ . "/../../controller/userController.php");
require_once(__DIR__ . "/../../controller/chatboxController.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $senderId = $_POST['senderId'];
    $receiverId = $_POST['idreciever'];
    $content = trim($_POST['messageInput']);
    $filePath = null;

    // Bad words array
    $badWords = [
        'stupid', 'badword2', 'curse', 'swear', 'racistterm',
        'example1', 'example2', 'profanity', 'offensiveterm'
    ];

    // Store original content for comparison
    $originalContent = $content;

    // Replace bad words with stars
    foreach ($badWords as $word) {
        $pattern = '/\b' . preg_quote($word, '/') . '\b/i';
        $replacement = str_repeat('*', strlen($word));
        $content = preg_replace($pattern, $replacement, $content);
    }

    // Show warning if content was modified
    if ($originalContent !== $content) {
        $_SESSION['warning'] = "Your message contained inappropriate words and was filtered.";
    }

    // 📎 Fichier joint
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $fileName = basename($_FILES['attachment']['name']);
        $targetPath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetPath)) {
            $filePath = 'uploads/' . $fileName;
        }
    }

   // 🎙️ Message vocal
if ($isVoice && !empty($_POST['audioData'])) {
    $audioBase64 = $_POST['audioData'];
    $audioData = explode(',', $audioBase64)[1];
    $audioFileName = 'audio_' . time() . '.webm';

    // 📁 Sauvegarde physique
    $uploadDir = __DIR__ . '/../../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $audioPath = $uploadDir . $audioFileName;
    file_put_contents($audioPath, base64_decode($audioData));

    // 🌐 Chemin HTML utilisable dans <audio>
    $filePath = '/chatbox-project-Message/uploads/' . $audioFileName;

    // facultatif : texte associé
    $content = '';
}


    header("Location: contact.php?user_id=$receiverId");
    exit;
}
?>