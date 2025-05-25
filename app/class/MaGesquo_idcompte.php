<?php
class Idcompte extends MaGesquo
{
  private $idcompte;
  private $conn;
  private $valid;

  public function __construct($idcompte)
  {
    $this->valid = new DataValidator();
    $this->conn = new MaGesquo($this->idcompte);
    $this->idcompte = $idcompte;
  }

  public function getIdcompte()
  {
    return $this->idcompte;
  }
  public function askIdcompte()
  {
    $idcompte = $this->getIdcompte();
    $param = ['idcompte' => $idcompte];
    $requette = "select * from idcompte where idcompte = :idcompte limit 1";
    $infos = $this->conn->oneRow(requete: $requette, params: $param);
    $data = [];
    foreach ($infos as $k => $v) {
      $data[$k] =  $v;
    }
    return $data;
  }
  public function showIdcompte(): mixed
  {
    $idcompte = $this->getIdcompte();
    $param = ['idcompte' => $idcompte];
    $requette = "select * from idcompte where idcompte = :idcompte limit 1";
    $infos = $this->conn->oneRow(requete: $requette, params: $param);
    //pretty($infos);
    $nom = empty($infos['nom_legal']) ? 'Nom legal' : $infos['nom_legal'];
    $id = empty($infos['referent_id']) ? 'referent_id' : $infos['referent_id'];
    $prenom = empty($infos['statut']) ? 'Statut' : $infos['statut'];
    $show = '<span class="px-0 py-2"><span class="small puce mr-1">N° ' . $id . '</span><span class=" text-muted mr-1">' . $prenom . '</span><span class="text-muted">' . $nom . '</span></span>';
    return $show;
  }
  public function updateIdcompte($id, $data): array
  {
    $erreurs = [];

    // Assigner les valeurs
    foreach ($data as $key => $value) {
      ${$key} = $value; // Assigner la valeur
    }

    // Valider chaque champ et ajouter les erreurs au tableau
    try {
      $nom_legal = DataValidator::validateNom($nom_legal);
    } catch (InvalidArgumentException $e) {
      $erreurs[] = 'Nom entreprise invalide';
    }

    try {
      $denomination_commerciale = DataValidator::validatePrenom($denomination_commerciale);
    } catch (InvalidArgumentException $e) {
      $erreurs[] = $e->getMessage();
    }

    try {
      $adresse_ent = DataValidator::validateAdresse($adresse_ent);
    } catch (InvalidArgumentException $e) {
      $erreurs[] = $e->getMessage();
    }

    try {
      $ville_ent = DataValidator::validateVille($ville_ent);
    } catch (InvalidArgumentException $e) {
      $erreurs[] = $e->getMessage();
    }

    try {
      $cp_ent = DataValidator::validateCP($cp_ent);
    } catch (InvalidArgumentException $e) {
      $erreurs[] = $e->getMessage();
    }

    try {
      $telephone_ent = DataValidator::validateTelephone($telephone_ent);
    } catch (InvalidArgumentException $e) {
      $erreurs[] = $e->getMessage();
    }

    try {
      $portable_ent = DataValidator::validatePortable($portable_ent);
    } catch (InvalidArgumentException $e) {
      $erreurs[] = $e->getMessage();
    }

    try {
      $email_ent = DataValidator::validateEmail($email_ent);
    } catch (InvalidArgumentException $e) {
      $erreurs[] = $e->getMessage();
    }

    // Si des erreurs ont été trouvées, retourner le tableau d'erreurs
    if (!empty($erreurs)) {
      return $erreurs;
    }

    $param = ['nom' => $nom, 'prenom' => $prenom, 'adresse' => $adresse, 'ville' => $ville, 'cp' => $cp, 'telephone' => $telephone, 'portable' => $portable, 'email' => $email, 'id' => $id];
    pretty($param);
    //$update_data = 'UPDATE users_infos SET nom = :nom, prenom = :prenom,adresse = :adresse,ville = :ville, cp = :cp, telephone = :telephone, portable = :portable, email = :email  WHERE id = :id';
    //$this->conn->handleRow($update_data, $param, 'update', $id, 'Mise à jour des informations utilisateur ' . $idcompte);

    return ['<span class="text-success">Informations de l\'entreprise mises à jour</span>']; // Retourner un tableau vide si tout s'est bien passé
  }
}
