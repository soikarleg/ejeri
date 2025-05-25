<?php

use MaGesquo;

class Users extends MaGesquo
{
  private $magesquo;
  private $idcompte;
  private $iduser;
  private $valid;
  public function __construct($iduser)
  {
    $this->valid = new DataValidator();
    $this->magesquo = new MaGesquo($this->idcompte);
    $this->idcompte = MaGesquo::getIdcompte();
    $this->iduser = $iduser;
  }
  /**
   * getIduser
   *
   * @return mixed
   */
  public function getIduser(): mixed
  {
    return $this->iduser;
  }
  public function getIdcompte(): mixed
  {
    return $this->idcompte;
  }
  /**
   * askIduser
   * Basée sur 'users_infos'
   */
  public function askIduser()
  {
    $iduser = $this->getIduser();
    $param = ['id' => $iduser];
    $requette = "select * from users_infos where id = :id limit 1";
    $infos = $this->magesquo->oneRow(requete: $requette, params: $param);
    $data = [];
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    return $data;
  }

  /**
   * allUsers
   *
   * 
   */
  public function allUsers($idcompte)
  {
    
    $param = ['idcompte' => $idcompte];
    $requette = "select * from users_infos where idcompte = :idcompte ";
    $infos = $this->magesquo->allRow(requete: $requette, params: $param);
    $data = [];
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    return $data;
  }

  /**
   * showUser
   * Retourne une puce avec N° et nom user principal
   * @return string
   */
  public function showUser(): string
  {
    $iduser = $this->getIduser();
    $param = ['id' => $iduser];
    $requette = "select * from users_infos where id = :id limit 1";
    $infos = $this->magesquo->oneRow(requete: $requette, params: $param);
    //pretty($infos);
    $nom = empty($infos['nom']) ? 'Nom' : $infos['nom'];
    $id = empty($infos['id']) ? 'id' : $infos['id'];
    $prenom = empty($infos['prenom']) ? 'Prénom' : $infos['prenom'];
    $show = ' <a href="parametres" class="px-3 py-1  ">N° ' . $id . ' - ' . $prenom . ' ' . $nom . '</a> ';
    return $show;
  }

  public function updateUser($id, $data): array
  {
    $erreurs = [];

    // Assigner les valeurs
    foreach ($data as $key => $value) {
      ${$key} = $value; // Assigner la valeur
    }

    // Valider chaque champ et ajouter les erreurs au tableau
    try {
      $nom = DataValidator::validateNom($nom);
    } catch (InvalidArgumentException $e) {
      $erreurs[] = $e->getMessage();
    }

    try {
      $prenom = DataValidator::validatePrenom($prenom);
    } catch (InvalidArgumentException $e) {
      $erreurs[] = $e->getMessage();
    }

    try {
      $adresse = DataValidator::validateAdresse($adresse);
    } catch (InvalidArgumentException $e) {
      $erreurs[] = $e->getMessage();
    }

    try {
      $ville = DataValidator::validateVille($ville);
    } catch (InvalidArgumentException $e) {
      $erreurs[] = $e->getMessage();
    }

    try {
      $cp = DataValidator::validateCP($cp);
    } catch (InvalidArgumentException $e) {
      $erreurs[] = $e->getMessage();
    }

    try {
      $telephone = DataValidator::validateTelephone($telephone);
    } catch (InvalidArgumentException $e) {
      $erreurs[] = $e->getMessage();
    }

    try {
      $portable = DataValidator::validatePortable($portable);
    } catch (InvalidArgumentException $e) {
      $erreurs[] = $e->getMessage();
    }

    try {
      $email = DataValidator::validateEmail($email);
    } catch (InvalidArgumentException $e) {
      $erreurs[] = $e->getMessage();
    }

    // Si des erreurs ont été trouvées, retourner le tableau d'erreurs
    if (!empty($erreurs)) {
      return $erreurs;
    }

    $param = ['nom' => $nom, 'prenom' => $prenom, 'adresse' => $adresse, 'ville' => $ville, 'cp' => $cp, 'telephone' => $telephone, 'portable' => $portable, 'email' => $email, 'id' => $id];
    //pretty($param);
    $update_data = 'UPDATE users_infos SET nom = :nom, prenom = :prenom,adresse = :adresse,ville = :ville, cp = :cp, telephone = :telephone, portable = :portable, email = :email  WHERE id = :id';
    $this->magesquo->handleRow($update_data, $param, 'update', $id, 'Mise à jour des informations utilisateur ' . $idcompte);

    return ['<span class="text-success">Informations de l\'utilisateur mises à jour</span>']; // Retourner un tableau vide si tout s'est bien passé
  }
}
