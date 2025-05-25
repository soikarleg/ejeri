<?php
session_start();
// error_reporting(\E_ALL);
// ini_set('display_errors', 'stdout');
$chemin = $_SERVER['DOCUMENT_ROOT'];
include $chemin . '/inc/function.php';
$secteur = $_SESSION['idcompte'];
$iduser = $_SESSION['idusers'];
foreach ($_POST as $k => $v) {
  ${$k} = $v;
  //echo '$' . $k . ' = ' . $v . '</br>';
}
$conn = new connBase();
$prod = new Production($secteur);
?>
<p class="text-bold">Liste des intervenants - <?= $annref . ' ' . $iduser ?></p>
<p>Adresse de référence : <?= $adref = "3, place de l'eglise, 45740 Lailly-en-Val" ?></p>

<?php

function getCoordinates($address)
{
  // URL de l'API
  $url = 'https://api-adresse.data.gouv.fr/search/?q=' . urlencode($address);

  // Effectuer la requête HTTP
  $response = file_get_contents($url);

  // Vérifier si la requête a réussi
  if ($response === false) {
    return false; // Erreur lors de la requête
  }

  // Décoder la réponse JSON
  $data = json_decode($response, true);

  // Vérifier si des résultats ont été retournés
  if (empty($data['features'])) {
    return false; // Aucun résultat trouvé
  }

  // Extraire les coordonnées du premier résultat
  $coordinates = $data['features'][0]['geometry']['coordinates'];

  // Retourner les coordonnées (latitude et longitude)
  return [
    'latitude' => $coordinates[1],
    'longitude' => $coordinates[0]
  ];
}

//$token = sk.eyJ1Ijoib3R0b3ZvbiIsImEiOiJjbHg5a2NibzEwNDdjMmtzYzZyZWtpamtwIn0.xWqYcrHg2qJYxR4EM4qKzA
function getDistance($lon, $lat)
{
  $url = "https://api.mapbox.com/directions/v5/mapbox/driving/1.686303,47.770344;$lon,$lat?access_token=pk.eyJ1Ijoib3R0b3ZvbiIsImEiOiJjbHQ0NmJsM20xb290MmpvNWI3djZhcHN0In0.z-Jcud1SqKW1gFqMNrLEWw";

  $curl = curl_init($url);

  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  $response = curl_exec($curl);

  if ($response === false) {
    return "Erreur lors de la récupération des données";
  } else {
    $data = json_decode($response, true);
    //var_dump($data);
    $coordinates = $data['routes'][0]['distance'];
    return [
      'km' => $coordinates / 1000
    ];
  }

  curl_close($curl);
}

function getDistanceA($lon, $lat)
{
  // URL de l'API
  //$url = 'https://api-adresse.data.gouv.fr/search/?q=' . urlencode($address);
  echo $url = 'https://api.mapbox.com/directions/v5/mapbox/driving/1.686303%2C47.770344%3B' . $lon . '%2C' . $lat . '?alternatives=true&annotations=distance%2Cduration&geometries=geojson&language=en&overview=full&steps=true&access_token=pk.eyJ1Ijoib3R0b3ZvbiIsImEiOiJjbHQ0NmdrNDExZWQzMmtyejJ5Nng1cHM0In0.wZBjeEjUzeSRgu5Oc1xl8w';

  // Effectuer la requête HTTP
  $response = file_get_contents($url);
  var_dump($response);
  // Vérifier si la requête a réussi
  if ($response === false) {
    return false; // Erreur lors de la requête
  }

  // Décoder la réponse JSON
  $data = json_decode($response, true);

  // Vérifier si des résultats ont été retournés
  if (empty($data['routes'])) {
    return false; // Aucun résultat trouvé
  }

  // Extraire les coordonnées du premier résultat
  $coordinates = $data['routes'][0]['distance'];

  // Retourner les coordonnées (latitude et longitude)
  return [
    'km' => $coordinates / 1000
  ];
}


$ik = $prod->getInterTotal($iduser, $annref);
$list_inter_a = $prod->getInterIK($iduser, $annref);
$list_inter_b = $prod->getInterIK($iduser, $annref - 1);

$coord = getCoordinates($adref);
echo $coord['longitude'] . ' ' . $coord['latitude'] . '<br><br>';

//var_dump($list_inter_a);
foreach ($list_inter_a as $k) {
  $adresse = $prod->getAdresseCli($k['idcli']);
  echo $k['idcli'] . ' - ' . $k['nomcli'] . ' - ' . $k['jour'] . '/' . $k['mois'] . '/' . $k['annee'] . '<br> ' . $adresse . '<br>';
  $coordo = getCoordinates($adresse);
  echo  $coordo['longitude'] . ' ' .  $coordo['latitude'] . '<br>';
  $lon = $coordo['longitude'];
  $lat = $coordo['latitude'];
  $dist = getDistance($lon, $lat);

  echo '** ' .  Dec_2($dist['km'], ' km') . ' / ' . Dec_2($dist['km'] * 2, ' km') . '<br><br>';
}
echo '----<br>';
echo '----<br>';

foreach ($list_inter_b as $k) {
  $adresse = $prod->getAdresseCli($k['idcli']);
  echo $k['idcli'] . ' - ' . $k['nomcli'] . ' - ' . $k['jour'] . '/' . $k['mois'] . '/' . $k['annee'] . '<br> ' . $adresse . '<br>';
  $coordo = getCoordinates($adresse);
  echo  $coordo['longitude'] . ' ' .  $coordo['latitude'] . '<br>';
  $lon = $coordo['longitude'];
  $lat = $coordo['latitude'];
  $dist = getDistance($lon, $lat);

  echo '** ' .  Dec_2($dist['km'], ' km') . ' / ' . Dec_2($dist['km'] * 2, ' km') . '<br><br>';
}
// foreach ($list_inter_b as $k) {
//   $adresse = $prod->getAdresseCli($k['idcli']);
//   echo $k['idcli'] . ' - ' . $k['nomcli'] . '<br> ' . $adresse . '<br>';
//   $coordo =  getCoordinates($adresse);
//   echo $lon = $coordo['longitude'] . ' ' . $lat = $coordo['latitude'] . '<br>';
//   $dist = getDistance($lon, $lat);
//   echo '** ' . $dist['km'] . '<br>';
// }
?>



<script>
  $(function() {
    mapboxgl.accessToken = 'pk.eyJ1Ijoib3R0b3ZvbiIsImEiOiJjbHQ0NmdrNDExZWQzMmtyejJ5Nng1cHM0In0.wZBjeEjUzeSRgu5Oc1xl8w';
    //mapboxgl.accessToken = 'sk.eyJ1Ijoib3R0b3ZvbiIsImEiOiJjbHQ0c2o2b2kwNnhxMmxtcXJiajBjY2s2In0.AjJDgbG0dyDdfVnYH90tDg';

    var map = new mapboxgl.Map({
      container: 'mapo',
      center: [<?= $lonac ?>, <?= $latac ?>],
      style: 'mapbox://styles/mapbox/satellite-streets-v12',
      zoom: 18
    });
    new mapboxgl.Marker().setLngLat([<?= $lonac ?>, <?= $latac ?>]).addTo(map);
    //new mapboxgl.Marker().setLngLat([<?= $lonas ?>, <?= $latas ?>]).addTo(map);


    // Coordonnées de l'origine et de la destination
    var origin = [<?= $lonas ?>, <?= $latas ?>];
    var destination = [<?= $lonac ?>, <?= $latac ?>];
    console.log(origin + ' ' + destination);
    // Profile de l'itinéraire (par exemple, 'driving', 'cycling', 'walking')
    var profile = 'driving';

    var originEncoded = origin.map(coord => encodeURIComponent(coord)).join(',');
    var destinationEncoded = destination.map(coord => encodeURIComponent(coord)).join(',');

    // Construire l'URL de l'API Directions
    var apiUrl = `https://api.mapbox.com/directions/v5/mapbox/driving/${originEncoded};${destinationEncoded}?alternatives=true&banner_instructions=true&geometries=geojson&language=fr&overview=full&steps=true&access_token=pk.eyJ1Ijoib3R0b3ZvbiIsImEiOiJjbHQ0NmdrNDExZWQzMmtyejJ5Nng1cHM0In0.wZBjeEjUzeSRgu5Oc1xl8w`;

    // Construire l'URL de l'API Directions
    // var apiUrl = `https://api.mapbox.com/directions/v5/mapbox/${profile}/${origin[0]},${origin[1]};${destination[0]},${destination[1]}?access_token=${mapboxgl.accessToken}`;
    console.log(apiUrl);
    // Effectuer une requête GET à l'API Directions
    map.on('load', function() {
      // Effectuer une requête GET à l'API Directions
      fetch(apiUrl)
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();

        })

        .then(data => {
          //console.log(data);
          // Traiter les données de réponse (par exemple, afficher l'itinéraire sur la carte)
          var route = data.routes[0].geometry;

          // Convertir la route en GeoJSON valide
          var routeGeojson = {
            type: 'Feature',
            properties: {},
            geometry: route
          };



          // Dessiner l'itinéraire sur la carte
          // map.addLayer({
          //   id: 'route',
          //   type: 'line',
          //   source: {
          //     type: 'geojson',
          //     data: routeGeojson
          //   },
          //   layout: {
          //     'line-join': 'round',
          //     'line-cap': 'round'
          //   },
          //   paint: {
          //     'line-color': '#3d76ad',
          //     'line-width': 6
          //   }
          // });

          var infos_km = data.routes[0].legs;

          var distance = infos_km[0].distance / 1000;
          var duration = infos_km[0].duration / 60;

          var instructions = document.getElementById('instructions');


          //var details = data.routes[0].legs;
          //console.log(details);
          var tripInstructions = '';
          // var i = 0;
          // for (var step of details) {
          //   console.log(details);
          //   tripInstructions += `<p class="small">${step.steps[11].maneuver.instruction}</p>`;
          //   i = i + i;
          //   console.log(i);
          // }
          var details = data.routes[0].legs;

          console.log(details[0]);
          for (var i = 0; i < details[0].steps.length; i++) {

            // Afficher les détails de l'étape actuelle
            //    tripInstructions += `<p class="small"><b>${i+1}</b> - ${details[0].steps[i].maneuver.instruction}<br> ${(details[0].steps[i].distance/1000).toFixed(2)} km</p>`;
            console.log(tripInstructions); // Afficher la valeur de l'incrémentielle i
          }

          instructions.innerHTML = `<p class="mb-1"><strong>Distance : ${distance.toFixed(2)} km / ${Math.floor(duration)} min </strong></p>`
          //  instructions.innerHTML += `<hr/>`;
          instructions.innerHTML += `<div class=""> ${tripInstructions} </div>`;

          // <
          // ol > $ {
          //   tripInstructions
          // } < /ol>

        })
        .catch(error => {
          console.error('There was a problem with the fetch operation:', error);
        });



    });



    map.addControl(new mapboxgl.NavigationControl({

    }));
  });
</script>