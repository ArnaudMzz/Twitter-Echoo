document.addEventListener('DOMContentLoaded', function() {
    function searchQuery() {
        let query = document.getElementById('searchBar').value.trim();
        if (query.length < 2) {
            document.getElementById('searchResults').style.display = 'none';
            return;
        }

        fetch('/View/SearchView.php?query=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            let resultsDiv = document.getElementById('searchResults');
            resultsDiv.innerHTML = '';  // Vide les résultats avant de les ajouter
            resultsDiv.style.display = "block";

            if (data.users.length || data.hashtags.length || data.tweets.length) {
                resultsDiv.style.display = 'block';
            } else {
                resultsDiv.style.display = 'none';
            }

            // Affichage des résultats Utilisateurs
            if (data.users.length) {
                resultsDiv.innerHTML += '<strong>Utilisateurs</strong><br>';
                data.users.forEach(user => {
                    resultsDiv.innerHTML += `<div onclick="window.location.href='/View/Profile.php?username=${user.username}'">
                        <img src="${user.avatar_url}" width="30"> ${user.username}
                    </div>`;
                });
            }

            // Affichage des Hashtags
            if (data.hashtags.length) {
                resultsDiv.innerHTML += '<strong>Hashtags</strong><br>';
                data.hashtags.forEach(tag => {
                    resultsDiv.innerHTML += `<div onclick="window.location.href='/View/Hashtag.php?tag=${tag.name}'">
                        #${tag.name}
                    </div>`;
                });
            }

            // Affichage des Tweets
            if (data.tweets.length) {
                resultsDiv.innerHTML += '<strong>Tweets</strong><br>';
                data.tweets.forEach(tweet => {
                    resultsDiv.innerHTML += `<div onclick="window.location.href='/View/Home.php?id=${tweet.id_tweet}'">
                        <img src="${tweet.avatar_url}" width="30"> ${tweet.username} : ${tweet.content}
                    </div>`;
                });
            }

            // Vérification de l'existence de la navbar avant de positionner les résultats
            let navbar = document.querySelector('.navbar');
            if (navbar) {
                let navbarHeight = navbar.offsetHeight;
                resultsDiv.style.top = `${navbarHeight}px`;  // Ajuste en fonction de la hauteur de la navbar
            } else {
                console.error("Navbar non trouvée !");
            }
        })
        .catch(error => console.error('Erreur:', error));
    }

    // Event listener ou appel de la fonction de recherche
    document.getElementById('searchBar').addEventListener('input', searchQuery);
});

