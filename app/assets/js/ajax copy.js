// *******************
// Fonctions Ajax
// FLGA 11/2021
// dist/js/ajax.js
// *******************


function AJP(posget, urle, infopas, affiche, imatt) {
	var status;

	$.ajax({
		type: posget,
		url: urle,
		enctype: 'multipart/form-data',
		async: true,
		data: infopas,
		dataType: 'html',
		beforeSend: function () {
			$("#" + imatt).show('slow');
			//alert('beforeSend');
		},

		success: function (reponse) {
			$('#' + affiche).html(reponse);
			$(reponse).html("#" + affiche);
			status = reponse;
		},
		error: function (resultat, statut, erreur) {
			$(resultat).appendTo("#" + affiche);
			$("#" + imatt).hide('slow');
			status = resultat;
		},
		complete: function () {

			//alert('complete');
			$("#" + imatt).hide('slow');

		},
	});
}

function getCookie(nomCookie) {
	deb = document.cookie.indexOf(nomCookie + "=")
	if (deb >= 0) {
		deb += nomCookie.length + 1
		fin = document.cookie.indexOf(";", deb)
		if (fin < 0) fin = document.cookie.length
		return unescape(document.cookie.substring(deb, fin))
	} else return ""
}

// Infos depuis un formulaire
function ajaxForm(formulaire, page_demande, cadre_desti, image_attente) {
	var usr = getCookie('valajax');
	if (usr == '1') {
		var data = $(formulaire).serialize(); //
		//alert('EF : ' + data + ' usr : ' + usr);
		AJP('POST', page_demande, data, cadre_desti, image_attente);
	} else {
		window.location = "https://www.sagaas.fr/dist/login.php?mess=12d85120b1f8b102c7a30bcb37106e69"; // + md5('sess_end')

	}
};

// Infos directement insérées
function ajaxData(data, page_demande, cadre_desti, image_attente) {
	var usr = getCookie('valajax');
	if (usr == '1') {
		//alert('EFA : ' + data + ' usr : ' + usr);
		AJP('POST', page_demande, data, cadre_desti, image_attente);
	} else {
		window.location = "https://www.sagaas.fr/dist/login.php?mess=12d85120b1f8b102c7a30bcb37106e69"; // + md5('sess_end')

	}
};


function Trans(dici, ala) {
	var trans = $(dici).text();
	$(ala).append(trans);
}




// ouverture et fermeture fenetres ok
function Menu(menu, titre) {

	$(menu).toggle('200', 'linear');
	$(titre).toggle('200', 'linear');

}

function Menu2(menu1, menu2, menu3) {

	$(menu1).toggle('200', 'linear');
	$(menu2).toggle('200', 'linear');
	$(menu3).toggle('200', 'linear');

}