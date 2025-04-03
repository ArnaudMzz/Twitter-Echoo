$(document).ready(function () {
  const userId = $("#user-id").val();

  if (!userId) {
    console.error("Aucun ID utilisateur trouvé.");
    return;
  }

  // Charger le thème au chargement de la page
  $.ajax({
    url: "../Controller/GetTheme.php",
    method: "GET",
    data: { userId: userId },
    success: function (data) {
      let response = JSON.parse(data);
      if (response.theme) {
        // Appliquer le thème au body
        $("body").removeClass().addClass(response.theme);
        // Cocher le bouton radio correspondant
        $(`input[name="theme"][value="${response.theme}"]`).prop(
          "checked",
          true
        );
      }
    },
    error: function (xhr, status, error) {
      console.error("Erreur lors du chargement du thème :", error);
    },
  });

  // Gestion du changement de thème
  $('input[name="theme"]').change(function () {
    const theme = $(this).val();
    // Appliquer le thème sélectionné
    $("body").removeClass().addClass(theme);

    $.ajax({
      url: "../Controller/UpdateTheme.php",
      method: "POST",
      data: { userId: userId, theme: theme },
      success: function (response) {
        console.log(response);
      },
      error: function (xhr, status, error) {
        console.error("Erreur lors de la mise à jour du thème :", error);
      },
    });
  });

  // Afficher/Masquer le menu de sélection du thème
  $("#theme-button").click(function () {
    $("#theme-settings").toggleClass("hidden");
  });
});