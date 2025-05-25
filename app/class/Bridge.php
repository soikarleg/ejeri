<?php
class Bridge
{
  private $secteur;
  private $iduser;
  private $client_id;
  private $client_secret;
  public function __construct()
  {
    //$this->secteur = $secteur;
    // $this->iduser = $iduser;
  }

  public function getData($str)
  {
    if ($str !== null) {
      $data = json_decode($str, true);
      if ($data === null) {
        return 'Erreur de décoding JSON';
      } else {
        return $data;
      }
    } else {
      return 'La chaîne JSON est null';
    }
  }

  // Cette fonction insère un nouvel enregistrement dans la table "bridge" en utilisant un UUID et un secteur spécifiés. 
  // Elle crée une connexion à la base de données, construit une requête SQL d'insertion, 
  // puis exécute cette requête pour ajouter les données correspondantes.

  public function insertUUID($uuid, $secteur)
  {
    //$secteur = $this->secteur;
    $conn = new connBase();
    $insertsql = "INSERT INTO `bridge`(`idcompte`, `user_uuid`) VALUES ('$secteur','$uuid')";
    $conn->handleRow($insertsql);
    return true;
  }

  public function getUUID($secteur)
  {
    //$secteur = $this->secteur;
    $conn = new connBase();
    $selectsql = "SELECT `user_uuid` FROM `bridge` WHERE idcompte='$secteur'";
    $uuid_exist = $conn->oneRow($selectsql);
    $uuid = $uuid_exist['user_uuid'];
    if ($uuid) {
      return $uuid;
    } else {
      return false;
    }
  }

  // Cette fonction permet de créer un utilisateur en envoyant une requête POST à l'API Bridge.
  // Elle prend en paramètres l'ID et le secret du client, construit les données nécessaires, et gère les erreurs potentielles liées à cURL.

  public function userCreation($client_id, $client_secret)
  {
    $url = 'https://api.bridgeapi.io/v2/users';
    $data = array(
      'external_user_id' => $this->secteur
    );
    $jsonData = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Bridge-Version: 2021-06-01',
      'Content-Type: application/json',
      'Client-Id:' . $client_id,
      'Client-Secret: ' . $client_secret
    ));
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
      echo '<span class="text-danger text-bold l-9">** userCreation ** erreur cURL :  </span>  ' . curl_error($ch) . '</br>';
    }
    curl_close($ch);;
    return $response;
  }

  public function userAuthentification($user_uuid, $client_id, $client_secret)
  {
    $url = 'https://api.bridgeapi.io/v2/authenticate';
    $data = array(
      'external_user_id' => $user_uuid,
    );
    $jsonData = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Bridge-Version: 2021-06-01',
      'Content-Type: application/json',
      'Client-Id:' . $client_id,
      'Client-Secret: ' . $client_secret
    ));
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
      echo '<span class="text-danger text-bold">** userAuthentification ** erreur cURL :  </span>  ' . curl_error($ch) . '</br>';
    }
    curl_close($ch);
    return $response;
  }

  public function listUsers($client_id, $client_secret)
  {

    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.bridgeapi.io/v2/users?limit=null",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => [
        "Bridge-Version: 2021-06-01",
        "Client-Id: $client_id",
        "Client-Secret: $client_secret",
        "accept: application/json"
      ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      return "cURL Error #:" . $err;
    } else {
      return $response;
    }
  }
  public function getUser($uuid, $client_id, $client_secret)
  {
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.bridgeapi.io/v2/users/$uuid",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => [
        "Bridge-Version: 2021-06-01",
        "Client-Id: $client_id",
        "Client-Secret: $client_secret",
        "accept: application/json"
      ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      return "cURL Error #:" . $err;
    }
    return $response;
  }
  public function setItem($iduser, $tsat, $client_id, $client_secret)
  {
    //$iduser = $this->iduser;
    $conn = new connBase();
    $email = $conn->askIduser($iduser, 'email');
    $email = $email['email'];
    $url = 'https://api.bridgeapi.io/v2/connect/items/add';
    $data = array(
      'country' => 'fr',
      'prefill_email' => $email
    );
    // Convertir les données en JSON
    $jsonData = json_encode($data);
    // Options cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Bridge-Version: 2021-06-01',
      'Client-Id: ' . $client_id,
      'Client-Secret: ' . $client_secret,
      'Authorization: Bearer ' . $tsat,
      'Content-Type: application/json'
    ));
    // Exécuter la requête cURL
    $response = curl_exec($ch);
    // Vérifier les erreurs
    if (curl_errno($ch)) {
      return 'Erreur cURL : ' . curl_error($ch);
    }
    // Fermer la session cURL
    curl_close($ch);
    // Traiter la réponse
    return $response;
  }
  public function getItems($auth, $client_id, $client_secret)
  {
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.bridgeapi.io/v2/items",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $auth",
        "Bridge-Version: 2021-06-01",
        "Client-Id: $client_id",
        "Client-Secret: $client_secret",
        "accept: application/json"
      ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      return "cURL Error #:" . $err;
    } else {
      return $response;
    }
  }
  public function refreshItem($auth, $iditem, $client_id, $client_secret)
  {
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.bridgeapi.io/v2/items/$iditem/refresh",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_HTTPHEADER => [
        "Authorization: $auth",
        "Bridge-Version: 2021-06-01",
        "Client-Id: $client_id",
        "Client-Secret: $client_secret",
        "accept: text/plain"
      ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      return "cURL Error #:" . $err;
    } else {
      return $response;
    }
  }
  public function getBank($idbank, $client_id, $client_secret)
  {
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.bridgeapi.io/v2/banks/$idbank",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => [
        "Bridge-Version: 2021-06-01",
        "Client-Id: $client_id",
        "Client-Secret: $client_secret",
        "accept: application/json"
      ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      return "cURL Error #:" . $err;
    } else {
      return $response;
    }
  }
  public function getAccounts($auth, $iditem, $client_id, $client_secret)
  {
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.bridgeapi.io/v2/accounts?item_id=$iditem&limit=null",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $auth",
        "Bridge-Version: 2021-06-01",
        "Client-Id: $client_id",
        "Client-Secret: $client_secret",
        "accept: application/json"
      ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      return "cURL Error #:" . $err;
    } else {
      return $response;
    }
  }
  public function getOneAccount($auth, $idacc, $client_id, $client_secret)
  {
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.bridgeapi.io/v2/accounts/$idacc",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $auth",
        "Bridge-Version: 2021-06-01",
        "Client-Id: $client_id",
        "Client-Secret: $client_secret",
        "accept: application/json"
      ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      return "cURL Error #:" . $err;
    } else {
      return $response;
    }
  }
  public function getListAccount($auth, $client_id, $client_secret)
  {
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.bridgeapi.io/v2/accounts?limit=500",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $auth",
        "Bridge-Version: 2021-06-01",
        "Client-Id: $client_id",
        "Client-Secret: $client_secret",
        "accept: application/json"
      ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      return "cURL Error #:" . $err;
    } else {
      return $response;
    }
  }
  public function getTransactions($auth, $idacc, $client_id, $client_secret)
  {
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.bridgeapi.io/v2/accounts/$idacc/transactions?limit=null",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $auth",
        "Bridge-Version: 2021-06-01",
        "Client-Id: $client_id",
        "Client-Secret: $client_secret",
        "accept: application/json"
      ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      return "cURL Error #:" . $err;
    } else {
      return $response;
    }
  }
  public function delItem($auth, $iditem, $client_id, $client_secret)
  {
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.bridgeapi.io/v2/items/$iditem",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "DELETE",
      CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $auth",
        "Bridge-Version: 2021-06-01",
        "Client-Id: $client_id",
        "Client-Secret: $client_secret",
        "accept: text/plain"
      ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }
  }
  public function getRecon($auth, $iditem, $client_id, $client_secret)
  {
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.bridgeapi.io/v2/connect/items/sync?item_id=$iditem",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $auth",
        "Bridge-Version: 2021-06-01",
        "Client-Id: $client_id",
        "Client-Secret: $client_secret",
        "accept: application/json"
      ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      return "cURL Error #:" . $err;
    } else {
      return $response;
    }
  }

  public function getWebhookItem($secteur, $item_id)
  {
    $conn = new connBase();
    $webhooks_select = "SELECT * FROM webhooks WHERE idcompte='$secteur' and item_id = '$item_id' order by id desc limit 1";
    $uuid_exist = $conn->allRow($webhooks_select);

    return $uuid_exist;
  }
}
