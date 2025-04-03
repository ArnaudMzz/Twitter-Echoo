<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Navbar</title>
    <link rel="stylesheet" href="./assets/Navbar.css" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link
        href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css"
        rel="stylesheet" />
    <script
        src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        type="text/javascript"></script>
</head>

<body>
    <div class="navbar">
        <div class="navbar-container">
            <div class="navbar-header">
                <button class="navbar-toggle" onclick="toggleMenu()">
                    <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
                </button>
                <a href="/View/Home.php" class="navbar-brand">Echoo</a>
            </div>

            <div class="search-btn">
                <div class="search-bar">
                    <div class="navbar-collapse" id="mobile_menu">
                        <ul class="nav">
                            <li>
                            <form class="form search-form" v>
                                <ul id="searchResults" class="search-dropdown"></ul>
                                    <button>
                                        <svg
                                            width="17"
                                            height="16"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                            role="img"
                                            aria-labelledby="search">
                                            <path
                                                d="M7.667 12.667A5.333 5.333 0 107.667 2a5.333 5.333 0 000 10.667zM14.334 14l-2.9-2.9"
                                                stroke="currentColor"
                                                stroke-width="1.333"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                    <input class="input-srch" id="searchBar" placeholder="Recherchez" required="" type="text" onkeyup="searchQuery()" autocomplete="off" />
                                    <button class="reset" type="reset">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="nav-right">
                    <div class="links-btn">
                        <nav class="links" style="--items: 2">
                            <a href="Profile.php?username=<?= urlencode($_SESSION['user']['username']) ?>">Profil</a>
                            <a href="../Controller/Logout.php">Déconnecter</a>
                            <span class="line"></span>
                        </nav>
                    </div>

                    <div class="settings">
                        <button class="setting-btn" id="theme-button">
                            <span class="bar bar1"></span>
                            <span class="bar bar2"></span>
                            <span class="bar bar1"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="settings-container">
        <input type="hidden" id="user-id" value="1" />
        <div class="limit-settings">
            <div id="theme-settings" class="hidden">
                <div class="profile-btn">
                    <a href="./Settings.php" class="style-profile-btn">Paramètres</a>
                </div>
                <p class="theme-space border-b border-t text-white border-purple-200">Thèmes :</p>
                <div class="radio-input">
                    <label class="label">
                        <input type="radio" id="value-1" name="theme" value="dark" />
                        <p class="text">Sombre</p>
                    </label>
                    <label class="label">
                        <input type="radio" id="value-2" name="theme" value="light" />
                        <p class="text">Clair</p>
                    </label>
                    <label class="label">
                        <input type="radio" id="value-3" name="theme" value="midnight" />
                        <p class="text">Minuit</p>
                    </label>
                    <label class="label">
                        <input type="radio" id="value-4" name="theme" value="sunset" />
                        <p class="text">Crépuscule</p>
                    </label>
                    <label class="label">
                        <input type="radio" id="value-5" name="theme" value="blood" />
                        <p class="text">Sang</p>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="scroll"> </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/Navbar.js"></script>
    <script src="./js/search.js"></script>
    <script>
        function toggleMenu() {
            var menu = document.getElementById("mobile_menu");
            menu.classList.toggle("active");
        }

        function toggleDropdown() {
            var dropdown = event.target.nextElementSibling;
            dropdown.classList.toggle("active");
        }
    </script>
    
</body>

</html>