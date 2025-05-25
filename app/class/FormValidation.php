<?php
class FormValidation
{
  private $data;
  public function __construct($data)
  {
    $this->data  = $data;
  }
  public function Tel($t = null)
  {
    $tel = wordwrap($t, 2, '.', true);
    return $tel;
  }
  // todo Gestion apostrophes pour nom, prenom, adresse et ville. \' enlevé le 23082023 du preg_replace
  public function valNom($strNom)
  {
    $strNom = preg_replace('/[^a-zA-Z àâäçéèêëîïôœùûüÿÀÂÄÇÉÈÊËÎÏÔŒÙÛÜŸ\'-]/', '', $strNom); // Supprimer les caractères spéciaux
    $strNom = strtoupper($strNom);
    $strNom = addslashes($strNom);
    return $strNom;
  }
  public function valPrenom($strPrenom)
  {
    $strPrenom = preg_replace('/[^a-zA-Z àâäçéèêëîïôœùûüÿÀÂÄÇÉÈÊËÎÏÔŒÙÛÜŸ\'-]/', '', $strPrenom); // Supprimer les caractères spéciaux
    $strPrenom = ucfirst($strPrenom);
    $strPrenom = addslashes($strPrenom);
    return $strPrenom;
  }
  public function valString($str)
  {
    $str = preg_replace('/[^a-zA-Z àâäçéèêëîïôœùûüÿÀÂÄÇÉÈÊËÎÏÔŒÙÛÜŸ.\'-]/', '', $str); // Supprimer les caractères spéciaux
    $str = addslashes($str);
    return $str;
  }
  public function valFull($str)
  {
    $str = preg_replace('/[^a-zA-Z0-9 àâäçéèêëîïôœùûüÿÀÂÄÇÉÈÊËÎÏÔŒÙÛÜŸ.\/\'\-_]/', '', $str); // Supprimer les caractères spéciaux
    $str = addslashes($str);
    return $str;
  }
  public function valFullsansSlashes($str)
  {
    $str = preg_replace('/[^a-zA-Z0-9 àâäçéèêëîïôœùûüÿÀÂÄÇÉÈÊËÎÏÔŒÙÛÜŸ.\/\'\-_]/', '', $str); // Supprimer les caractères spéciaux
    //$str = addslashes($str);
    return $str;
  }
  public function valAdresse($str)
  {
    $str = preg_replace('/[^a-zA-Z0-9 àâäçéèêëîïôœùûüÿÀÂÄÇÉÈÊËÎÏÔŒÙÛÜŸ.\'-]/', '', $str); // Supprimer les caractères spéciaux
    $str = addslashes($str);
    return $str;
  }
  public function valIntStr($str)
  {
    $str = preg_replace('/[^0-9A-Z]/', '', $str); // Supprimer les caractères spéciaux
    return $str;
  }
  public function valInt($str)
  {
    $str = preg_replace('/[^0-9]/', '', $str); // Supprimer les caractères spéciaux
    return $str;
  }
  public function valFloat($str)
  {
    $str = preg_replace('/[^0-9.]/', '', $str); // Supprimer les caractères spéciaux
    return $str;
  }
  public function valIntPoint($str)
  {
    $str = preg_replace('/[^0-9Z.]/', '', $str); // Supprimer les caractères spéciaux
    return $str;
  }
  public function validationCompte($data)
  {
    $data_client = $data;
    $errors = array();
    $data_corrige = array();
    $sap = $data_client['sap'];
    $agre = $this->valIntStr($data_client['agre']);
    // Valider le champ SIRET
    if (empty($data_client['siret'])) {
      $errors[] = "La SIRET ne peux pas être vide.";
    } else {
      $siret = $this->valInt($data_client['siret']);
      if (strlen($siret) !== 9) {
        $errors[] = "Le SIRET doit comporter 9 chiffres.</>";
      }
    }
    // Valider le champ nom
    if (empty($data_client['nom'])) {
      $errors[] = "Le nom ne peux pas être vide.";
    } else {
      $nom = $this->valNom($data_client['nom']);
    }
    // Valider le champ denomination
    $denomination = $data_client['denomination_input'];
    $denomination = empty($data_client['denomination_input']) ? "" : $denomination;
    //$errors[] = "La denomination ne peux pas être vide.";
    $denomination = $this->valNom($denomination);
    // Valider le champ NIC
    if (empty($data_client['nic_input'])) {
      $errors[] = "Le NIC ne peux pas etre vide.";
    } else {
      $nic = $this->valInt($data_client['nic_input']); // Supprimer les caractères spéciaux
    }
    // Valider le champ NAF
    if (empty($data_client['naf_input'])) {
      $errors[] = "Le NAF ne peux pas etre vide.";
    } else {
      $naf = $this->valIntPoint($data_client['naf_input']); // Supprimer les caractères spéciaux
    }
    // Valider le champ statut
    if (empty($data_client['cat_juridique_input'])) {
      $errors[] = "Le statut ne peux pas etre vide.";
    } else {
      $juridique = $this->valString($data_client['cat_juridique_input']); // Supprimer les caractères spéciaux
    }
    // Valider le champ adresse
    if (empty($data_client['adresse'])) {
      $errors[] = "L'adresse ne peux pas être vide.";
    } else {
      $adresse = $this->valAdresse($data_client['adresse']); // Supprimer les caractères spéciaux
    }
    // Valider le champ ville
    $ville = $data_client['ville'];
    if (empty($ville)) {
      $errors[] = "La ville ne peux pas être vide.";
    } else {
      $ville = preg_replace('/[^a-zA-Z àâäçéèêëîïôœùûüÿÀÂÄÇÉÈÊËÎÏÔŒÙÛÜŸ\'-]/', '', $ville); // Supprimer les caractères spéciaux
    }
    // Valider le champ cp
    $cp = $data_client['cp'];
    if (empty($cp)) {
      $errors[] = "Le code postal ne peux pas être vide.";
    } else {
      $cp = preg_replace('/[^0-9]/', '', $cp); // Supprimer les caractères non numériques
      if (strlen($cp) !== 5 || $cp < 1000 || $cp > 99999) {
        $errors[] = "Le code postal doit être composé de 5 chiffres entre 01000 et 99999.</>";
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
    } else {
      $errors[] = "Le téléphone ne peux pas être vide.";
    }
    // Valider le champ email
    $email = $data_client['email'];
    if (!empty($email)) {
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse email n'est pas valide.";
      }
    } else {
      $errors[] = "L'adresse mail ne peux pas être vide.";
    }
    if (empty($errors)) {
      $data_corrige['siret'] = $siret;
      $data_corrige['nom'] = $nom;
      $data_corrige['denomination'] = $denomination;
      $data_corrige['adresse'] = $adresse;
      $data_corrige['ville'] = $ville;
      $data_corrige['cp'] = $cp;
      $data_corrige['nic'] = $nic;
      $data_corrige['naf'] = $naf;
      $data_corrige['telephone'] = $telephone;
      $data_corrige['email'] = $email;
      $data_corrige['juridique'] = $juridique;
      $data_corrige['agre'] = $agre;
      $data_corrige['sap'] = $sap;
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
  public function validationReferent($data)
  {
    $data_client = $data;
    $errors = array();
    $data_corrige = array();
    // Civilite
    if (empty($data_client['civilite'])) {
      $errors[] = "La civilité ne peux pas être vide.";
    } else {
      $civilite = $this->valString($data_client['civilite']);
    }
    // Valider le champ nom
    if (empty($data_client['nom'])) {
      $errors[] = "Le nom ne peux pas être vide.";
    } else {
      $nom = $this->valNom($data_client['nom']);
    }
    // Valider le champ prenom
    if (empty($data_client['prenom'])) {
      $errors[] = "Le prénom ne peux pas être vide.";
    } else {
      $prenom = $this->valPrenom($data_client['prenom']);
    }
    // Valider le champ statut
    if (empty($data_client['statut'])) {
      $errors[] = "Le statut ne peux pas être vide.";
    } else {
      $statut = $this->valPrenom($data_client['statut']);
    }
    // Valider le champ adresse
    if (empty($data_client['adresse'])) {
      $errors[] = "L'adresse ne peux pas être vide.";
    } else {
      $adresse = $this->valAdresse($data_client['adresse']); // Supprimer les caractères spéciaux
    }
    // Valider le champ ville
    $ville = $data_client['ville'];
    if (empty($ville)) {
      $errors[] = "La ville ne peux pas être vide.";
    } else {
      $ville = preg_replace('/[^a-zA-Z àâäçéèêëîïôœùûüÿÀÂÄÇÉÈÊËÎÏÔŒÙÛÜŸ\'-]/', '', $ville); // Supprimer les caractères spéciaux
    }
    // Valider le champ cp
    $cp = $data_client['cp'];
    if (empty($cp)) {
      $errors[] = "Le code postal ne peux pas être vide.";
    } else {
      $cp = preg_replace('/[^0-9]/', '', $cp); // Supprimer les caractères non numériques
      if (strlen($cp) !== 5 || $cp < 1000 || $cp > 99999) {
        $errors[] = "Le code postal doit être composé de 5 chiffres entre 01000 et 99999.</>";
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
    } else {
      $errors[] = "Le téléphone ne peux pas être vide.";
    }
    // Valider le champ email
    $email = $data_client['email'];
    if (!empty($email)) {
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse email n'est pas valide.";
      }
    } else {
      $errors[] = "L'adresse mail ne peux pas être vide.";
    }
    if (empty($errors)) {
      $data_corrige['civilite'] = $civilite;
      $data_corrige['nom'] = $nom;
      $data_corrige['prenom'] = $prenom;
      $data_corrige['adresse'] = $adresse;
      $data_corrige['ville'] = $ville;
      $data_corrige['cp'] = $cp;
      $data_corrige['statut'] = $statut;
      $data_corrige['telephone'] = $telephone;
      $data_corrige['email'] = $email;
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
  public function validationIntervenants($data)
  {
    /**
     * civilite
     * nom
     * prenom
     
     * adresse
     * ville
     * portable
     * email
     * statut
     * 
     */
    $data_client = $data;
    $errors = array();
    $data_corrige = array();
    // Valider le champ civilite
    if (empty($data_client['civilite'])) {
      $errors[] = "La civilité ne peux pas être vide.";
    } else {
      $civilite = $this->valString($data_client['civilite']);
    }
    // Valider le champ nom
    if (empty($data_client['nom'])) {
      $errors[] = "Le nom ne peux pas être vide.";
    } else {
      $nom = $this->valNom($data_client['nom']);
    }
    // Valider le champ nom
    if (empty($data_client['prenom'])) {
      $errors[] = "Le prénom ne peux pas être vide.";
    } else {
      $prenom = $this->valPrenom($data_client['prenom']);
    }
    // Valider le champ adresse
    if (empty($data_client['adresse'])) {
      $errors[] = "L'adresse ne peux pas être vide.";
    } else {
      $adresse = $this->valAdresse($data_client['adresse']); // Supprimer les caractères spéciaux
    }
    // Valider le champ ville
    $ville = $data_client['ville'];
    if (empty($ville)) {
      $errors[] = "La ville ne peux pas être vide.";
    } else {
      $ville = preg_replace('/[^a-zA-Z àâäçéèêëîïôœùûüÿÀÂÄÇÉÈÊËÎÏÔŒÙÛÜŸ\'-]/', '', $ville); // Supprimer les caractères spéciaux
    }
    // Valider le champ cp
    $cp = $data_client['cp'];
    if (empty($cp)) {
      $errors[] = "Le code postal ne peux pas être vide.";
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
    } else {
      $errors[] = "Le portable ne peux pas être vide";
    }
    // Valider le champ email
    $email = $data_client['email'];
    if (!empty($email)) {
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse email n'est pas valide.";
      }
    } else {
      $errors[] = "L'adresse mail ne peux pas être vide.";
    }
    // Valider le champ adresse
    if (empty($data_client['statut'])) {
      $errors[] = "Le statut ne peux pas être vide.";
    } else {
      $statut = $this->valAdresse($data_client['statut']); // Supprimer les caractères spéciaux
    }
    if (empty($errors)) {
      $data_corrige['civilite'] = $civilite;
      $data_corrige['nom'] = $nom;
      $data_corrige['prenom'] = $prenom;
      $data_corrige['adresse'] = $adresse;
      $data_corrige['ville'] = $ville;
      $data_corrige['cp'] = $cp;
      $data_corrige['portable'] = $portable;
      $data_corrige['email'] = $email;
      $data_corrige['statut'] = $statut;
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
  public function validationContacts($data)
  {
    $data_client = $data;
    $errors = array();
    $data_corrige = array();
    // Valider le champ civilite
    if (empty($data_client['civilite'])) {
      $errors[] = "La civilité ne doit pas être vide.";
    } else {
      $civilite = $this->valString($data_client['civilite']);
    }
    // Valider le champ nom
    $nom = $data_client['nom'];
    if (empty($nom)) {
      $errors[] = "Le nom ne peux pas être vide.";
    } else {
      $nom = $this->valNom($nom);
    }
    // Valider le champ prenom
    if (!empty($data_client['prenom'])) {
      $prenom = $this->valString($data_client['prenom']);
      $prenom = ucfirst($prenom);
    }
    // Valider le champ adresse
    $adresse = $data_client['adresse'];
    if (empty($adresse)) {
      $errors[] = "L'adresse ne doit pas être vide.";
    } else {
      $adresse = preg_replace('/[^a-zA-Z0-9 àâäçéèêëîïôœùûüÿÀÂÄÇÉÈÊËÎÏÔŒÙÛÜŸ\'-]/', '', $adresse); // Supprimer les caractères spéciaux
      //$adresse = addslashes($adresse);
    }
    // Valider le champ ville
    $ville = $data_client['ville'];
    if (empty($ville)) {
      $errors[] = "La ville ne doit pas être vide.";
    } else {
      $ville = preg_replace('/[^a-zA-Z àâäçéèêëîïôœùûüÿÀÂÄÇÉÈÊËÎÏÔŒÙÛÜŸ\'-]/', '', $ville); // Supprimer les caractères spéciaux
     // $ville = addslashes($ville);
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
    // // Valider le champ commercial
    // $commercial = $data_client['commercial'];
    // $commercial = preg_replace('/[^a-zA-Z0-9 -]/', '', $commercial);

    // // Valider le champ tournee
    // $tournee = $data_client['tournee'];
    // $tournee = preg_replace('/[^a-zA-Z0-9 -]/', '', $tournee);

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
  public function validationChantier($data)
  {
    $data_client = $data;
    $errors = array();
    $data_corrige = array();
    // Valider idcli
    $idcli = $this->valInt($data_client['idcli']);
    // Valider le champ civilite
    if (empty($data_client['civilite'])) {
      $errors[] = "La civilité ne doit pas être vide.";
    } else {
      $civilite = $this->valString($data_client['civilite']);
    }
    // Valider le champ nom
    $nom = $data_client['nom'];
    if (empty($nom)) {
      $errors[] = "Le nom ne peux pas être vide.";
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
      $adresse = preg_replace('/[^a-zA-Z0-9 àâäçéèêëîïôœùûüÿÀÂÄÇÉÈÊËÎÏÔŒÙÛÜŸ\'-]/', '', $adresse); // Supprimer les caractères spéciaux
    }
    // Valider le champ ville
    $ville = $data_client['ville'];
    if (empty($ville)) {
      $errors[] = "La ville ne doit pas être vide.";
    } else {
      $ville = preg_replace('/[^a-zA-Z àâäçéèêëîïôœùûüÿÀÂÄÇÉÈÊËÎÏÔŒÙÛÜŸ\'-]/', '', $ville); // Supprimer les caractères spéciaux
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
    if (empty($errors)) {
      $data_corrige['civilite'] = $civilite;
      $data_corrige['nom'] = $nom;
      $data_corrige['prenom'] = $prenom;
      $data_corrige['adresse'] = $adresse;
      $data_corrige['ville'] = $ville;
      $data_corrige['cp'] = $cp;
      $data_corrige['idcli'] = $idcli;
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
  public function validationCorrespondance($data)
  {
    $data_client = $data;
    $errors = array();
    $data_corrige = array();
    $idcli = $this->valInt($data_client['idcli']);
    // Valider le champ mention
    $mention = $this->valString($data_client['mention']);
    $mention = ucwords($mention);
    // Valider le champ adresse
    $adresse = $data_client['adresse'];
    if (empty($adresse)) {
      $errors[] = "L'adresse ne doit pas être vide.";
    } else {
      $adresse = preg_replace('/[^a-zA-Z0-9 àâäçéèêëîïôœùûüÿÀÂÄÇÉÈÊËÎÏÔŒÙÛÜŸ\'-]/', '', $adresse); // Supprimer les caractères spéciaux
    }
    // Valider le champ ville
    $ville = $data_client['ville'];
    if (empty($ville)) {
      $errors[] = "La ville ne doit pas être vide.";
    } else {
      $ville = preg_replace('/[^a-zA-Z àâäçéèêëîïôœùûüÿÀÂÄÇÉÈÊËÎÏÔŒÙÛÜŸ\'-]/', '', $ville); // Supprimer les caractères spéciaux
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
    if (empty($errors)) {
      $data_corrige['mention'] = $mention;
      $data_corrige['idcli'] = $idcli;
      $data_corrige['adresse'] = $adresse;
      $data_corrige['ville'] = $ville;
      $data_corrige['cp'] = $cp;
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
  public function validationFactures($data)
  {
  }
  public function validationInfos($data)
  {
    $data_client = $data;
    $errors = array();
    $data_corrige = array();
    $idcli = $this->valInt($data_client['idcli']);
    // Valider le champ categorie
    if (empty($data_client['categorie'])) {
      $errors[] = "La catégorie ne peux pas être vide.";
    } else {
      $categorie = $this->valString($data_client['categorie']);
    }
    // Valider le champ type
    if (empty($data_client['type'])) {
      $errors[] = "Le type de chantier ne peux pas être vide.";
    } else {
      $type = $this->valString($data_client['type']);
    }
    // Valider le champ connu
    if (empty($data_client['connu'])) {
      $errors[] = "Le biais de connaissance ne peux pas être vide.";
    } else {
      $connu = $this->valString($data_client['connu']);
    }
    // Valider le champ commercial
    $commercial = $data_client['commercial'];
    if (empty($commercial)) {
      $errors[] = "Le nom du commercial ne peux pas être vide.";
    } else {
      $commercial = preg_replace('/[^a-zA-Z0-9 -]/', '', $commercial); // Supprimer les caractères spéciaux
    }
    // Valider le champ tournee
    $tournee = $data_client['tournee'];
    if (empty($tournee)) {
      $errors[] = "Le nom de l'intervenant ne peux pas être vide.";
    } else {
      $tournee = preg_replace('/[^a-zA-Z0-9 -]/', '', $tournee); // Supprimer les caractères spéciaux
    }
    if (empty($errors)) {
      $data_corrige['categorie'] = $categorie;
      $data_corrige['type'] = $type;
      $data_corrige['idcli'] = $idcli;
      $data_corrige['connu'] = $connu;
      $data_corrige['commercial'] = $commercial;
      $data_corrige['tournee'] = $tournee;
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
  public function validationTelemail($data)
  {
    $data_client = $data;
    $errors = array();
    $data_corrige = array();
    $idcli = $data_client['idcli'];
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
    if (empty($errors)) {
      $data_corrige['portable'] = $portable;
      $data_corrige['telephone'] = $telephone;
      $data_corrige['email'] = $email;
      $data_corrige['idcli'] = $idcli;
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
  public function validationNotes($data)
  {
    $data_client = $data;
    $errors = array();
    $data_corrige = array();
    $idcli = $data_client['idcli'];
    $note = $data_client['note'];
    if (empty($note)) {
      $errors[] = "La note ne peux pas être vide.";
    } else {
      $note = preg_replace('/[^a-zA-Z0-9 àâäçéèêëîïôœùûüÿÀÂÄÇÉÈÊËÎÏÔŒÙÛÜŸ.,!?\'-]/', '', $note);
    }
    if (empty($errors)) {
      $data_corrige['note'] = $note;
      $data_corrige['idcli'] = $idcli;
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
}
