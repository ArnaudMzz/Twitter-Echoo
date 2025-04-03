$(document).ready(function() {
    // Gestion de l'envoi du formulaire
    $('#messageForm').on('submit', function(e) {
        e.preventDefault(); // Empêche le rechargement de la page

        var formData = $(this).serialize(); // Récupère les données du formulaire

        $.ajax({
            url: '../Controller/MessageController.php', // L'URL du contrôleur
            type: 'POST',
            data: formData,
            success: function(response) {
                var data = JSON.parse(response);

                if (data.status === 'success') {
                    // Ajoutez le nouveau message à la liste des messages
                    var newMessage = '<div class="message sent">' +
                        '<div class="message-header">' +
                        '<span class="username">' +
                        '<a href="Profile.php?username=' + data.message.sender_username + '">' +
                        data.message.sender_username +
                        '</a>' +
                        '</span>' +
                        '</div>' +
                        '<p>' + data.message.content + '</p>' +
                        '<small>' + data.message.created_at + '</small>' +
                        '</div>';
                    $('.messages').append(newMessage);

                    // Effacez le champ de texte
                    $('#messageContent').val('');

                    // Faites défiler vers le bas pour voir le nouveau message
                    $('.messages').scrollTop($('.messages')[0].scrollHeight);
                } else {
                    alert('Erreur : ' + data.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Une erreur s\'est produite lors de l\'envoi du message.');
            }
        });
    });
});