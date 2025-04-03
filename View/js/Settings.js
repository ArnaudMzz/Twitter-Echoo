// Fonction pour afficher la carte de confirmation
function showDeactivateCard() {
    const card = document.getElementById("deactivate-card");
    card.style.display = "block";
  }
  
  function hideDeactivateCard() {
    const card = document.getElementById("deactivate-card");
    card.style.display = "none";
  }
  
  // Attacher l'événement au bouton de suppression du compte
  document.querySelector(".delete-btn").addEventListener("click", function () {
    showDeactivateCard();
  });
  
  // Attacher l'événement au bouton de confirmation de suppression
  document
    .querySelector(".confirm-delete")
    .addEventListener("click", function () {
      $.ajax({
        type: "POST",
        url: "../Controller/DeleteAccount.php",
        success: function (response) {
          window.location.href = "../View/Register.php?account_deleted=1";
        },
        error: function () {
          alert("Une erreur s'est produite lors de la suppression du compte.");
        },
      });
    });