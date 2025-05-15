<?php
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/../../controller/userController.php");
require_once(__DIR__ . "/../../controller/messageController.php");
session_start();

$userController = new userController();
$messageController = new messageController();
$users = $userController->getOtherUsers($_SESSION['user']['username']);

$selectedUserId = isset($_GET['user_id']) ? $_GET['user_id'] : null;
$selectedUser = null;
$messages = [];

if ($selectedUserId) {
    $selectedUser = $userController->getUserById($selectedUserId);
    $messages = $messageController->listeMessages($_SESSION['user']['id'], $selectedUserId);
    
    // Mark received messages as read
    $messageController->markMessagesAsRead(
        $selectedUserId, // sender is the selected user
        $_SESSION['user']['id'] // receiver is current user
    );
    
    // Refresh messages after marking as read
    $messages = $messageController->listeMessages($_SESSION['user']['id'], $selectedUserId);
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
    <link rel="stylesheet" href="chatboxstyle.css">
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
}
</style>
</head>
<body> <div class="sidebar">
        <div class="sidebar-header">
            <h3 class="text-center">Admin Panel</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="http://localhost/ttttttesttttt/view/BackOffice/dashboard_user.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="http://localhost/ttttttesttttt/view/BackOffice/index_voiture.php"><i class="fas fa-car"></i> Gestion Voitures</a></li>
            <li><a href="http://localhost/ttttttesttttt/view/BackOffice/index_user.php"  class="active"><i class="fas fa-users"></i> Utilisateurs</a></li>
            <li><a href="http://localhost/ttttttesttttt/view/BackOffice/index_reservation.php" class="active"><i class="fas fa-calendar-check"></i> Réservations</a></li>
			            <li><a href="http://localhost/ttttttesttttt/view/takearide-back-office/views/event.php" class="active"><i class="fas fa-calendar-check"></i> Evenement</a></li>
			            <li><a href="http://localhost/ttttttesttttt/view/takearide-back-office/views/reservation_event.php" class="active"><i class="fas fa-calendar-check"></i> Réservations Evenement</a></li>

			<li><a href="http://localhost/ttttttesttttt/view/BackOffice/dashboard.php"><i class="fas fa-calendar-check"></i> Réclamations</a></li>
			<li><a href="http://localhost/ttttttesttttt/view/BackOffice/view_reponse.php"><i class="fas fa-calendar-check"></i> Réponses</a></li>

			<li><a href="http://localhost/ttttttesttttt/view/frontoffice/chatbox/view/backoffice/chatbox.php"><i class="fas fa-calendar-check"></i> Contact</a></li>

            <li><a href="http://localhost/ttttttesttttt/view/frontoffice/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </div>


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
                    <img src="<?= !empty($user['photo']) ? $user['photo'] : 'https://i.pravatar.cc/150?img='.rand(1, 70) ?>" alt="<?= $user['username'] ?>">
                    <?php if (rand(0, 3) === 0): ?>
                        <div class="user-badge">3</div>
                    <?php endif; ?>
                </div>
                <div class="user-info">
                    <div class="user-name"><?= htmlspecialchars($user['username']) ?></div>
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
                <img src="<?= !empty($selectedUser['photo']) ? $selectedUser['photo'] : 'https://i.pravatar.cc/150?img='.rand(1, 70) ?>" alt="<?= $selectedUser['username'] ?>">
            </div>
            <div class="messages-header-info">
                <div class="messages-header-name"><?= htmlspecialchars($selectedUser['username']) ?></div>
                <div class="messages-header-status">En ligne</div>
            </div>
            <div class="messages-header-actions">
                <button class="messages-header-action" title="Appel vocal">📞</button>
                <button class="messages-header-action" title="Appel vidéo">🎥</button>
                <button class="messages-header-action" title="Plus d'options">⋮</button>
            </div>
        </div>

        <div class="messages-content" id="messagesContent">
            <?php foreach ($messages as $message): 
                $isSent = $message['idsender'] == $_SESSION['user']['id'];
            ?>
                <div class="message <?= $isSent ? 'sent' : 'received' ?>" data-message-id="<?= $message['message_id'] ?>">
                    <div class="message-text"><?= htmlspecialchars($message['content']) ?></div>
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
            <form action="addmessage.php" method="POST" class="message-input-form">
                <input type="hidden" name="idreciever" value="<?= $selectedUser['id'] ?>">
                <input type="hidden" name="senderId" value="<?= $_SESSION['user']['id'] ?>">
                <textarea name="messageInput" class="message-input" placeholder="Écrivez votre message ici..." required></textarea>
                <button class="send-button" type="submit">
                    <svg class="send-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 2L11 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M22 2L15 22L11 13L2 9L22 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
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
    // Update mobile menu toggle
    const sidebarToggle = document.querySelector('.menu-toggle');
    sidebarToggle.addEventListener('click', () => {
        document.querySelector('.sidebar').classList.toggle('active');
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 768) {
            if (!e.target.closest('.sidebar') && !e.target.closest('.menu-toggle')) {
                document.querySelector('.sidebar').classList.remove('active');
            }
        }
    });
</script>
</body>
</html>