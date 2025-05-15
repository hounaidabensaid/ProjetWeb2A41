<!-- footer.php -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function checkNewReservations() {
        $.ajax({
            url: 'check_notifications.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.new) {
                    showNotification("📢 Nouvelle réservation ajoutée !");
                }
            },
            error: function() {
                console.log("Erreur lors de la vérification des notifications.");
            }
        });
    }

    function showNotification(message) {
        const notif = document.createElement('div');
        notif.className = 'alert alert-info';
        notif.style.position = 'fixed';
        notif.style.top = '20px';
        notif.style.right = '20px';
        notif.style.zIndex = '9999';
        notif.style.display = 'flex';
        notif.style.alignItems = 'center';
        notif.style.justifyContent = 'space-between';
        notif.style.minWidth = '250px';
        notif.style.boxShadow = '0 4px 8px rgba(0,0,0,0.2)';
        notif.style.padding = '10px';
        notif.style.borderRadius = '5px';

        notif.innerHTML = `
            <span>${message}</span>
            <a href="index_reservation.php" class="btn btn-sm btn-primary ms-3">Voir</a>
        `;

        document.body.appendChild(notif);

        setTimeout(() => {
            notif.remove();
        }, 7000);
    }

    setInterval(checkNewReservations, 10000);
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
