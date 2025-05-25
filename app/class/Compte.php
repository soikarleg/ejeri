<?php

class Compte
{

  private $iduser;


  public function __construct($iduser)
  {
    $this->iduser = $iduser;
  }

  public function verifPaye()
  {
    $iduser = $this->iduser;
    $user = new connBase();
    $user_infos = $user->askIduser($iduser, 'payeok');
    $payeok = $user_infos['payeok'];
    return $payeok;
  }

  public function getFacturation($secteur, $mois, $annee)
  {
    $idcompte = new connBase();
    //$facturation = $idcompte->askAllFacture($secteur, 'and mois=\'' . $mois . '\' and annee=\'' . $annee . '\'', 'SUM(totttc) as t');
    $facturation = $idcompte->askAllFacture($secteur, 'and mois=\'' . $mois . '\' and annee=\'' . $annee . '\'', '*, SUM(totttc) as t');
    return $facturation;
  }


  public function getDateDebut()
  {
    $iduser = $this->iduser;
    $user = new connBase();
    $user_infos = $user->askIduser($iduser);

    $id = $user_infos['id'];

    $user_auth = new authBase();
    $date_debut = $user_auth->askUsers($id);
    //var_dump($date_debut);
    $timestamp = $date_debut['registered'];
    $date_format = 'd/m/Y';
    $date_debut = date($date_format, $timestamp);
    return array(
      "date" => $date_debut,
      "timestamp" => $timestamp
    );
  }

  public function getFinEssai()
  {
    $date_debut = $this->getDateDebut();
    $d = explode('/', $date_debut['date']);
    $jour = $d[0];
    $mois = $d[1];
    $annee = $d[2];
    $date_corrigee = $mois . '/' . $jour . '/' . $annee;
    $essai_fin = date('d/m/Y', strtotime($date_corrigee . ' +30DAYS'));
    return $essai_fin;
  }

  public function getMoisDebutFacture()
  {
    $date_debut_fact = $this->getFinEssai();
    $mois_debut = explode('/', $date_debut_fact);
    $mois_debut = $mois_debut[1] - 1;
    return $mois_debut;
  }
};
