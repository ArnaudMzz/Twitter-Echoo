<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inscription</title>
    <link rel="stylesheet" href="./assets/Register.css" />
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
        <header>Inscription</header>
        <form method="POST" action="../Controller/UserController.php">
            <div class="wrapper">
                <div class="one_cont">
                    <div class="field">
                        <div class="input-area">
                            <input type="text" placeholder="Pseudo" name="username" />
                            <i class="icon fas fa-user"></i>
                        </div>
                        <div class="error_username error_php"></div>
                    </div>

                    <div class="field">
                        <div class="input-area">
                            <input type="email" placeholder="Email" name="email" />
                            <i class="icon fas fa-envelope"></i>
                        </div>
                        <div class="error_email error_php"></div>
                    </div>

                    <div class="field">
                        <div class="input-area">
                            <input type="text" placeholder="Téléphone" name="telephone" />
                            <i class="icon fas fa-phone"></i>
                        </div>
                    </div>

                    <div class="field">
                        <div class="input-area">
                            <input
                                type="password"
                                placeholder="Mot de passe"
                                name="password" />
                            <i class="icon fas fa-lock"></i>
                        </div>
                    </div>

                    <div class="field">
                        <div class="input-area">
                            <input
                                type="password"
                                placeholder="Confirmez mot de passe"
                                name="password_conf" />
                            <i class="icon fas fa-lock"></i>
                        </div>
                        <div class="error_password error_php"></div>
                    </div>

                    <div class="field">
                        <div class="input-area">
                            <input type="date" name="birthday" />
                            <i class="icon fas fa-birthday-cake"></i>
                        </div>
                    </div>
                    <div class="empty_error error_php"></div>
                </div>
            </div>
            <div class="login_page">
                <div class="login_text">
                    <p>Déjà un compte ?</p>

                    <a href="Login.php">Connectez vous</a>
                </div>
                <input
                    type="submit"
                    name="load"
                    onclick="Set_Form()"
                    value="S'inscrire" />
            </div>
        </form>
    </div>
    <script src="./js/Register.js"></script>
</body>

</html>