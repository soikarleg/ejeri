<?php
$chemin = $_SERVER['DOCUMENT_ROOT'];
// include $chemin . '/myclass/flxxx/src/Base.php';


class Parametres
{

  /** @var string */
  private $secteur;
  private $data;
  // private $base;

  public function __construct($secteur, $data)
  {
    $this->secteur = $secteur;
    $this->data  = $data;
  }

  public function Tel($t = null)
  {
    $tel = wordwrap($t, 2, '.', true);
    return $tel;
  }


  public function valNom($strNom)
  {
    $strNom = preg_replace('/[^a-zA-Z àâäçéèêëîïôœùûüÿ\'-]/', '', $strNom); // Supprimer les caractères spéciaux
    $strNom = strtoupper($strNom);
    return $strNom;
  }

  public function valPrenom($strPrenom)
  {
    $strPrenom = preg_replace('/[^a-zA-Z àâäçéèêëîïôœùûüÿ\'-]/', '', $strPrenom); // Supprimer les caractères spéciaux
    $strPrenom = ucfirst($strPrenom);
    return $strPrenom;
  }

  public function valString($str)
  {
    $str = preg_replace('/[^a-zA-Z àâäçéèêëîïôœùûüÿ\'-]/', '', $str); // Supprimer les caractères spéciaux
    $str = ucfirst($str);
    return $str;
  }

  // !Revoir function en fonction de l'action : Ajout ou Modification chantier adresse et infos

  public function validationParametres($data)
  {
    $data_client = $data;
    $errors = array();
    $data_corrige = array();

    // Valider le champ civilite

    if (empty($data_client['statut'])) {
      $errors[] = "Le statut ne dois pas être vide.";
    } else {
      $civilite = $this->valString($data_client['statut']);
    }


    // Valider le champ nom
    $nom = $data_client['nom'];
    if (empty($nom)) {
      $errors[] = "Le nom ne doit pas être vide.";
    } else {
      $nom = $this->valNom($nom);
    }

    // Valider le champ prenom
    if (!empty($data_client['prenom'])) {
      $prenom = $this->valString($data_client['prenom']);
    }

    // Valider le champ adresse
    $adresse = $data_client['adresse'];
    if (empty($adresse)) {
      $errors[] = "L'adresse ne doit pas être vide.";
    } else {
      $adresse = preg_replace('/[^a-zA-Z0-9 àâäçéèêëîïôœùûüÿ\'-]/', '', $adresse); // Supprimer les caractères spéciaux
    }

    // Valider le champ ville
    $ville = $data_client['ville'];
    if (empty($ville)) {
      $errors[] = "La ville ne doit pas être vide.";
    } else {
      $ville = preg_replace('/[^a-zA-Z àâäçéèêëîïôœùûüÿ\'-]/', '', $ville); // Supprimer les caractères spéciaux
    }

    // Valider le champ cp
    $cp = $data_client['cp'];
    if (empty($cp)) {
      $errors[] = "Le code postal ne doit pas être vide.";
    } else {
      $cp = preg_replace('/[^0-9]/', '', $cp); // Supprimer les caractères non numériques
      if (strlen($cp) !== 5 || $cp < 1000 || $cp > 99999) {
        $errors[] = "Le code postal doit être composé de 5 chiffres entre 01000 et 99999.</>";
      }
    }

    // Valider le champ portable
    $portable = $data_client['portable'];
    if (!empty($portable)) {
      $portable = preg_replace('/[^0-9]/', '', $portable); // Supprimer les caractères non numériques

      if (strlen($portable) !== 10 || $portable < 600000000 || $portable > 799999999) {
        $errors[] = "Le numéro de portable doit être composé de 10 chiffres entre 0600000000 et 0799999999.</>";
      } else {
        $portable = $this->Tel($portable);
      }
    }

    // Valider le champ telephone
    $telephone = $data_client['telephone'];
    if (!empty($telephone)) {
      $telephone = preg_replace('/[^0-9]/', '', $telephone); // Supprimer les caractères non numériques

      if (strlen($telephone) !== 10 || ($telephone < 100000000 && $telephone > 999999999)) {
        $errors[] = "Le numéro de téléphone doit être composé de 10 chiffres entre 0100000000 et 9999999999.";
      } else {
        $telephone = $this->Tel($telephone);
      }
    }

    // Valider le champ email
    $email = $data_client['email'];
    if (!empty($email)) {
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse email n'est pas valide.";
      }
    }

    // Valider le champ type
    // $type = $data_client['type'];
    // if (empty($type)) {
    //   $errors[] = "Le type ne doit pas être vide.";
    // } else {
    //   $type = preg_replace('/[^a-zA-Z -]/', '', $type); // Supprimer les caractères spéciaux
    // }

    // // Valider le champ categorie
    // $categorie = $data_client['categorie'];
    // if (!empty($categorie)) {

    //   $categorie = preg_replace('/[^a-zA-Z -]/', '', $categorie); // Supprimer les caractères spéciaux
    // }

    // // Valider le champ connu
    // $connu = $data_client['connu'];
    // if (!empty($connu)) {

    //   $connu = preg_replace('/[^a-zA-Z -]/', '', $connu); // Supprimer les caractères spéciaux
    // }

    // Valider le champ commercial
    // $commercial = $data_client['commercial'];
    // if (empty($commercial)) {
    //   $errors[] = "Le nom du commercial ne doit pas être vide.";
    // } else {
    //   $commercial = preg_replace('/[^a-zA-Z0-9 -]/', '', $commercial); // Supprimer les caractères spéciaux
    // }

    // Valider le champ tournee
    // $tournee = $data_client['tournee'];
    // if (empty($tournee)) {
    //   $errors[] = "Le nom de l'intervenant ne doit pas être vide.";
    // } else {
    //   $tournee = preg_replace('/[^a-zA-Z0-9 -]/', '', $tournee); // Supprimer les caractères spéciaux
    // }

    if (empty($errors)) {
      $data_corrige['civilite'] = $civilite;
      $data_corrige['nom'] = $nom;
      $data_corrige['prenom'] = $prenom;
      $data_corrige['adresse'] = $adresse;
      $data_corrige['ville'] = $ville;
      $data_corrige['cp'] = $cp;
      $data_corrige['portable'] = $portable;
      $data_corrige['telephone'] = $telephone;
      $data_corrige['email'] = $email;
      // $data_corrige['type'] = $type;
      // $data_corrige['commercial'] = $commercial;
      // $data_corrige['tournee'] = $tournee;

      return
        array(
          'success' => true,
          'data' => $data_corrige
        );
    } else {
      return
        array(
          'success' => false,
          'errors' => $errors
        );
    }
  }

  public function ajoutClient($data)
  {
    $message = array();
    $data_client = $data;
    $message = $this->validationParametres($data_client);
    if ($message) {
      foreach ($message as $e) {
        return $e;
      }
    } else {
      foreach ($data_client as $k => $v) {

        ${$k} = $v;
        //echo '$' . $k . '= ' . $v . '<br class=""> ';
        echo strtoupper($nom);
        echo ucfirst($prenom);
        $message[] = $v;
      }
    }
    return $message;
  }
}
