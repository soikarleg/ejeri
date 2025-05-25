function HighLight(field, error) {
  //COULEUR
  if (error) field.style.borderBottom = "2px solid #dc3545";
  else field.style.borderBottom = "2px solid #28a745";
}

function Email(field) {
  //EMAIL
  var regex = /^((?!\.)[\w_.-]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$/;
  if (!regex.test(field.value)) {
    HighLight(field, true);
    return false;
  } else {
    HighLight(field, false);
    return true;
  }
}

function Username(field) {
  //USERNAME
  var regex = /[\x00-\x1f\x7f\/:\\\\]/;
  if (!regex.test(field)) {
    console.log("false" + field);
    return false;
  } else {
    console.log("true" + field);
    return true;
  }
}

function cleanString(input) {
  // Supprimer les caractères spéciaux et les accents
  var cleaned = input
    .replace(/[^\w\s]|_/g, "")
    .replace(/\s+/g, " ")
    .replace(/[àáâãäå]/g, "a")
    .replace(/[èéêë]/g, "e")
    .replace(/[ìíîï]/g, "i")
    .replace(/[òóôõö]/g, "o")
    .replace(/[ùúûü]/g, "u")
    .replace(/[ýÿ]/g, "y")
    .replace(/[ñ]/g, "n")
    .replace(/[ç]/g, "c")
    .toLowerCase();
  return cleaned;
}

$(document).ready(function () {
  $("#username").on("input", function () {
    var username = $(this).val();

    if (username.length > 3) {
      $.ajax({
        url: "https://app.enooki.com/src/menus/validation_username.php", // URL de votre script PHP pour la validation
        method: "POST",
        data: {
          username: username,
        },
        success: function (response) {
          if (response === "true") {
            $("#infos").html("Cet indentifiant existe déjà");
            $("#infos").removeClass("text-success");
            $("#infos").removeClass("text-primary");
            $("#infos").addClass("mt-signup text-danger absplus");
            $("#username").css("border-bottom", "2px solid #dc3545");
          } else {
            var propre = cleanString(username);
            $("#username").val(propre);
            $("#infos").html("Identifiant disponible");
            $("#infos").removeClass("text-danger");
            $("#infos").removeClass("text-primary");
            $("#infos").addClass("mt-signup text-success absplus");
            $("#username").css("border-bottom", "2px solid #28a745");
          }
        },
      });
    } else {
      $("#infos").html("Identifiant trop court");
      $("#infos").removeClass("text-success");
      $("#infos").removeClass("text-danger");
      $("#infos").addClass("mt-signup text-primary absplus");
      $(this).css("border-bottom", "0px solid #dc3545");
    }
  });

  $("#toggleBtn").on("click", function () {
    var passwordInput = $("#password");
    var currentType = passwordInput.attr("type");
    if (currentType === "password") {
      passwordInput.attr("type", "text");
      $("#toggleBtn").html(
        "<i class='bx bx-hide icon-bar bx-flxxx mt-2 ml-2 pointer absplus text-warning'>"
      );
    } else {
      passwordInput.attr("type", "password");
      $("#toggleBtn").html(
        "<i class='bx bx-show icon-bar bx-flxxx mt-2 ml-2 pointer absplus text-primary'>"
      );
    }
  });
  $("#password").on("input", function () {
    var password = $(this).val();
    if (
      password.length >= 12 &&
      /[A-Z]/.test(password) &&
      /[a-z]/.test(password) &&
      /\d/.test(password) &&
      /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/.test(password)
    ) {
      $(this).css("border-bottom", "2px solid #28a745");
    } else {
      $(this).css("border-bottom", "2px solid #dc3545");
    }
  });
});
