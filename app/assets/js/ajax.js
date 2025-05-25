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
        console.log("Je met le rappel " + btncli.attr("class"));
      } else {
        $("#client_menu").removeClass("rappel");
        $("#sous_menu_contact").removeClass("rappel");
        console.log("Je ne met pas le rappel " + btncli.attr("class"));
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
        console.log("Je met le rappel " + btncli.attr("class"));
      } else {
        $("#infos_sous_menu").removeClass("rappel");
        $("#compte_menu").removeClass("rappel");
        console.log("Je ne met pas le rappel " + btncli.attr("class"));
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
        console.log("Je met le rappel " + btncli.attr("class"));
      } else {
        $("#intervenant_menu").removeClass("rappel");
        $("#intervenant_sous_menu").removeClass("rappel");
        console.log("Je ne met pas le rappel " + btncli.attr("class"));
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
    async: true,
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

function getCookie(nomCookie) {
  deb = document.cookie.indexOf(nomCookie + "=");
  if (deb >= 0) {
    deb += nomCookie.length + 1;
    fin = document.cookie.indexOf(";", deb);
    if (fin < 0) fin = document.cookie.length;
    return decodeURIComponent(document.cookie.substring(deb, fin));
  } else return "";
}
function FabrikCookie(cname, cvalue, exminutes) {
  var d = new Date();
  d.setTime(d.getTime() + exminutes * 60 * 1000);
  var expires = "expires=" + d.toUTCString();
  document.cookie =
    cname +
    "=" +
    cvalue +
    ";" +
    expires +
    ";domain=m.sagaas.fr; path=/; samesite=strict;Secure";
}
function getSession() {
  $.ajax({
    type: "POST",
    url: "https://app.enooki.com/src/menus/get_session_value.php",
    enctype: "multipart/form-data",
    async: true,
    data: "secteur",
    dataType: "html",
    success: function (data) {
      // Store the session value in a variable
      var usr = getCookie("PHPSESSID");
      console.log('Vérification valeur *** de session "user" = ' + data);
      //FabrikCookie('sessval', usr, 45);
      console.log('Création du cookie "sessval" = ' + data);
    },
  });
}

function AJP(method, url, data, target, loader) {
  var status;
  $.ajax({
    type: method,
    url: url,
    enctype: "multipart/form-data",
    async: true,
    data: data,
    dataType: "html",
    beforeSend: function () {
      $("#" + loader).show();
      console.log("Verifier la valeur du cookie ici");
      //$('#' + target).hide();
    },
    success: function (response) {
      console.log("Verifier la valeur du cookie ici");
      // verifClient();
      // verifInfosCompte();
      // verifInfosUsers();
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

function ajaxForm(formulaire, page_demande, cadre_desti, image_attente) {
  // getSession();
  // var usr = getCookie('user');
  // var sess = getCookie('PHPSESSID');
  // if (usr == sess) {
  var data = $(formulaire).serialize(); //
  // var jsonData = {};
  // $.each(data, function (index, field) {
  // 	jsonData[field.name] = field.value;
  // });
  //alert('EF : ' + jsonData );
  AJP("POST", page_demande, data, cadre_desti, image_attente);
  // } else {
  // 	window.location = "https://m.sagaas.fr"; // + md5('sess_end')
  // }
}
function ajaxData(data, page_demande, cadre_desti, image_attente) {
  // getSession();
  // var usr = getCookie('user');
  // var sess = getCookie('PHPSESSID');
  //alert('EFA PHPSESSID = ' + sess + ' / user = ' + usr);
  // console.log('Depuis EFA. Valeur de session "sess" "PHPSESSID" = ' + sess);
  // console.log('Depuis EFA. Valeur de session "usr" "user" = ' + usr);
  // console.log('Valeur du cookie "user" = ' + usr);
  // if (usr == sess) {
  //alert('EFA : ' + data + ' usr : ' + usr);
  AJP("POST", page_demande, data, cadre_desti, image_attente);
  // } else {
  // 	window.location = "https://m.sagaas.fr"; // + md5('sess_end')
  // }
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
    effect: "slide", //fade
    speed: 300,
    customClass: null,
    customIcon: '<i class="bx bx-like bx-lg icon-bar"></i>',
    showIcon: true,
    showCloseButton: true,
    autoclose: true,
    autotimeout: 2500,
    gap: 30,
    distance: 22,
    type: 2,
    position: "right bottom",
  });
}
function pushError(titre, text) {
  new Notify({
    status: "error", // success error warning
    title: titre,
    text: text,
    effect: "slide", //fade
    speed: 300,
    customClass: null,
    customIcon: '<i class="bx bxs-error bx-lg icon-bar"></i>',
    showIcon: true,
    showCloseButton: true,
    autoclose: true,
    autotimeout: 2500,
    gap: 30,
    distance: 22,
    type: 2,
    position: "right bottom",
  });
}
function pushWarning(titre, text) {
  new Notify({
    status: "warning", // success error warning
    title: titre,
    text: text,
    effect: "slide", //fade
    speed: 300,
    customClass: null,
    customIcon: '<i class="bx bx-info-circle bx-lg icon-bar"></i>',
    showIcon: true,
    showCloseButton: true,
    autoclose: true,
    autotimeout: 2500,
    gap: 30,
    distance: 22,
    type: 2,
    position: "right bottom",
  });
}
