<!DOCTYPE html>
<html>

<head>
  <script src='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.js?<?= time() ?>'></script>
  <link href='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.css?<?= time() ?>' rel='stylesheet' />
  <?php
  //  error_reporting(\E_ALL);
  // ini_set('display_errors', 'stdout');
  //$addressA = $chantier_adresse . ' ' . $chantier_cp . ' ' . $chantier_ville;
  //$addressA = $chantier['chantier_adresse'] . ' ' . $chantier['chantier_cp'] . ' ' . $chantier['chantier_ville'];
  $address = $correspondance_adresse . ' ' . $correspondance_cp . ' ' . $correspondance_ville;
  $address_bulle = $correspondance_adresse . '</br> ' . $correspondance_cp . ' ' . $correspondance_ville;

  function getCoordinates($address)
  {

    $url = 'https://api-adresse.data.gouv.fr/search/?q=' . urlencode($address);

    $response = file_get_contents($url);

    if ($response === false) {
      return false;
    }

    $data = json_decode($response, true);

    if (empty($data['features'])) {
      return false;
    }
    $coordinates = $data['features'][0]['geometry']['coordinates'];
    return [
      'latitude' => $coordinates[1],
      'longitude' => $coordinates[0]
    ];
  }
  // Exemple d'utilisation
  //$address = "3, place de l'eglise, 45740 Lailly en Val";
  //$address = $_POST['adress'];
  $adresse_cli = getCoordinates($address);
  //prettyc($adresse_cli);
  if ($adresse_cli !== false) {
    $lonac = $adresse_cli['longitude'];
    $latac = $adresse_cli['latitude'];
    //  echo "Latitude: " . $adresse_cli['latitude'] . " - ";
    //echo "Longitude: " . $adresse_cli['longitude'] . "<br>";
  } else {
    echo "<p class='text-danger text-bold'>Cette adresse n'existe pas.</p>";
  }
  $adresse_secteur = getCoordinates('3, place de eglise, 45740 Lailly en Val');
  if ($adresse_secteur !== false) {
    $lonas = $adresse_secteur['longitude'];
    $latas = $adresse_secteur['latitude'];
    //  echo "Latitude: " . $adresse_secteur['latitude'] . " - ";
    // echo "Longitude: " . $adresse_secteur['longitude'] . "<br>";
  } else {
    echo "Adresse non trouvée ou erreur lors de la requête.";
  }
  ?>
  <style>
    #map,
    #mapo {
      padding: 1px;
      width: 100%;
      height: 550px;
      /**/
      background-color: #202e44;
      border-radius: 6px
    }

    .mapboxgl-popup {
      max-width: 500px;
      font: 12px/20px 'Kanit';
    }

    #instructions {
      position: relative;
      margin: -10px;
      width: 30%;
      top: 0;
      bottom: 20%;
      padding: 8px;
      background-color: #202e44;
      overflow-y: scroll;
      font-family: 'Kanit';
      border-radius: 6px;
      font-size: 14px;
      z-index: 1;
    }
  </style>
  <!--  class="scroll-s" -->

  <div>
    <ul class="nav justify-content-center">
      <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="#">Active</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" aria-disabled="true">Disabled</a>
      </li>
    </ul>
  </div>

  <div id='mapo'>
    <!-- -->
    <div id="instructions">
      <?= $address_bulle ?>
    </div>
  </div>
  <script>
    function formatDuration(minutes) {
      const hours = Math.floor(minutes / 60);
      const remainingMinutes = Math.floor(minutes % 60);
      return `${hours}h ${remainingMinutes.toString().padStart(2, '0')} min`;
    }
    $(function() {
      mapboxgl.accessToken = 'pk.eyJ1Ijoib3R0b3ZvbiIsImEiOiJjbHQ0NmJsM20xb290MmpvNWI3djZhcHN0In0.z-Jcud1SqKW1gFqMNrLEWw';

      var map = new mapboxgl.Map({
        container: 'mapo',
        center: [<?= $lonac ?>, <?= $latac ?>],
        style: 'mapbox://styles/mapbox/satellite-streets-v12',
        zoom: 17
      });
      new mapboxgl.Marker({color: '#ff0000', rotation: 45}).setLngLat([<?= $lonac ?>, <?= $latac ?>]).addTo(map);
      new mapboxgl.Marker({color: '#ff0000', rotation: 45}).setLngLat([<?= $lonas ?>, <?= $latas ?>]).addTo(map);
      var origin = [<?= $lonas ?>, <?= $latas ?>];
      var destination = [<?= $lonac ?>, <?= $latac ?>];
      var profile = 'driving';
      var apiUrl = `https://api.mapbox.com/directions/v5/mapbox/${profile}/${origin[0]},${origin[1]};${destination[0]},${destination[1]}?geometries=geojson&access_token=${mapboxgl.accessToken}`;
      map.on('load', function() {
        fetch(apiUrl)
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then(data => {
            var route = data.routes[0].geometry;
            var routeGeojson = {
              type: 'Feature',
              properties: {},
              geometry: route
            };
            // Dessiner l'itinéraire sur la carte
            map.addLayer({
              id: 'route',
              type: 'line',
              source: {
                type: 'geojson',
                data: routeGeojson
              },
              layout: {
                'line-join': 'round',
                'line-cap': 'round'
              },
              paint: {
                'line-color': '#ff0000',
                'line-width': 3
              }
            });
            // Ajuster la vue de la carte pour englober l'itinéraire
            var coordinates = route.coordinates;
            var bounds = coordinates.reduce(function(bounds, coord) {
              return bounds.extend(coord);
            }, new mapboxgl.LngLatBounds(coordinates[0], coordinates[0]));
            map.fitBounds(bounds, {
              padding: {
                top: 80,
                bottom: 80,
                left: 80,
                right: 80
              }
            });
            var distance = data.routes[0].legs[0].distance / 1000;

            var duration = data.routes[0].legs[0].duration / 60;
            //var duration = data.routes[0].legs[0].duration;
            var formattedDuration = formatDuration(duration);

            var instructions = document.getElementById('instructions');
            var tripInstructions = '';
            data.routes[0].legs[0].steps.forEach((step, index) => {
              tripInstructions += `<p class="small"><b>${index + 1}</b> - ${step.maneuver.instruction}<br> ${(step.distance / 1000).toFixed(2)} km</p>`;
            });
            // ${Math.floor(duration)}
            instructions.innerHTML = `<p class="mb-1">Trajet de ${distance.toFixed(2)} km pour  ${formattedDuration} </p>`;
            instructions.innerHTML += `<div class=""> ${tripInstructions} </div>`;
          })
          .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
          });
      });
      map.addControl(new mapboxgl.NavigationControl({}));
    });
  </script>
</head>