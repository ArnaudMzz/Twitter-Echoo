<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Connexion</title>
    <link rel="stylesheet" href="./assets/Login.css" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <script
        src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        type="text/javascript"></script>
</head>

<body>
    <div class="background">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="limits">
        <div class="wrapper">
            <header>Connexion</header>
            <form action="../Model/Connexion.php" method="POST">
                <div class="field email">
                    <div class="input-area">
                        <input type="email" placeholder="Email" name="email" />
                        <i class="icon fas fa-envelope"></i>
                        <i class="error error-icon fas fa-exclamation-circle"></i>
                    </div>
                </div>
                <div class="field password">
                    <div class="input-area">
                        <input
                            type="password"
                            placeholder="Mot de passe"
                            name="password" />
                        <i class="icon fas fa-lock"></i>
                        <i class="error error-icon fas fa-exclamation-circle"></i>
                    </div>
                    <div class="empty_error error-txt"></div>
                    <div class="error_verify error-txt"></div>
                </div>

                <div class="pass-txt"><a href="#">Mot de passe oublié ?</a></div>
                <input type="submit" name="login" value="Connexion" />
            </form>

            <div class="sign-txt">
                Pas de compte ? <a href="./Register.php">Inscrivez-vous</a>
            </div>
        </div>
    </div>
    <script src="./js/Login.js"></script>
</body>

</html>