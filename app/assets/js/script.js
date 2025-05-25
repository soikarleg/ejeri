// *******************
// Fonctions Ajax
// FLGA 11/2021 rev. 08/2023
// https://app.enooki.com/assets/js/ajax.js
// *******************
function verifClient() {
  var secteur = $("#idcompte_value").val();
  $.ajax({
    type: "POST",
    url: "https://app.enooki.com/src/menus/verification_client_base.php",
    data: {
      secteur: secteur,
    },
    success: function (response) {
      var btncli = $("#client_menu");
      if (response !== "existe") {
        $("#client_menu").addClass("rappel"); //sous_menu_contact
        $("#sous_menu_contact").addClass("rappel");
        //console.log('Je met le rappel ' + btncli.attr('class'))
      } else {
        $("#client_menu").removeClass("rappel");
        $("#sous_menu_contact").removeClass("rappel");
        //console.log('Je ne met pas le rappel ' + btncli.attr('class'))
      }
    },
  });
}
function verifInfosCompte() {
  var secteur = $("#idcompte_value").val();
  $.ajax({
    type: "POST",
    url: "https://app.enooki.com/src/menus/verification_infos_base.php",
    data: {
      secteur: secteur,
    },
    success: function (response) {
      var btncli = $("#infos_menu");
      if (response !== "existe") {
        $("#infos_sous_menu").addClass("rappel");
        $("#compte_menu").addClass("rappel");
        //console.log('Je met le rappel ' + btncli.attr('class'))
      } else {
        $("#infos_sous_menu").removeClass("rappel");
        $("#compte_menu").removeClass("rappel");
        //console.log('Je ne met pas le rappel ' + btncli.attr('class'))
      }
    },
  });
}
function verifInfosUsers() {
  var secteur = $("#idcompte_value").val();
  $.ajax({
    type: "POST",
    url: "https://app.enooki.com/src/menus/verification_users_base.php",
    data: {
      secteur: secteur,
    },
    success: function (response) {
      var btncli = $("#infos_menu");
      if (response !== "existe") {
        $("#intervenant_menu").addClass("rappel");
        $("#intervenant_sous_menu").addClass("rappel");
        //console.log('Je met le rappel ' + btncli.attr('class'))
      } else {
        $("#intervenant_menu").removeClass("rappel");
        $("#intervenant_sous_menu").removeClass("rappel");
        //console.log('Je ne met pas le rappel ' + btncli.attr('class'))
      }
    },
  });
}
function AJPold(posget, urle, infopas, affiche, imatt) {
  var status;
  $.ajax({
    type: posget,
    url: urle,
    enctype: "multipart/form-data",
    async: false,
    data: infopas,
    dataType: "html",
    beforeSend: function () {
      $("#" + imatt).show();
      $("#" + affiche).hide();
      //alert('beforeSend');
    },
    success: function (reponse) {
      $("#" + affiche).html(reponse);
      $(reponse).html("#" + affiche);
      status = reponse;
    },
    error: function (resultat, statut, erreur) {
      $(resultat).appendTo("#" + affiche);
      $("#" + imatt).hide();
      $("#" + affiche).show();
      status = resultat;
    },
    complete: function () {
      $("#" + imatt).hide();
      $("#" + affiche).show();
    },
  });
}
function FabrikCookie(cname, cvalue, exminutes) {
  var d = new Date();
  var m = parseInt(exminutes);
  d.setTime(d.getTime() + m * 60 * 1000);
  var expires = "expires=" + d.toString();
  document.cookie =
    cname +
    "=" +
    cvalue +
    ";" +
    expires +
    ";domain=flxxx.fr; path=/; samesite=none;Secure";
}
var dec = sha256("deconnexion");
//FabrikCookie('limit', dec, 1);

// function getSession() {
// 	return new Promise((resolve, reject) => {
// 		$.ajax({
// 			type: "POST",
// 			url: "https://app.enooki.com/api/get_session_value.php",
// 			enctype: 'multipart/form-data',
// 			async: true,
// 			data: 'secteur',
// 			dataType: 'html',
// 			success: function (data) {
// 				//console.log('MyDATA => ', data);
// 				resolve(data); // Résoudre la promesse avec la donnée reçue
// 			},
// 			error: function (xhr, status, error) {
// 				console.error('Error:', error);
// 				reject(error); // Rejeter la promesse en cas d'erreur
// 			},
// 		});
// 	});
// }

function getSession() {
  return new Promise((resolve, reject) => {
    fetch("https://app.enooki.com/api/get_session_value.php", {
      method: "POST",
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(
            "Erreur réseau lors de la récupération de la session."
          );
        }
        return response.json(); // Convertir la réponse en JSON
      })
      .then((data) => {
        if (data.success) {
          resolve(data.sessionId); // Session valide
        } else {
          reject(new Error(data.error || "Session invalide.")); // Session invalide
        }
      })
      .catch((error) => {
        reject(error); // Autres erreurs (réseau, parsing, etc.)
      });
  });
}

function getCookieExpiration(cookieName) {
  const cookies = document.cookie.split(";");
  for (let i = 0; i < cookies.length; i++) {
    const cookie = cookies[i].trim();
    if (cookie.startsWith(cookieName + "=")) {
      const cookieAttributes = cookie.split(";");
      for (let j = 0; j < cookieAttributes.length; j++) {
        const attribute = cookieAttributes[j].trim();
        if (attribute.toLowerCase().startsWith("expires=")) {
          const expiresValue = attribute.substring("expires=".length);
          console.log(expiresValue);
          return new Date(expiresValue).toUTCString();
        }
      }
    }
  }
  return null; // Cookie non trouvé ou pas de valeur d'expiration
}
// ******************************
// Function AJAX pour le chargement des pages

function AJP(method, url, data, target, loader) {
  var status;
  $("#" + loader).show();
  $.ajax({
    type: method,
    url: url,
    enctype: "multipart/form-data",
    async: true,
    data: data,
    dataType: "html",
    beforeSend: function () {
      $("#" + loader).show();
    },
    success: function (response) {
      // verifClient();
      // verifInfosCompte();
      // verifInfosUsers();

      $("#" + loader).hide();
      $("#" + target).html(response);
      $(response).html("#" + target);
      status = response;
    },
    error: function (result, status, error) {
      $(result).appendTo("#" + target);
      $("#" + loader).hide();
      $("#errajax").appendTo(error);
      status = result;
    },
    complete: function () {
      $("#" + loader).hide();
      //$('#' + target).show();
    },
  });
}
function AJPFile(method, url, data, target, loader) {
  var status;
  $("#" + loader).show();
  $.ajax({
    type: method,
    url: url,
    processData: false,
    contentType: false,
    async: true,
    data: data,
    dataType: "html",
    beforeSend: function () {
      $("#" + loader).show();
      //$('#' + target).hide();
    },
    success: function (response) {
      verifClient();
      verifInfosCompte();
      verifInfosUsers();
      var sess = getSession();
      $("#" + loader).hide();
      $("#" + target).html(response);
      $(response).html("#" + target);
      status = response;
    },
    error: function (result, status, error) {
      $(result).appendTo("#" + target);
      $("#" + loader).hide();
      $("#errajax").appendTo(error);
      status = result;
    },
    complete: function () {
      $("#" + loader).hide();
      //$('#' + target).show();
    },
  });
}
function getCookie(nomCookie) {
  deb = document.cookie.indexOf(nomCookie + "=");
  if (deb >= 0) {
    deb += nomCookie.length + 1;
    fin = document.cookie.indexOf(";", deb);
    if (fin < 0) fin = document.cookie.length;
    return decodeURIComponent(document.cookie.substring(deb, fin));
  } else return "";
}
function ajaxForm(formulaire, page_demande, cadre_desti, image_attente) {
  let dec = sha256("deconnexion");
  const limit = getCookie("limit");
  var data = $(formulaire).serialize();
  //alert('Valeur de limit : ' + limit);
  if (limit === null) {
    window.location = "https://app.enooki.com/" + dec; //
    console.log(
      "On deconnecte en allant sur " + "https://app.enooki.com/" + dec
    );
  } else {
    AJP("POST", page_demande, data, cadre_desti, image_attente);
    $("#renvoi_page").val(page_demande);
    console.log(
      "Cookie " + limit + ". On travail et on continu...              " + dec
    );
    let surclick =
      "ajaxForm('" +
      formulaire +
      "','" +
      page_demande +
      "','" +
      cadre_desti +
      "','" +
      image_attente +
      "');";
    //updateBreadcrumb(page_demande, surclick);
  }
}
function ajaxFile(formulaire, page_demande, cadre_desti, image_attente) {
  let dec = sha256("deconnexion");
  const limit = getCookie("limit");
  var formData = new FormData($(formulaire)[0]);
  if (limit === null) {
    window.location = "https://app.enooki.com/" + dec;
    console.log(
      "On se déconnecte en allant sur " + "https://app.enooki.com/" + dec
    );
  } else {
    AJPFile("POST", page_demande, formData, cadre_desti, image_attente);
    $("#renvoi_page").val(page_demande);
    console.log("On travaille et on continue... " + dec);
  }
}
function ajaxData(data, page_demande, cadre_desti, image_attente) {
  let dec = sha256("deconnexion");
  const limit = getCookie("limit");
  console.log("Cookie limit", limit);
  if (limit === null) {
    window.location = "https://app.enooki.com/sortie"; //+ dec
    console.log(
      "On deconnecte en allant sur " + "https://app.enooki.com/sortie"
    );
  } else {
    AJP("POST", page_demande, data, cadre_desti, image_attente);
    $("#renvoi_page").val(page_demande);
    console.log(
      "Cookie " + limit + ". On travail et on continu...              " + dec
    );
  }
}

// ******************************
// END Function AJAX pour le chargement des pages

// ******************************
// Function de limitation pour le signup

// ***** Création svg/idcompte *****
function getRandomHexColor() {
  const randomColor = Math.floor(Math.random() * 0xffffff);
  const hexColor = `#${randomColor.toString(16).padStart(6, "0")}`;
  return hexColor;
}
function generateCircleSVG(identifier) {
  // SVG namespace
  const xmlns = "http://www.w3.org/2000/svg";

  // SVG properties
  const color = getRandomHexColor();

  const width = 30; // Width of the SVG
  const height = 30; // Height of the SVG
  const radius = 15; // Radius of the circle
  const circleColor = "#1a1e2b";
  // const color = "#9d232380"; // Circle color
  const textColor = "#ffffff"; // Text color
  const fontSize = "16px"; // Font size of the text
  const fontW = "700";
  // Create an SVG element
  const svg = document.createElementNS(xmlns, "svg");
  svg.setAttribute("width", width);
  svg.setAttribute("height", height);
  svg.setAttribute("viewBox", `0 0 ${width} ${height}`);
  svg.setAttribute("xmlns", xmlns);

  // Create a circle
  const circle = document.createElementNS(xmlns, "circle");
  circle.setAttribute("cx", width / 2); // Center x
  circle.setAttribute("cy", height / 2); // Center y
  circle.setAttribute("r", radius); // Radius
  circle.setAttribute("fill", color); // Fill color

  const mycircle = document.createElementNS(xmlns, "circle");
  mycircle.setAttribute("cx", width / 2);
  mycircle.setAttribute("cy", height / 2);
  mycircle.setAttribute("r", radius / 1.5);
  mycircle.setAttribute("fill", circleColor);

  const text = document.createElementNS(xmlns, "text");
  text.setAttribute("x", width / 2 + 0.5);
  text.setAttribute("y", height / 2 + 5.5);
  text.setAttribute("fill", textColor);
  text.setAttribute("font-size", fontSize);
  text.setAttribute("font-family", "sans-serif");
  text.setAttribute("font-weight", fontW);
  text.setAttribute("text-anchor", "middle");
  text.textContent = identifier;

  svg.appendChild(circle);
  svg.appendChild(mycircle);
  svg.appendChild(text);

  // const svgContainer = document.getElementById("svgContainer");
  // svgContainer.innerHTML = "";

  // svgContainer.appendChild(svg);

  const serializer = new XMLSerializer();
  const svgString = serializer.serializeToString(svg);
  return svgString;
}
function saveSVG(svgContent, name) {
  fetch("/api/upload_svg.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "svgContent=" + encodeURIComponent(svgContent) + "&name=" + name,
  })
    .then((response) => response.text())
    .then((data) => {
      console.log("okokok", data); // Affiche le message de succès ou d'erreur
    })
    .catch((error) => {
      console.error("Erreur:", error);
    });
}

// ***** Création svg/idcompte *****

function togglePassword(input, button) {
  let passwordInput = $("#" + input);
  let currentType = passwordInput.attr("type");
  console.log("État F : ", currentType);
  if (currentType === "password") {
    console.log("Changement F ", currentType);
    passwordInput.attr("type", "text");
    $("#" + button).html(
      "<i class='bx bx-hide icon-bar bx-flxxx mt-2 ml-2 pointer absplus text-warning'>"
    );
  } else {
    console.log("Changement F ", currentType);
    passwordInput.attr("type", "password");
    $("#" + button).html(
      "<i class='bx bx-show icon-bar bx-flxxx mt-2 ml-2 pointer absplus text-primary'>"
    );
  }
}

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

  // $('#toggleBtn').on('click', function () {
  // 	var passwordInput = $('#password');
  // 	var currentType = passwordInput.attr('type');
  // 	if (currentType === 'password') {
  // 		passwordInput.attr('type', 'text');
  // 		$('#toggleBtn').html("<i class='bx bx-hide icon-bar bx-flxxx mt-2 ml-2 pointer absplus text-warning'>");
  // 	} else {
  // 		passwordInput.attr('type', 'password');
  // 		$('#toggleBtn').html("<i class='bx bx-show icon-bar bx-flxxx mt-2 ml-2 pointer absplus text-primary'>");
  // 	}
  // });
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
// ******************************
// END  Function de limitation pour le signup

// Calendar

function ajaxEvent(formulaire) {
  let dec = sha256("deconnexion");
  const limit = getCookie("limit");
  var data = $(formulaire).serialize();
  console.log("Data form ****************//");
  console.log(data);
  if (limit === null) {
    window.location = "https://app.enooki.com/" + dec; //
    console.log(
      "Déconnecté depuis calendar. Envoi " + "https://app.enooki.com/" + dec
    );
  } else {
    var title = $("#client").val();
    var start = $("#debut").val();
    var end = $("#fin").val();
    var dateStr = $("#eventDateHidden").val();
    var description = $("#description").val();

    if (title && start && end) {
      var startDateTime = dateStr + "T" + start;
      var endDateTime = dateStr + "T" + end;

      // Envoyer les données via AJAX pour les sauvegarder dans la base de données
      $.ajax({
        url: "../src/pages/calendar/event_add.php",
        type: "POST",
        data: {
          title: title,
          start: startDateTime,
          end: endDateTime,
        },
        success: function () {
          // Ajouter l'événement au calendrier après sauvegarde

          $("#eventModal").modal("hide"); // Fermer la modale
          Note("Prestation modifiée", "", "info", 1800);
        },
      });
    } else {
      alert("Veuillez remplir tous les champs.");
    }
  }
}

function dateStr(dateString) {
  const dateObj = new Date(dateString);

  // Formater la date (jour/mois/année)
  const day = ("0" + dateObj.getDate()).slice(-2); // Ajoute un 0 si nécessaire
  const month = ("0" + (dateObj.getMonth() + 1)).slice(-2); // Les mois sont de 0 à 11
  const year = dateObj.getFullYear();
  const formattedDate = `${day}/${month}/${year}`;

  return formattedDate;
}

function timeStr(dateString) {
  const dateObj = new Date(dateString);

  const hours = ("0" + dateObj.getHours()).slice(-2);
  const minutes = ("0" + dateObj.getMinutes()).slice(-2);
  const formattedTime = `${hours}:${minutes}`;

  return formattedTime;
}

// Tableau pour stocker les 5 dernières pages demandées
// Tableau pour stocker les 8 dernières pages demandées
let breadcrumbHistory = [];

// Fonction pour formater les noms de fichiers en titres
function formatTitle(page) {
  let formattedPage = page.replace(".php", ""); // Retirer l'extension .php
  formattedPage = formattedPage.replace(/[_-]/g, " "); // Remplacer les underscores et tirets par des espaces
  formattedPage = formattedPage
    .split(" ")
    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
    .join(" "); // Capitaliser la première lettre de chaque mot
  return formattedPage;
}

// Fonction pour extraire les paramètres d'une chaîne `data`
function getData(data) {
  let datas = {}; // On va utiliser un objet pour stocker les paires clé-valeur
  let group = data.split("&"); // Séparer les éléments par le caractère '&'

  group.forEach(function (item) {
    let parts = item.split("="); // Séparer chaque élément en clé et valeur par le caractère '='
    let key = parts[0]; // Première partie : la clé
    let value = parts[1] || ""; // Deuxième partie : la valeur (ou chaîne vide si elle n'existe pas)
    datas[key] = value; // Ajouter la paire clé-valeur dans l'objet
  });

  return datas; // Retourner l'objet contenant les données
}

// Fonction pour récupérer les informations `href` ou le deuxième paramètre `ajaxData`
function extractLinkData(anchor) {
  // Récupération de `href`
  const href = anchor.getAttribute("href");

  // Récupération de `onclick` et extraction du deuxième paramètre
  const onclick = anchor.getAttribute("onclick");
  let ajaxCommand = null;

  if (onclick) {
    // Extraire le deuxième paramètre avec une RegEx
    const match = onclick.match(/ajaxData\([^,]+,\s*'([^']+)'/);
    ajaxCommand = match ? match[1] : null;
  }

  return { ajaxCommand };
}

// Fonction pour gérer le breadcrumb
function updateBreadcrumb(page_demande, ajaxCommand, data) {
  const segments = page_demande.split("/");
  const lastPage = segments.pop(); // Extraire le dernier segment
  const title = formatTitle(lastPage); // Formater le titre

  let result = getData(data); // Extraire les données de `data`
  let idcli = result.idcli || ""; // Si `idcli` n'est pas présent, définir comme vide

  // Ajouter uniquement si c'est un clic utilisateur valide
  breadcrumbHistory.push({ title: title, command: ajaxCommand, idcli: idcli });

  // Limiter l'historique à 5 éléments maximum
  if (breadcrumbHistory.length > 10) {
    breadcrumbHistory.shift(); // Supprimer l'élément le plus ancien
  }

  // Mettre à jour l'affichage des breadcrumbs
  displayBreadcrumbs();

  // Mettre à jour le cookie de la dernière page
  setmyCookie("lastPage", page_demande, 2);
}

// Fonction pour afficher les breadcrumbs
function displayBreadcrumbs() {
  const breadcrumbContainer = $("#breadcrumb"); // Sélection du conteneur
  breadcrumbContainer.empty(); // Vider le conteneur pour un nouvel affichage

  // Afficher chaque élément de l'historique
  breadcrumbHistory.forEach((page, index) => {
    const pageTitle = page.title;
    const ajaxCommand = page.command;
    const idcli = page.idcli;
    //console.log('*** ICI ***', idcli)
    // Créer un élément breadcrumb avec l'événement onclick associé
    const breadcrumbElement = $(
      `<span class="bread"><a onclick="ajaxData('','${ajaxCommand}','target-one','attente');" class="text-white pointer">${
        pageTitle.toLowerCase() + " " + idcli
      }</a></span>`
    );

    breadcrumbContainer.append(breadcrumbElement);

    // Ajouter une flèche si ce n'est pas le dernier élément
    if (index < breadcrumbHistory.length - 1) {
      breadcrumbContainer.append(
        "<i class='bx bxs-right-arrow-square bx-sm'></i>"
      );
    }
  });
}

// Gérer les cookies (création, lecture, suppression)
function setmyCookie(name, value, hours) {
  let d = new Date();
  d.setTime(d.getTime() + hours * 60 * 60 * 1000); // Convertir les heures en millisecondes
  let expires = "expires=" + d.toUTCString();
  document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

function getmyCookie(name) {
  let decodedCookie = decodeURIComponent(document.cookie);
  let cookiesArray = decodedCookie.split(";");
  for (let i = 0; i < cookiesArray.length; i++) {
    let cookie = cookiesArray[i].trim();
    if (cookie.indexOf(name + "=") == 0) {
      return cookie.substring(name.length + 1, cookie.length);
    }
  }
  return null;
}

function deleteCookie(name) {
  document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}

// Fonction pour extraire uniquement le deuxième paramètre de ajaxData depuis l'attribut onclick
function extractAjaxCommandFromClick(event) {
  // Empêcher toute exécution par défaut
  event.preventDefault();

  // Vérifier si l'élément cliqué est un <a>
  const anchor = event.target;
  if (anchor.tagName === "A") {
    // Récupérer la commande définie dans onclick
    const onclick = anchor.getAttribute("onclick");

    if (onclick) {
      // Utiliser une expression régulière pour extraire le 2e paramètre de ajaxData
      const match = onclick.match(/ajaxData\([^,]+,\s*'([^']+)'/);
      const ajaxCommand = match ? match[1] : null;

      // Afficher le résultat dans la console (ou utiliser ailleurs dans votre code)
      console.log("Commande AJAX récupérée :", ajaxCommand);

      return ajaxCommand; // Retourner la commande si besoin
    } else {
      console.log("Pas de commande AJAX dans onclick.");
    }
  }
  return null;
}

// Gestion des clics sur les éléments <a>
// document.addEventListener('click', function (event) {
// 	// Vérifiez que l'utilisateur a cliqué sur une balise <a>
// 	if (event.target.tagName === 'A') {
// 		event.preventDefault(); // Empêche la navigation ou toute exécution par défaut

// 		// Récupérez le contenu de l'attribut `onclick`
// 		const onclick = event.target.getAttribute('onclick');

// 		if (onclick) {
// 			// Extraire le deuxième paramètre de ajaxData
// 			const match = onclick.match(/ajaxData\([^,]+,\s*'([^']+)'/);
// 			const ajaxCommand = match ? match[1] : null;

// 			if (ajaxCommand) {
// 				console.log('Commande AJAX cliquée par l\'utilisateur :', ajaxCommand, onclick);

// 				// Appeler la fonction updateBreadcrumb avec les données du clic
// 				updateBreadcrumb(ajaxCommand, ajaxCommand, 'data=data');
// 			}
// 		}
// 	}
// });

// Vérification du cookie de la dernière page lors de la connexion
function checkLastPage() {
  const lastPage = getmyCookie("lastPage");
  if (lastPage !== null) {
    console.log("Redirection vers la dernière page demandée : " + lastPage);
    // Utiliser ajaxData pour rediriger vers la dernière page
    ajaxData({}, lastPage, "#cadre_desti", "#image_attente"); // Exemple de redirection avec ajaxData
  } else {
    console.log("Aucune page précédente trouvée.");
  }
}

function ajaxDataGET(data, page_demande, cadre_desti, image_attente) {
  let dec = sha256("deconnexion");
  const limit = getCookie("limit");
  //alert('EFA affiche : ' + limit);
  if (limit === null) {
    window.location = "https://app.enooki.com/" + dec; //
    console.log(
      "On deconnecte en allant sur " + "https://app.enooki.com/" + dec
    );
  } else {
    AJP("GET", page_demande, data, cadre_desti, image_attente);
    $("#renvoi_page").val(page_demande);
    console.log(
      "Cookie " + limit + ". On travail et on continu...              " + dec
    );
  }
}

// *****************************
// elargissement de la page

$(function () {
  $('[data-bs-toggle="tooltip"]').tooltip();
});

function modi() {
  // Sélectionner tous les div avec la classe 'modi' qui ont aussi la classe 'container'
  $(".modi").each(function () {
    if ($(this).hasClass("container")) {
      $(this).removeClass("container").addClass("container-fluid");
      console.log("Changement en container-fluid");
    } else if ($(this).hasClass("container-fluid")) {
      $(this).removeClass("container-fluid").addClass("container");
      console.log("Changement en container");
    }
  });
}

// *****************************
// elargissement de la page

// ******************************

function fermeCadre(cadre_desti) {
  $("#" + cadre_desti).fadeOut();
}
function Trans(dici, ala) {
  var trans = $(dici).text();
  $(ala).append(trans);
}
function eff_form(t) {
  $(t).val("");
}
function pushSuccess(titre, text) {
  new Notify({
    status: "success", //error warning
    title: titre,
    text: text,
    effect: "fade", //fade
    speed: 1000,
    customClass: null,
    customIcon: '<i class="bx bx-like bx-lg icon-bar"></i>',
    showIcon: true,
    showCloseButton: true,
    autoclose: true,
    autotimeout: 5000,
    gap: 30,
    distance: 30,
    type: 3,
    position: "right top",
  });
}
function pushSauvegarde(titre, text, phrase = "") {
  new Notify({
    status: "success", //error warning
    title: "",
    text: text + ' "' + phrase + '"',
    effect: "fade", //slide fade
    speed: 400,
    customClass: null,
    customIcon: '<i class="bx bx-like bx-lg icon-bar text-white"></i>',
    showIcon: false,
    showCloseButton: true,
    autoclose: false,
    autotimeout: 3000,
    gap: 2,
    distance: 0,
    type: 3,
    position: "x-center bottom",
  });
}

// 'error', 'warning', 'success', 'info'
function Note(text, infos = "", type, time) {
  new Notify({
    status: type, //error warning
    title: "",
    text: text + " " + infos + "",
    effect: "fade", //slide fade
    speed: 600,
    customClass: null,
    customIcon: '<i class="bx bx-like bx-lg icon-bar text-white"></i>',
    showIcon: false,
    showCloseButton: true,
    autoclose: true,
    autotimeout: time,
    gap: 2,
    distance: 0,
    type: 3,
    position: "x-center bottom",
  });
}

function pushError(titre, text) {
  new Notify({
    status: "error", // success error warning
    title: titre,
    text: text,
    effect: "fade", //fade
    speed: 400,
    customClass: null,
    customIcon: '<i class="bx bxs-error bx-lg icon-bar"></i>',
    showIcon: true,
    showCloseButton: true,
    autoclose: true,
    autotimeout: 8000,
    gap: 30,
    distance: 30,
    type: 3,
    position: "x-center bottom",
  });
}
function pushDoublons(titre, text, phrase = "") {
  new Notify({
    status: "error", // success error warning
    title: titre,
    text: text + ' "' + phrase + '"',
    effect: "slide", //fade
    speed: 400,
    customClass: null,
    customIcon: '<i class="bx bxs-error bx-lg icon-bar"></i>',
    showIcon: true,
    showCloseButton: true,
    autoclose: true,
    autotimeout: 6000,
    gap: 30,
    distance: 30,
    type: 3,
    position: "x-center bottom",
  });
}
function pushWarning(titre, text) {
  new Notify({
    status: "warning", // success error warning
    title: titre,
    text: text,
    effect: "fade", //fade, slide
    speed: 1500,
    customClass: null,
    customIcon: '<i class="bx bx-info-circle bx-lg icon-bar"></i>',
    showIcon: true,
    showCloseButton: true,
    autoclose: true,
    autotimeout: 5000,
    gap: 30,
    distance: 30,
    type: 3,
    position: "x-center bottom",
  });
}
function creCook(name, value, days) {
  var expires;
  if (days) {
    var date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    expires = "; expires=" + date.toGMTString();
  } else {
    expires = "";
  }
  document.cookie = name + "=" + value + expires + "; SameSite=Strict; path=/";
}
var full_screen_enabled = !1;
function toggleFullScreen() {
  full_screen_enabled ? closeFullscreen() : openFullscreen();
}
function openFullscreen() {
  full_screen_enabled = !0;
  var a = document.documentElement;
  $("#icon-holder").html(
    '<i class="text-primary bx-flip-horizontal bx bx-expand-alt icon-bar "></i>'
  );
  a.requestFullscreen
    ? a.requestFullscreen()
    : a.mozRequestFullScreen
    ? a.mozRequestFullScreen()
    : a.webkitRequestFullscreen
    ? a.webkitRequestFullscreen()
    : a.msRequestFullscreen && a.msRequestFullscreen();
}
function closeFullscreen() {
  full_screen_enabled = !1;
  $("#icon-holder").html(
    '<i class="text-muted bx bx-expand-alt icon-bar"></i>'
  );
  document.exitFullscreen
    ? document.exitFullscreen()
    : document.mozCancelFullScreen
    ? document.mozCancelFullScreen()
    : document.webkitExitFullscreen
    ? document.webkitExitFullscreen()
    : document.msExitFullscreen && document.msExitFullscreen();
}
function cookFond() {
  var classBody = $("body").prop("class");
  if (classBody == "dark fond") {
    creCook("mode", "jour", "");
  }
  if (classBody == "fond") {
    creCook("mode", "nuit", "");
  }
}
function modeJourNuit() {
  var classBody = $("body").prop("class");
  if (classBody == "dark fond") {
    $("#toggleLink").html('<i class="text-muted bx bx-moon icon-bar "></i>');
    $("body").removeClass();
    $("body").addClass("fond");
    $("html").removeClass();
    $("html").addClass("fond");
    creCook("mode", "jour", "");
  }
  if (classBody == "fond") {
    $("#toggleLink").html('<i class="text-muted bx bx-sun icon-bar "></i>');
    $("body").removeClass();
    $("body").addClass("dark fond");
    $("html").removeClass();
    $("html").addClass("dark fond");
    creCook("mode", "nuit", "");
  }
}
function rechercheClient(time = 600) {
  var timer;
  clearTimeout(timer);
  timer = setTimeout(function () {
    baseClient();
  }, time);
}
function baseClient() {
  var valeurInput = document.getElementById("recherche_cli").value;
  console.log("Valeur saisie : " + valeurInput);
  ajaxData(
    "limit=1&term=" + valeurInput + "",
    "../src/pages/contacts/contacts_liste.php",
    "action",
    "attente_target"
  );
}
function rechercheFacture(fen = "", time = 1200) {
  var timer;
  clearTimeout(timer);
  timer = setTimeout(function () {
    baseFacture(fen);
  }, time);
}
function baseFacture(fen = "") {
  var valeurInput = document.getElementById("recherche_facture").value;
  console.log("Valeur saisie : " + valeurInput);
  ajaxData(
    "limit=1&term=" + valeurInput + "",
    "../src/pages/factures/factures_liste.php",
    fen,
    "xx"
  );
}
// Recherche règlements
function rechercheReglements(fen = "", time = 600) {
  var timer;
  clearTimeout(timer);
  timer = setTimeout(function () {
    baseReglements(fen);
  }, time);
}
function baseReglements(fen = "") {
  var valeurInput = document.getElementById("valrech").value;
  console.log("Valeur saisie : " + valeurInput);
  ajaxData(
    "limit=1&term=" + valeurInput + "",
    "../src/pages/reglements/reglements_liste_pointage.php",
    fen,
    "xx"
  );
}
function askPhrases(num) {
  $("#designation" + num).autocomplete({
    minLength: 0,
    source: function (request, response) {
      $.ajax({
        url: "https://app.enooki.com/inc/suggest_phrase.php",
        dataType: "json",
        data: {
          term: request.term,
        },
        success: function (data) {
          response(data);
        },
      });
    },
    select: function (event, ui) {
      var nom = ui.item.label;
      $("#designation" + num).text(nom);
    },
  });
}
function calculTotal() {
  var total = 0;
  $("input[name^='tot_']").each(function () {
    var value = parseFloat($(this).val());
    if (!isNaN(value)) {
      total += value;
    }
  });
  var acompte = $("#acompte").val();
  var totalnet = total - acompte;
  $("#total_general").val(total.toFixed(2));
  $("#apayer").val(totalnet.toFixed(2));
}
function suppLigne(element) {
  var ligne = $(element).closest("tr");
  var compteur = ligne.attr("num");
  ligne.remove();
  calculTotal();
  compteur = parseInt(compteur) - 1;
  $("#numero_designation").val(compteur);
}
function transLigne(num) {
  var i = $("#i" + num + "").val();
  var numinter = $("#numinter" + num + "").val();
  var numdev = $("#numdev" + num + "").val();
  var designation = $("#designation" + num + "").val();
  var quant = $("#quant" + num + "").val();
  var pu = $("#pu" + num + "").val();
  var tot = $("#tot_" + num + "").val();
  var concat =
    i +
    "_" +
    numinter +
    "_" +
    designation +
    "_" +
    quant +
    "_" +
    pu +
    "_" +
    tot +
    "_" +
    numdev;
  $("#trans" + num + "").val(concat);
  console.log(concat);
}
function calculLigne(num) {
  var q = $("#quant" + num + "").val();
  var pu = $("#pu" + num + "").val();
  var multi = q * pu;
  multi = multi.toFixed(2);
  $("#tot_" + num + "").val(multi);
  transLigne(num);
  calculTotal();
}
function getIframePDF(chemin, cible) {
  // Spécifiez l'URL du script PHP
  var urlScriptPHP = chemin;
  // Obtenez une référence à l'iframe
  var iframe = document.getElementById(cible);
  // Définissez l'URL de l'iframe pour charger le script PHP
  iframe.src = urlScriptPHP;
}
function getPDF(chemin, cible) {
  $.ajax({
    url: chemin,
    type: "HEAD",
    success: function () {
      // Le document existe, l'afficher dans la cible
      $("#" + cible).load(chemin);
    },
    error: function () {
      // Le document n'existe pas, générer et stocker le document
      var urlScriptPHP = "http://devis_pdf.php?numero=5454";
      var largeurFenetre = 1000;
      var hauteurFenetre = 700;
      var xPos = (screen.width - largeurFenetre) / 4;
      var yPos = (screen.height - hauteurFenetre) / 2;
      var options =
        "top=-20, left=-20, width=" +
        largeurFenetre +
        ", height=" +
        hauteurFenetre;
      var nouvelleFenetre = window.open(urlScriptPHP, cible, options);
      // Vérifier si la fenêtre s'est ouverte avec succès
      if (
        !nouvelleFenetre ||
        nouvelleFenetre.closed ||
        typeof nouvelleFenetre.closed == "undefined"
      ) {
        alert(
          "La fenêtre pop-up a été bloquée par le navigateur. Veuillez autoriser les pop-ups pour ce site."
        );
      }
    },
  });
}
function getRelancesPDF(chemin, cible, secteur) {
  // Fonction AJAX pour vérifier l'existence du document
  let xhr = new XMLHttpRequest();
  console.log(chemin);
  xhr.open("GET", chemin); // Placer la requête AJAX au début
  xhr.setRequestHeader(
    "Access-Control-Allow-Origin",
    "https://app.enooki.com/"
  );
  xhr.send(); // Envoyer la requête AJAX après avoir défini les paramètres
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        //
        //   console.log('RES' + xhr.responseText)
        //   document.getElementById(cible).innerHTML = xhr.responseText;
        // } else {
        // Le document n'existe pas, générer et stocker le document
        var urlScriptPHP =
          "https://app.enooki.com/src/pages/factures/relances_01_pdf.php?secteur=" +
          secteur;
        // Ouvrir une nouvelle fenêtre pour générer le document
        var largeurFenetre = 1200;
        var hauteurFenetre = 1200;
        //var xPos = (screen.width - largeurFenetre) / 4;
        //var yPos = (screen.height - hauteurFenetre) / 2;
        //,top=-20, left=-20, width=' + largeurFenetre + ', height=' + hauteurFenetre
        var options =
          "fullscreen=yes,menubar=no,titlebar=no,status=no,toolbar=no,location=no";
        var nouvelleFenetre = window.open(urlScriptPHP, cible, options);
        // Vérifier si la fenêtre s'est ouverte avec succès
        if (
          !nouvelleFenetre ||
          nouvelleFenetre.closed ||
          typeof nouvelleFenetre.closed == "undefined"
        ) {
          alert(
            "La fenêtre pop-up a été bloquée par le navigateur. Veuillez autoriser les pop-ups pour ce site."
          );
        }
      }
    }
  };
}
function getAttestationPDF(chemin, cible, annee, idcli, secteur) {
  // Fonction AJAX pour vérifier l'existence du document
  let xhr = new XMLHttpRequest();
  console.log(chemin);
  xhr.open("HEAD", chemin); // Placer la requête AJAX au début
  xhr.setRequestHeader(
    "Access-Control-Allow-Origin",
    "https://app.enooki.com/"
  );
  xhr.send(); // Envoyer la requête AJAX après avoir défini les paramètres
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        //   console.log('RES' + cible)
        //   document.getElementById(cible).innerHTML = xhr.responseText;
        // } else {
        // Le document n'existe pas, générer et stocker le document
        var urlScriptPHP =
          "https://app.enooki.com/src/pages/attestations/attestation_01_pdf.php?annref=" +
          annee +
          "&secteur=" +
          secteur +
          "&idcli=" +
          idcli;
        // Ouvrir une nouvelle fenêtre pour générer le document
        var largeurFenetre = 1200;
        var hauteurFenetre = 1200;
        //var xPos = (screen.width - largeurFenetre) / 4;
        //var yPos = (screen.height - hauteurFenetre) / 2;
        //,top=-20, left=-20, width=' + largeurFenetre + ', height=' + hauteurFenetre
        var options =
          "fullscreen=yes,menubar=no,titlebar=no,status=no,toolbar=no,location=no";
        var nouvelleFenetre = window.open(urlScriptPHP, cible, options);
        // Vérifier si la fenêtre s'est ouverte avec succès
        if (
          !nouvelleFenetre ||
          nouvelleFenetre.closed ||
          typeof nouvelleFenetre.closed == "undefined"
        ) {
          alert(
            "La fenêtre pop-up a été bloquée par le navigateur. Veuillez autoriser les pop-ups pour ce site."
          );
        }
      }
    }
  };
}
function getDocPDF(chemin, cible) {
  // Spécifiez l'URL du script PHP
  var urlScriptPHP = chemin;
  // Calculez la largeur et la hauteur de la fenêtre
  var largeurFenetre = 1000;
  var hauteurFenetre = 700;
  // Calculez la position horizontale et verticale pour centrer la fenêtre
  var xPos = (screen.width - largeurFenetre) / 4;
  var yPos = (screen.height - hauteurFenetre) / 2;
  //alert(xPos + ' ' + yPos)
  // Ouvrez la fenêtre avec les calculs de position
  var options =
    "top=-20, left=-20, width=" + largeurFenetre + ", height=" + hauteurFenetre;
  var nouvelleFenetre = window.open(urlScriptPHP, cible, options);
  // Assurez-vous que la fenêtre s'est ouverte avec succès
  if (
    !nouvelleFenetre ||
    nouvelleFenetre.closed ||
    typeof nouvelleFenetre.closed == "undefined"
  ) {
    alert(
      "La fenêtre pop-up a été bloquée par le navigateur. Veuillez autoriser les pop-ups pour ce site."
    );
  }
}
function setCookie(name, value, days) {
  var expires = "";
  if (days) {
    var date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    expires = "; expires=" + date.toUTCString();
  }
  document.cookie = name + "=" + (value || "") + expires + "; path=/";
}
function getCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(";");
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) === " ") c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
  }
  return null;
}

function acceptCookies() {
  // Ajouter les informations dans le cookie
  var userInformation =
    "Browser: " +
    navigator.userAgent +
    "; OS: " +
    navigator.platform +
    "; IP: " +
    "<?php echo $_SERVER['REMOTE_ADDR']; ?>";
  setCookie("cookies_accepted", userInformation, 30); // Adjust the expiration as needed
  document.getElementById("cookie-banner").style.display = "none";
}
window.onload = function () {
  if (!getCookie("cookies_accepted")) {
    document.getElementById("cookie-banner").style.display = "block";
  }
};
