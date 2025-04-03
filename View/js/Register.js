$("form").on("submit", function (e) {
  e.preventDefault();

  let data = $("form").serialize(); // En cas de plusieurs form
  data = data + "&load=true"; // bouton submit = load appelé uniquement (syntaxt manuel obligatoire)

  $.ajax({
    type: "POST", //Definie la méthode à utiliser (get ou post)
    url: "../../Controller/UserController.php", //On envoie la data vers le fichier ciblé
    dataType: "json", // json = le fichier qu'on va renvoyer
    data: data,
    success: function (res) {
      $(".error_php").html(""); // Clear previous errors
      $(".error_email").html("");
      $(".error_username").html("");
      $(".error_password").html("");
      if (res.error == true) {
        switch (res.type) {
          case "empty":
            $(".empty_error").html(
              "<p class='error-txt'> Tous les champs sont obligatoires ! </p>"
            );
            break;
          case "email":
            $(".error_email").html(
              "<p class='error-txt'> E-mail déjà utilisé ! </p>"
            );
            break;
          case "username":
            $(".error_username").html(
              "<p class='error-txt'> Pseudo déjà pris ! </p>"
            );
            break;
          case "password":
            $(".error_password").html(
              "<p class='error-txt'> Les mots de passe ne correspondent pas ! </p>"
            );
            break;
          case "database":
            window.location.href = "./404";
            break;
        }
      } else {
        window.location.href = "../View/Login.php";
      }
    },
  });
});
// async: true,
// .done(function (res) {});
//.fail(function (data) {}); //code à employer si la requête est un échec
