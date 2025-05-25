// JavaScript Document
// Horloge bandeau superieur
function getWeekNumber(b) {
  var e = new Date(b);
  var c = e.getDay();
  e.setDate(e.getDate() - (c + 6) % 7 + 3);
  var a = e.valueOf();
  e.setMonth(0);
  e.setDate(4);
  return Math.round((a - e.valueOf()) / (7 * 86400000)) + 1
}

function date_heure(a) {
  date = new Date;
  W = getWeekNumber(date);
  annee = date.getFullYear();
  moi = date.getMonth();
  mois = new Array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");
  //mois = new Array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12")
  j = date.getDate();
  jour = date.getDay();
  //jours = new Array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
  jours = new Array("Dim.", "Lun.", "Mar.", "Mer.", "Jeu.", "Ven.", "Sam.");
  h = date.getHours();
  if (h < 10) {
    h = "0" + h
  }
  m = date.getMinutes();
  if (m < 10) {
    m = "0" + m
  }
  s = date.getSeconds();
  if (s < 10) {
    s = "0" + s
  }
  resultat = jours[jour] + " " + j + " " + mois[moi] + " " + annee +" - Sem " + W + " - " + h + ":" + m;
  document.getElementById(a).innerHTML = resultat;
  setTimeout('date_heure("' + a + '");', "1000");
  console.log(resultat);
  return resultat;
};
//


function lisCook(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
  }
  return c;
}