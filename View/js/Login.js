$("form").on("submit", function (e) {
  e.preventDefault();

  let data = $("form").serialize(); // En cas de plusieurs form
  data = data + "&login=true"; // bouton submit = load appelé uniquement (syntaxt manuel obligatoire)

  $.ajax({
    type: "POST", //Definie la méthode à utiliser (get ou post)
    url: "../../Model/Connexion.php", //On envoie la data vers le fichier ciblé
    dataType: "json", // json = le fichier qu'on va renvoyer
    data: data,
    success: function (res) {
      $(".error_php").html(""); // Clear previous errors
      $(".error_verify").html("");
      $(".empty_error").html("");
      if (res.error == true) {
        switch (res.type) {
          case "empty":
            $(".empty_error").html(
              "<p class='error-txt'> Tous les champs sont obligatoires ! </p>"
            );
            break;
          case "verify":
            $(".error_verify").html(
              "<p class='error-txt'> E-mail ou mot de passe incorrect ! </p>"
            );
            break;
          case "database":
            window.location.href = "./404";
            break;
        }
      } else {
        window.location.href = "./Home.php";
      }
    },
  });
});
// async: true,
// .done(function (res) {});
//.fail(function (data) {}); //code à employer si la requête est un échec
