<?php
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/../../controller/userController.php");
require_once(__DIR__ . "/../../controller/messageController.php");
session_start();

// Vérification de session avec les variables séparées
if (!isset($_SESSION['user_id']) || !isset($_SESSION['nom'])) {
    header("Location: ../../login.php");
    exit;
}

$userController = new userController();
$messageController = new messageController();

// Adapté à ta structure de session
$users = $userController->getOtherUsers($_SESSION['nom']);

$selectedUserId = isset($_GET['user_id']) ? $_GET['user_id'] : null;
$selectedUser = null;
$messages = [];

if ($selectedUserId) {
    $selectedUser = $userController->getUserById($selectedUserId);
    $messages = $messageController->listeMessages($_SESSION['user_id'], $selectedUserId);

    // Marquer les messages reçus comme lus
    $messageController->markMessagesAsRead(
        $selectedUserId, // sender
        $_SESSION['user_id'] // receiver
    );

    // Rafraîchir les messages
    $messages = $messageController->listeMessages($_SESSION['user_id'], $selectedUserId);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messagerie Premium</title>
    <style>
        
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="contactStyle.css">
    <style>
    /* Like button styles */
    .message-time {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.75rem;
        color: #666;
    }

    .like-button {
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        color: #666;
        transition: color 0.2s;
        margin-left: 8px;
    }

    .like-button.liked i {
        color: #ff4757 !important;
    }

    .like-button i {
        font-size: 14px;
        transition: all 0.3s ease;
    }

    @keyframes likeBounce {
        0% { transform: scale(1); }
        30% { transform: scale(1.3); }
        50% { transform: scale(0.9); }
        70% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    .like-button.animate i {
        animation: likeBounce 0.6s ease-out;
    }
    .read-indicator {
    color: #666;
    font-size: 0.75rem;
    margin-left: 8px;
}

.read-indicator {
    color: #00b894;


    .conversation-search {
    padding: 15px;
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
}

.search-input {
    width: 100%;
    padding: 12px 20px;
    border: 1px solid #ddd;
    border-radius: 25px;
    font-size: 14px;
}

.search-input:focus {
    outline: none;
    border-color: #2f80ed;
    box-shadow: 0 0 0 2px rgba(47, 128, 237, 0.1);
}

.message-highlight {
    background-color: #ffeb3b;
    padding: 2px 4px;
    border-radius: 3px;
}
/* Style personnalisé pour les messages vocaux */
.voice-message {
    position: relative;
    padding: 15px;
    background: #f1f3f4;
    border-radius: 15px;
    width: 250px;
}

.voice-message audio {
    width: 100%;
    height: 40px;
}

/* Style des contrôles audio */
audio::-webkit-media-controls-panel {
    background: #ffffff;
    border-radius: 10px;
}

audio::-webkit-media-controls-play-button {
    background-color: #2f80ed;
    border-radius: 50%;
}

audio::-webkit-media-controls-timeline {
    background-color: #e0e0e0;
    border-radius: 5px;
    margin: 0 10px;
}
}
</style>
</head>
<body>

<div class="users-sidebar">
    <div class="users-header">
        <div class="users-title">Messagerie</div>
        <button class="theme-toggle" title="Toggle dark mode">🌓</button>
    </div>
    <div class="search-container">
        <input type="text" class="search-input" id="searchInput" placeholder="Rechercher un contact...">
    </div>
    <div class="user-list">
        <?php foreach ($users as $user): ?>
            <a href="?user_id=<?= htmlspecialchars($user['id']) ?>" class="user <?= ($selectedUserId == $user['id']) ? 'active' : '' ?>">
                <div class="user-avatar">
                    <img src="<?= !empty($user['photo']) ? $user['photo'] : 'https://i.pravatar.cc/150?img='.rand(1, 70) ?>" alt="<?= $user['nom'] ?>">
                    <?php if (rand(0, 3) === 0): ?>
                        <div class="user-badge">3</div>
                    <?php endif; ?>
                </div>
                <div class="user-info">
                    <div class="user-name"><?= htmlspecialchars($user['nom']) ?></div>
                    <div class="user-status">En ligne</div>
                </div>
                <div class="user-time">12:30</div>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="messages-container">
    
    <?php if ($selectedUser): ?>
        <div class="messages-header">
            <div class="messages-header-avatar">
                <img src="<?= !empty($selectedUser['photo']) ? $selectedUser['photo'] : 'https://i.pravatar.cc/150?img='.rand(1, 70) ?>" alt="<?= $selectedUser['nom'] ?>">
            </div>
            <div class="messages-header-info">
                <div class="messages-header-name"><?= htmlspecialchars($selectedUser['nom']) ?></div>
                <div class="messages-header-status">En ligne</div>
            </div>
            <div class="messages-header-actions">
                <button class="messages-header-action" title="Appel vocal">📞</button>
                <button class="messages-header-action" title="Appel vidéo">🎥</button>
                <button class="messages-header-action" title="Plus d'options">⋮</button>
            </div>
        </div>

        <div class="messages-content" id="messagesContent">
        <?php if (!empty($message['filePath']) && (empty($message['is_voice']) || $message['is_voice'] == 0)): ?>
    <?php
        $extension = strtolower(pathinfo($message['filePath'], PATHINFO_EXTENSION));
        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
    ?>

    <?php if ($isImage): ?>
        <!-- 🖼️ Image -->
        <div class="message-image" style="margin-top: 6px;">
            <img src="<?= htmlspecialchars($message['filePath']) ?>" alt="Image"
                 style="max-width: 250px; max-height: 250px; width: auto; height: auto; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
        </div>
    <?php else: ?>
        <!-- 📎 Autre fichier -->
        <div class="message-attachment" style="margin-top: 6px;">
            <a href="<?= htmlspecialchars($message['filePath']) ?>" target="_blank"
               style="color: #2f80ed; text-decoration: underline;">
                📎 Pièce jointe
            </a>
        </div>
    <?php endif; ?>
<?php endif; ?>

    <!-- Barre de recherche ajoutée ici -->
    <div class="conversation-search">
        <input type="text" id="searchMessage" 
               placeholder="🔍 Rechercher dans cette conversation..." 
               class="search-input">
    </div>

            <?php foreach ($messages as $message): 
                $isSent = $message['idsender'] == $_SESSION['user']['id'];
                
            ?>
                <div class="message <?= $isSent ? 'sent' : 'received' ?>" data-message-id="<?= $message['message_id'] ?>">
                <div class="message-text">
  <?php if (!empty($message['is_voice']) && $message['is_voice'] == 1 && !empty($message['filePath'])): ?>
      <!-- 🎤 Message vocal avec lecteur -->
      <div style="background: #f1f3f4; padding: 10px 12px; border-radius: 12px; display: flex; flex-direction: column; gap: 6px;">
          <div style="display: flex; align-items: center; gap: 8px;">
              <i class="fas fa-microphone" style="color: #2f80ed; font-size: 18px;"></i>
              <span style="font-weight: 500; color: #333;">Message vocal</span>
          </div>
          <audio controls style="width: 100%; border-radius: 6px;">
              <source src="<?= htmlspecialchars($message['filePath']) ?>" type="audio/webm">
              Votre navigateur ne supporte pas la lecture audio.
          </audio>
      </div>
  
  <?php else: ?>
      <!-- 📝 Message texte -->
      <?= htmlspecialchars($message['content']) ?>

      <?php if (!empty($message['filePath']) && (empty($message['is_voice']) || $message['is_voice'] == 0)): ?>
          <!-- 📎 Fichier joint normal (pas un vocal) -->
          <div class="message-attachment" style="margin-top: 6px;">
              <a href="<?= htmlspecialchars($message['filePath']) ?>" target="_blank"
                 style="color: #2f80ed; text-decoration: underline;">
                  📎 Pièce jointe
              </a>
          </div>
      <?php endif; ?>
  <?php endif; ?>
</div>

                    <div class="message-time">
    <?= date('H:i', strtotime($message['message_created_at'])) ?>
    <div class="message-status">
    <?php if ($isSent): ?>
        <?php if ($message['isread']): ?>
            <span class="read-indicator">✓✓</span>
        <?php else: ?>
            <span class="read-indicator">✓</span>
        <?php endif; ?>
    <?php endif; ?>
</div>
    <form action="handleReaction.php" method="POST" class="like-form">
    <input type="hidden" name="messageId" value="<?= $message['message_id'] ?>">
    <input type="hidden" name="receiverId" value="<?= $selectedUserId ?>">
    
    <?php if ($message['reaction'] == 'like'): ?>
        <input type="hidden" name="reaction" value="dislike">
        <button type="submit" class="like-button">
            <i class="fas fa-heart"></i>
        </button>
    <?php else: ?>
        <input type="hidden" name="reaction" value="like">
        <button type="submit" class="like-button">
            <i class="far fa-heart"></i>
        </button>
    <?php endif; ?>
</form>
   
</div>
                    <?php if ($isSent): ?>
                        <div class="message-actions">
                            <button class="message-action update-btn" title="Modifier">✏️</button>
                            <a class="message-action delete-btn" href="deleteMessage.php?id=<?= $message['message_id'] ?>" title="Supprimer">🗑️</a>
                        </div>
                        <div class="edit-form">
                            <textarea class="edit-textarea"><?= htmlspecialchars($message['content']) ?></textarea>
                            <div class="edit-buttons">
                                <button class="edit-button edit-cancel">Annuler</button>
                                <button class="edit-button edit-save">Enregistrer</button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="message-input-container">
        <form action="addmessage.php" method="POST" enctype="multipart/form-data" class="message-input-form"
    style="display: flex; align-items: center; gap: 10px; background: #fff; padding: 10px 20px; border-top: 1px solid #eee; border-radius: 25px;">

    <input type="hidden" name="idreciever" value="<?= $selectedUser['id'] ?>">
    <input type="hidden" name="senderId" value="<?= $_SESSION['user_id'] ?>">

    <!-- Zone de texte -->
    <textarea name="messageInput" placeholder="Écrivez votre message ici..."
        style="flex: 1; height: 40px; padding: 10px 15px; border: 1px solid #aaa; border-radius: 20px; resize: none; font-size: 14px;"></textarea>

    <!-- 📎 Pièce jointe -->
    <label for="fileInput" style="cursor: pointer;">
        <i class="fas fa-paperclip" style="font-size: 18px; color: #666;"></i>
    </label>
    <input type="file" id="fileInput" name="attachment" style="display: none;" accept=".jpg,.jpeg,.png,.pdf,.mp3,.mp4" />

    <!-- 🎙️ Enregistrement vocal -->
    <button type="button" id="startRecording"
        style="background: #3498db; border: none; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
        <i class="fas fa-microphone" style="color: white;"></i>
    </button>

    <!-- Aperçu vocal -->
    <audio id="audioPreview" controls style="display: none; max-width: 150px;"></audio>

    <!-- Champs cachés -->
    <input type="hidden" id="audioBlob" name="audioData">
    <input type="hidden" name="is_voice" value="0" id="isVoiceField">

    <!-- 📤 Envoyer -->
    <button type="submit"
        style="background: #e74c3c; border: none; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
        <i class="fas fa-paper-plane" style="color: white;"></i>
    </button>
</form>

</div>

    <?php else: ?>
        <div class="empty-state">
            <svg class="empty-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 15C21 15.5304 20.7893 16.0391 20.4142 16.4142C20.0391 16.7893 19.5304 17 19 17H7L3 21V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H19C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V15Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8 10H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8 14H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h3 class="empty-title">Aucune conversation sélectionnée</h3>
            <p class="empty-description">Sélectionnez un contact dans la liste pour commencer à discuter ou créez une nouvelle conversation</p>
        </div>
    <?php endif; ?>
</div>

<script>
    // Dark mode toggle
    const themeToggle = document.querySelector('.theme-toggle');
    themeToggle.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
    });

    // Check for saved theme preference
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', () => {
        const searchTerm = searchInput.value.toLowerCase();
        document.querySelectorAll('.user').forEach(user => {
            const username = user.querySelector('.user-name').textContent.toLowerCase();
            user.style.display = username.includes(searchTerm) ? 'flex' : 'none';
        });
    });

    // Message actions
    document.querySelectorAll('.update-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const messageDiv = this.closest('.message');
            messageDiv.querySelector('.edit-form').classList.add('active');
            messageDiv.querySelector('.edit-textarea').focus();
        });
    });

    document.querySelectorAll('.edit-cancel').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            this.closest('.edit-form').classList.remove('active');
        });
    });

    document.querySelectorAll('.edit-save').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const messageDiv = this.closest('.message');
            const messageId = messageDiv.dataset.messageId;
            const newContent = messageDiv.querySelector('.edit-textarea').value;

            fetch('EditMessage.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    messageId: messageId,
                    content: newContent
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.querySelector('.message-text').textContent = newContent;
                    messageDiv.querySelector('.edit-form').classList.remove('active');
                } else {
                    alert('Erreur lors de la modification du message');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });

    // Auto-scroll to bottom of messages
    const messagesContent = document.getElementById('messagesContent');
    if (messagesContent) {
        messagesContent.scrollTop = messagesContent.scrollHeight;
    }

    // Textarea auto-resize
    const messageInput = document.querySelector('.message-input');
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }

    // Mobile menu toggle (for responsive)
    const menuToggle = document.createElement('button');
    menuToggle.innerHTML = '☰';
    menuToggle.style.position = 'fixed';
    menuToggle.style.bottom = '20px';
    menuToggle.style.right = '20px';
    menuToggle.style.zIndex = '30';
    menuToggle.style.width = '50px';
    menuToggle.style.height = '50px';
    menuToggle.style.borderRadius = '50%';
    menuToggle.style.background = 'var(--primary)';
    menuToggle.style.color = 'white';
    menuToggle.style.border = 'none';
    menuToggle.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.2)';
    menuToggle.style.fontSize = '1.5rem';
    menuToggle.style.display = 'none';
    menuToggle.style.justifyContent = 'center';
    menuToggle.style.alignItems = 'center';
    menuToggle.style.cursor = 'pointer';
    document.body.appendChild(menuToggle);

    menuToggle.addEventListener('click', () => {
        document.querySelector('.users-sidebar').classList.toggle('active');
    });

    // Show/hide menu toggle based on screen size
    function checkScreenSize() {
        if (window.innerWidth <= 768) {
            menuToggle.style.display = 'flex';
        } else {
            menuToggle.style.display = 'none';
            document.querySelector('.users-sidebar').classList.remove('active');
        }
    }

    window.addEventListener('resize', checkScreenSize);
    checkScreenSize();
</script>
<script>
    // Like button functionality
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.querySelector('i');
            this.classList.toggle('liked');
            icon.classList.toggle('far');
            icon.classList.toggle('fas');
            
            // Add animation class
            this.classList.add('animate');
            setTimeout(() => {
                this.classList.remove('animate');
            }, 600);
        });
    });
</script>
<script>
document.getElementById('searchMessage').addEventListener('input', function() {
    const searchText = this.value.toLowerCase().trim();
    const messages = document.querySelectorAll('.message');
    let firstMatch = null;

    messages.forEach(message => {
        const textDiv = message.querySelector('.message-text');
        const originalText = textDiv.dataset.original || textDiv.textContent;
        
        // Sauvegarder le contenu original
        if (!textDiv.dataset.original) {
            textDiv.dataset.original = originalText;
        }

        if (searchText === "") {
            message.style.display = 'flex';
            textDiv.innerHTML = originalText;
        } else {
            const content = originalText.toLowerCase();
            if (content.includes(searchText)) {
                const highlighted = originalText.replace(
                    new RegExp(searchText, 'gi'),
                    match => `<span class="message-highlight">${match}</span>`
                );
                textDiv.innerHTML = highlighted;
                message.style.display = 'flex';
                if (!firstMatch) firstMatch = message;
            } else {
                message.style.display = 'none';
            }
        }
    });

    if (firstMatch) {
        firstMatch.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }
});
</script>
<script>
let mediaRecorder;
let audioChunks = [];

const micButton = document.getElementById("startRecording");
const audioPreview = document.getElementById("audioPreview");
const audioDataInput = document.getElementById("audioBlob");
const isVoiceField = document.getElementById("isVoiceField");

micButton.addEventListener("click", async () => {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        alert("Votre navigateur ne supporte pas l'enregistrement audio.");
        return;
    }

    // Désactiver le bouton pendant l'enregistrement
    micButton.disabled = true;
    micButton.style.backgroundColor = "#95a5a6"; // gris

    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
    mediaRecorder = new MediaRecorder(stream);
    audioChunks = [];

    mediaRecorder.start();
    isVoiceField.value = "1"; // on marque que c'est un vocal
    console.log("⏺️ Enregistrement...");

    mediaRecorder.addEventListener("dataavailable", event => {
        audioChunks.push(event.data);
    });

    mediaRecorder.addEventListener("stop", () => {
        const audioBlob = new Blob(audioChunks, { type: "audio/webm" });
        const audioUrl = URL.createObjectURL(audioBlob);
        audioPreview.src = audioUrl;
        audioPreview.style.display = "block";

        // Convertir le vocal en base64 pour l'envoyer en POST
        const reader = new FileReader();
        reader.readAsDataURL(audioBlob);
        reader.onloadend = () => {
            audioDataInput.value = reader.result;
            console.log("✅ Audio prêt à être envoyé.");
        };

        micButton.disabled = false;
        micButton.style.backgroundColor = "#3498db"; // bleu
    });

    // Arrêter l'enregistrement après 5 secondes
    setTimeout(() => {
        mediaRecorder.stop();
        stream.getTracks().forEach(track => track.stop());
    }, 5000);
});
</script>


</body>
</html>