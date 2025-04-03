$(document).ready(function () {
    $(".response").on("click", function (e) {
      e.preventDefault();
      let tweetId = $(this).data("tweet-id");
      $.ajax({
        url: "../Controller/ResponseController.php",
        type: "GET",
        data: {
          id_tweet: tweetId,
        },
        success: function (response) {
          $("#response-content").html(response);
          $("#response-popup").show();
        },
        error: function () {
          alert("Une erreur s'est produite lors du chargement des réponses.");
        },
      });
    });
  
    $(".close-btn").on("click", function () {
      $("#response-popup").hide();
    });
  
    // Gestion de l'envoi du formulaire de réponse
    $(document).on("submit", "#response-form", function (e) {
      e.preventDefault();
      let formData = $(this).serialize();
      $.ajax({
        url: "../Controller/SubmitReponse.php",
        type: "POST",
        data: formData,
        success: function (response) {
          let tweetId = $('input[name="id_tweet"]').val();
          $("#responses-" + tweetId).append(response);
          $("#response-popup").hide();
        },
        error: function () {
          alert("Une erreur s'est produite lors de l'envoi de la réponse.");
        },
      });
    });
  });