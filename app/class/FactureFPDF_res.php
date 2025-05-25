<?php

class FactureFPDF extends FPDF
{

  private $numero;
  private $secteur;
  private $chemin;
  private $pdf;

  public function __construct($secteur, $numero, $pdf)
  {

    $this->chemin = $_SERVER['DOCUMENT_ROOT'];
    $this->numero = $numero;
    $this->pdf = $pdf; // Utilisation de l'objet PDF passé en paramètre
    $conn = new connBase();
    $devis = new Factures($secteur);

    $this->pdf->AliasNbPages();
    $this->pdf->AddFont('Nunito', '', 'Nunito-Regular.php');
    $this->pdf->AddFont('Nunito', 'B', 'Nunito-Bold.php');
    $this->pdf->SetAutoPageBreak(true, 55);
    $this->pdf->AcceptPageBreak();




    $reqentete = $conn->askDevisNum($secteur, $numero);
    foreach ($reqentete as $key => $value) {
      ${$key} = mb_convert_encoding($value, 'ISO-8859-1');
      //echo $key . ' ' . $value . '<br>';
    }
    $reqlignes = $conn->askFactureLigne($secteur, $numero);
    foreach ($reqlignes as $key => $value) {
      ${'l_' . $key} = mb_convert_encoding($value, 'ISO-8859-1');
      //echo $key.' '.$value .'<br>';
    }
    // On récupère les éléments de l'entreprise.
    $reqcompte = $conn->askIdcompte($secteur);
    foreach ($reqcompte as $key => $value) {
      ${'c_' . $key} = mb_convert_encoding($value, 'ISO-8859-1');
      //echo $key.' '.$value .'<br>';
    }
    // On récupère les éléments du client.
    $reqcompte = $conn->askClientAdresse($idcli);
    foreach ($reqcompte as $key => $value) {
      ${'u_' . $key} = mb_convert_encoding($value, 'ISO-8859-1');
      //echo $key.' '.$value .'<br>';
    }
    $reqcompte = $conn->askClient($idcli);
    foreach ($reqcompte as $key => $value) {
      ${'u_' . $key} = mb_convert_encoding($value, 'ISO-8859-1');
      //echo $key.' '.$value .'<br>';
    }

    $reqacompte = $devis->askFacturesEntete($numero);
    foreach ($reqacompte as $key => $value) {
      ${'ac_' . $key} = mb_convert_encoding($value, 'ISO-8859-1');
      //echo 'ac ' . $key . ' ' . $value . '<br>';
    }

    $verif_acompte = $devis->askAcompte($ac_devref, $idcli);
    foreach ($verif_acompte as $key => $value) {
      ${'a_' . $key} = mb_convert_encoding($value, 'ISO-8859-1');
      // echo $key . ' ' . $value . '<br>';
    }
    $adresse_chantier = trim(strtolower($u_adresse));
    $adresse_adresse = trim(strtolower($u_adressfact));
    $compare = strcmp($adresse_adresse, $adresse_chantier);
    $adresse_chantier = null;
    if ($compare != '0') {
      $adresse_chantier = 'Chantier : ' . $u_adresse . ' ' . $u_cp . ' ' . $u_ville;
    }
    $reqfact = $devis->askFacturesEntete($numero);
    foreach ($reqfact as $key => $value) {
      ${'f_' . $key} = mb_convert_encoding($value, 'ISO-8859-1');
      //echo $key . ' ' . $value . '<br>';
    }
    $reqbank = $conn->askBankDefault($secteur);
    foreach ($reqbank as $key => $value) {
      $compte_bancaire = mb_convert_encoding('IBAN : ' . $value['iban'] . ' BIC : ' . $value['bic'] . ' / ' . $value['nom_bank'], 'ISO-8859-1');
    }

    $factav = $f_factav;
    $nomsecteur = mb_convert_encoding(NomSecteur($secteur), 'ISO-8859-1');
    $n = mb_convert_encoding('N°', 'ISO-8859-1');
    $agrement = mb_convert_encoding('Agrément : ' . $c_agre, 'ISO-8859-1');
    $datefacture = $reqfact['jour'] . '/' . $f_mois . '/' . $f_annee;
    define('SAUT', 4);
    define('NOMSEC', strtoupper($nomsecteur . ' ' . $c_statut));
    define('DENOMINATION', $c_denomination);
    define('ADRESSE', $c_adresse);
    define('CPVILLE', $c_cp . ' ' . ($c_ville));
    define('LIEUDATE', ('A ' . $c_ville . ', le ' . $datefacture));
    define('EDITION', ('Edition du ' . date('d/m/Y')));
    define('SIRET', 'SIRET : ' . $c_siret . ' ' . $c_rcs);
    define('AGRE', $agrement);
    define('TELEPHONE', $c_telephone);
    define('EMAIL', $c_email);
    define('SEC', $secteur);
    define('PIECE', $numero);
    define('FACTAV', $factav);
    define('RETRAITADR', 110);
    define('NOMCLI', $u_civilite . ' ' . $u_prenom . ' ' . $u_nom);
    define('ADRCLI', $u_adressfact);
    define('CPVILLECLI', $u_cpfact . ' ' . $u_villefact);
    define('ADRESSCHANTIER', $adresse_chantier);
    define('N', $n);
    define('FONT', 9);
    define('VOTRECONTACT', 'Votre contact');
    define('COMMERCIAL',  mb_convert_encoding(NomColla($iduser), 'ISO-8859-1'));
    define('TELCOMM', $c_telephone);
    define('TVA', $c_t7);
    define('SAP', $c_sap);
    if ($f_commentaire === "") {
      define('COMMENTAIRE', '');
    } else {
      define('COMMENTAIRE', ' - ' . $f_commentaire);
    }
    define('EPOQUE', $epoque);
    define('VALIDITE', mb_convert_encoding('Validité du devis : ' . $c_valdev, 'ISO-8859-1'));
    define('MODEREG', $c_modreg . ', ' . $c_delpai . '.');
    define('NUMCLIENT', $u_idcli);
    define('EURO', '€');
    define('BANK', $compte_bancaire);


    // Vérification existance logo secteur.
    $affiche_logo = null;
    $chemin_logo = '../../../documents/img/' . SEC . '/logo.png';
    if (file_exists($chemin_logo)) {
      define('ISLOGO', 'ok');
    } else {
      define('ISLOGO', null);
    }

    $this->pdf->AddPage();
  }


  public function Header()
  {

    if (!is_null(ISLOGO)) {

      $n =   '../../../documents/img/' . SEC . '/logo.png';
      $path_image = $this->chemin . '/documents/img/' . SEC . '/logo.png';
      $h = definirHauteurImageProgressive($path_image);
      $this->pdf->Image($path_image, 8, 8, $h);
    }
    $this->pdf->SetFont('Nunito', 'B', 10);
    $this->pdf->Cell(0, SAUT + 5,  LIEUDATE, '', 1, 'R');
    $this->pdf->SetFont('Nunito', 'B', 20);
    $this->pdf->Cell(0, SAUT + 5,  FACTAV, '', 1, 'R');
    $this->pdf->SetFont('Nunito', 'B', 15);
    $this->pdf->Cell(0, SAUT + 1,   N . ' ' . PIECE, '', 1, 'R');
    $this->pdf->SetFont('Nunito', 'B', 9);
    $this->pdf->Cell(0, SAUT + 1,   EDITION, '', 1, 'R');
    $this->pdf->Ln(28);
    $y = $this->pdf->GetY();
    define('Y', $y);
    $this->pdf->SetFont('Nunito', 'B', 10);
    $this->pdf->SetY($y - 5);
    $this->pdf->Cell(100, SAUT + 1, N . ' client : ' . NUMCLIENT, 0, 0, 'L');
    $this->pdf->Ln(25);
  }


  public function Head($numero)
  {

    $this->pdf->SetFont('Nunito', 'B', 10);
    $this->pdf->Cell(0, SAUT + 5,  LIEUDATE, '', 1, 'R');
    $this->pdf->SetFont('Nunito', 'B', 20);
    $this->pdf->Cell(0, SAUT + 5,  FACTAV, '', 1, 'R');
    $this->pdf->SetFont('Nunito', 'B', 15);
    $this->pdf->Cell(0, SAUT + 1,   N . ' ' . $numero, '', 1, 'R');
    $this->pdf->SetFont('Nunito', 'B', 9);
    $this->pdf->Cell(0, SAUT + 1,   EDITION, '', 1, 'R');
  }


  // function Footer()
  // {
  //     $pdf_footer = new FPDF();
  //     $pdf_footer->AddPage();
  //     $pdf_footer->AddFont('Nunito', '', 'Nunito-Regular.php');
  //     $pdf_footer->AddFont('Nunito', 'B', 'Nunito-Bold.php');
  //     $pdf_footer->SetY(-25);
  //     $pdf_footer->SetFont('Nunito', 'B', 8);
  //     $pdf_footer->Cell(190, SAUT, NOMSEC, 0, 1, 'C');
  //     $pdf_footer->SetFont('Nunito', '', 8);
  //     $pdf_footer->Cell(190, SAUT, ADRESSE . ' ' . CPVILLE, 0, 1, 'C');
  //     $pdf_footer->Cell(190, SAUT, TELEPHONE . ' ' . EMAIL . '   ' . SIRET . ' ' . AGRE, 0, 1, 'C');
  //     $pdf_footer->SetFont('Nunito', 'B', 7);
  //     $pdf_footer->Cell(193, SAUT + 3, 'Page ' . $this->PageNo() . '/{nb}', 0, 1, 'C');
  // }
  // function Adresse()
  // {
  //     $pdf_adr = new FPDF();
  //     $pdf_adr->AddPage();
  //     $pdf_adr->AddFont('Nunito', '', 'Nunito-Regular.php');
  //     $pdf_adr->AddFont('Nunito', 'B', 'Nunito-Bold.php');
  //     $pdf_adr->SetY(Y);
  //     $pdf_adr->SetFont('Nunito', 'B', 10);
  //     $pdf_adr->Cell(100, SAUT + 1, VOTRECONTACT, 0, 0, 'L');
  //     $pdf_adr->Cell(95, SAUT + 1, NOMCLI, 0, 1, 'L');
  //     $pdf_adr->SetFont('Nunito', '', 10);
  //     $pdf_adr->Cell(100, SAUT + 1, COMMERCIAL, 0, 0, 'L');
  //     $pdf_adr->Cell(95, SAUT + 1, ADRCLI, 0, 1, 'L');
  //     $pdf_adr->Cell(100, SAUT + 1, TELCOMM, 0, 0, 'L');
  //     $pdf_adr->Cell(95, SAUT + 1, CPVILLECLI, 0, 1, 'L');
  //     $pdf_adr->SetFont('Nunito', 'B', 8);
  //     $pdf_adr->Cell(100, SAUT + 1, ADRESSCHANTIER, 0, 0, 'L');
  //     $pdf_adr->Ln(8);
  // }
  // function Infos()
  // {
  //     $info = new FPDF;
  //     $info->AddPage();
  //     $info->AddFont('Nunito', '', 'Nunito-Regular.php');
  //     $info->AddFont('Nunito', 'B', 'Nunito-Bold.php');
  //     $reglement = mb_convert_encoding('Règlement : ', 'ISO-8859-1');
  //     $info->SetFont('Nunito', '', 10);
  //     $info->Cell(100, SAUT + 2,  $reglement . MODEREG, 0, 1, 'L');
  //     $notation = strEncoding('Merci de noter le numéro de facture lors de vos virements ou au dos votre chèque.');
  //     $info->SetFont('Nunito', 'B', 10);
  //     $info->Cell(25, SAUT + 2, $notation, 0, 1, 'L');
  //     $adrenvoi = strEncoding('Envoyez vos chèques au 3, place de l`Eglise 45740 Lailly-en-Val.');
  //     if (SEC == 'C4X') {
  //         $info->SetFont('Nunito', 'B', 10);
  //         $info->Cell(25, SAUT + 2, $adrenvoi, 0, 0, 'L');
  //     }
  // }
  // function Bank()
  // {
  //     $bk = new FPDF;
  //     $bk->AddPage();
  //     $bk->AddFont('Nunito', '', 'Nunito-Regular.php');
  //     $bk->AddFont('Nunito', 'B', 'Nunito-Bold.php');
  //     $reglement = mb_convert_encoding('Références bancaires : ', 'ISO-8859-1');
  //     $bk->SetFont('Nunito', '', 10);
  //     // $bk->Cell(100, SAUT + 1, 'Execution : ' . EPOQUE, 0, 1, 'L');


  //     $bk->Cell(100, SAUT + 2,  $reglement . BANK, 0, 1, 'L');
  //     //$bk->Cell(100, SAUT + 1,  VALIDITE, 0, 1, 'L');
  // }
  // function Lignes($numero)
  // {
  //     $devis = new Factures(SEC);
  //     $lignes = $devis->askFacturesLignes($numero);
  //     $entete = $devis->askFacturesEntete($numero);
  //     $acompte = $devis->askAcompte($entete['devref'], NUMCLIENT);
  //     //echo $entete['devref'];
  //     //var_dump($acompte);
  //     if ($acompte['montant']) {
  //         $acompte_client = $acompte['montant'];
  //     } else {
  //         $acompte_client = 0;
  //     }

  //     $desi = mb_convert_encoding('Désignation', 'ISO-8859-1');
  //     $titre = mb_convert_encoding($entete['titre'], 'ISO-8859-1');
  //     $titre = $titre === '' ? 'Prestation' : $titre;
  //     $pdf = new FPDF;
  //     $pdf->AddPage();
  //     $pdf->AddFont('Nunito', '', 'Nunito-Regular.php');
  //     $pdf->AddFont('Nunito', 'B', 'Nunito-Bold.php');
  //     $pdf->SetFont('Nunito', 'B', 10);
  //     $pdf->Cell(190, SAUT + 2, $titre . COMMENTAIRE, 'B', 1, 'L');
  //     $pdf->Ln(4);
  //     $pdf->Cell(115, SAUT + 1, $desi, 0, 0, 'L');
  //     $pdf->Cell(25, SAUT + 1, 'Q.', 0, 0, 'R');
  //     $pdf->Cell(25, SAUT + 1, 'PU', 0, 0, 'R');
  //     $pdf->Cell(25, SAUT + 1, 'PTTC', 0, 1, 'R');
  //     $pdf->Ln(5);
  //     foreach ($lignes as $l) {
  //         $pdf->SetFont('Nunito', '', 10);
  //         $desi = mb_convert_encoding($l['designation'], 'ISO-8859-1');
  //         //$pdf->Cell(95, SAUT + 1, $desi, 0, 0, 'L');
  //         if ($l['q'] === 0.00 || $l['puttc'] === 0.00 || $l['ptttc'] === 0.00) {
  //             $largeurWW = 195;
  //         } else {
  //             $largeurWW = 105;
  //         }
  //         $d = $pdf->WordWrap($desi, $largeurWW);
  //         //$pdf->MultiCell(115,SAUT,$d,0,'L',0);
  //         //$pdf->Cell(95, SAUT + 1, $d, 0, 0, 'L');
  //         $pdf->Write(SAUT + 1, $d);
  //         $pdf->SetX(125);
  //         $q = $l['q'] === 0.00 ? "" : Dec_2($l['q']);
  //         $pdf->Cell(25, SAUT + 1, $q, 0, 0, 'R');
  //         $pu = $l['puttc'] === 0.00 ? "" : Dec_2($l['puttc']);
  //         $pdf->Cell(25, SAUT + 1, $pu, 0, 0, 'R');
  //         $tot = $l['ptttc'] === 0.00 ? "" : Dec_2($l['ptttc']);
  //         $pdf->Cell(25, SAUT + 1, $tot, 0, 1, 'R');
  //         $pdf->Ln(3);
  //     }
  //     $pdf->SetFont('Nunito', '', 10);
  //     $montant_tva =  mb_convert_encoding('Montant TVA à ' . TVA . '%', 'ISO-8859-1');
  //     $pdf->Cell(190, SAUT + 1, '', 'T', 1, 'L');
  //     $y = $pdf->GetY();
  //     $pdf->SetY($y + 36);
  //     $pdf->SetFont('Nunito', '', 10);
  //     $pdf->SetY($y + 4);
  //     $pdf->SetX(125);
  //     $pdf->Cell(25, SAUT + 1, 'Montant HT', 0, 0, 'L');
  //     $pdf->Cell(25, SAUT + 1, '', 0, 0, 'L');
  //     $pdf->Cell(25, SAUT + 1, $ht = Dec_2(($entete['totttc'] + $acompte_client) / 1.2), 0, 1, 'R');
  //     $pdf->Cell(115, SAUT + 1, '', 0, 0, 'R');
  //     $pdf->Cell(25, SAUT + 1, $montant_tva, 0, 0, 'L');
  //     $pdf->Cell(25, SAUT + 1, '', 0, 0, 'L');
  //     $pdf->Cell(25, SAUT + 1, Dec_2($entete['totttc'] + $acompte_client - $ht), 0, 1, 'R');
  //     $pdf->Cell(115, SAUT + 1, '', 0, 0, 'R');
  //     $pdf->SetFont('Nunito', 'B', 10);
  //     $montant_a_payer = mb_convert_encoding('Montant à payer TTC', 'ISO-8859-1');
  //     $pdf->Cell(25, SAUT + 1, $montant_a_payer, 0, 0, 'L');
  //     $pdf->Cell(25, SAUT + 1, '', 0, 0, 'L');
  //     $pdf->Cell(25, SAUT + 1, Dec_2($entete['totttc'] + $acompte_client), 0, 1, 'R');
  //     if ($acompte_client > 0) {
  //         $pdf->Cell(115, SAUT + 1, '', 0, 0, 'R');
  //         $pdf->SetFont('Nunito', '', 10);
  //         $acompte_verse = mb_convert_encoding('Acompte versé', 'ISO-8859-1');
  //         $pdf->Cell(25, SAUT + 1, $acompte_verse, 0, 0, 'L');
  //         $pdf->Cell(25, SAUT + 1, '', 0, 0, 'L');
  //         $pdf->Cell(25, SAUT + 1, Dec_2($acompte_client), 0, 1, 'R');

  //         $pdf->Cell(115, SAUT + 1, '', 0, 0, 'R');
  //         $pdf->SetFont('Nunito', 'B', 10);
  //         $acompte_verse = mb_convert_encoding('Solde', 'ISO-8859-1');
  //         $pdf->Cell(25, SAUT + 1, $acompte_verse, 0, 0, 'L');
  //         $pdf->Cell(25, SAUT + 1, '', 0, 0, 'L');
  //         $pdf->Cell(25, SAUT + 1, Dec_2($entete['totttc']), 0, 1, 'R');
  //     }
  //     if (SAP != null) {

  //         $montant_reduction = Dec_2(($entete['totttc'] + $acompte_client) / 2);
  //         $reduc = $montant_reduction > 5000 ? 5000 : $montant_reduction;
  //         $reduction = mb_convert_encoding('Réduction d\'impôts envisagée', 'ISO-8859-1');

  //         //$pdf->Cell(115, SAUT + 1, '', 0, 0, 'R');
  //         $pdf->SetFont('Nunito', '', 10);
  //         $pdf->Cell(25, SAUT + 1, $reduction . ' : ' . Dec_2($reduc) . ' euros', 0, 1, 'L');


  //         // $pdf->Cell(25, SAUT + 1, '', 0, 0, 'L');

  //         // $pdf->Cell(25, SAUT + 1, Dec_2($reduc), 0, 1, 'R');
  //     }
  //     $pdf->Ln(10);
  //     $this->Bank();
  //     $this->Infos();
  //     $pdf->Ln(45);
  //     // $this->Infos();
  //     //$this->Ln(15);
  // }
  // function afficheImage($path, $x, $a)
  // {
  //     $img = new FPDF;
  //     $img->AddPage();
  //     $img->AddFont('Nunito', '', 'Nunito-Regular.php');
  //     $img->AddFont('Nunito', 'B', 'Nunito-Bold.php');
  //     $y = $img->GetY();
  //     $img->Image($path, $x, $y - $a - 14, 30);
  // }
}

//     private function setIncrementFacture()
//     {
//         $conn = new connBase();
//         $secteur = $this->secteur;
//         $reqfacture = "select COUNT(*) as total from facturesentete where cs='$secteur' ";
//         $facture = $conn->oneRow($reqfacture);
//         $this->increment = $facture['total'];
//     }

//     public function getNumFacture()
//     {
//         $conn = new connBase();
//         $idsect = $this->secteur;
//         $racine_devis = $conn->oneRow("select * from idcompte_infos where idcompte = '$idsect' ");
//         $racine = $racine_devis['fac_racine'];
//         $num = $this->increment;
//         $date = date('ym');
//         $numero = $idsect . '-' . $racine . '-' . $date . '-' . $num;
//         return $numero;
//     }

//     public function getNumAvoir()
//     {
//         $conn = new connBase();
//         $idsect = $this->secteur;
//         $racine_devis = $conn->oneRow("select * from idcompte_infos where idcompte = '$idsect' ");
//         $racine = $racine_devis['avo_racine'];
//         $num = $this->increment;
//         $date = date('ym');
//         $numero = $idsect . '-' . $racine . '-' . $date . '-' . $num;
//         return $numero;
//     }

//     public function getNomNumCommercial($nom = "", $num = "")
//     {
//         $conn = new connBase();
//         $idsect = $this->secteur;
//         $requsers = "select idusers,nom, prenom from users where idcompte = '$idsect' and nom like '$nom' or idcompte = '$idsect' and idusers like '$num' limit 1";
//         $num = $conn->oneRow($requsers);

//         return array(
//             "nom" => $num['prenom'] . ' ' . $num['nom'],
//             "num" => $num['idusers']
//         );
//     }


//     public function getMoisCA($mois, $annee)
//     {
//         $conn = new connBase();
//         $secteur = $this->secteur;
//         $requsers = "select *,SUM(totttc) as total from facturesentete where cs='$secteur' and mois = '$mois' and annee='$annee' and paye='oui' ";
//         $num = $conn->oneRow($requsers);
//         $data = $num['total'];
//         return $data;
//     }

//     public function getMoisCAn($mois, $annee)
//     {
//         $conn = new connBase();
//         $secteur = $this->secteur;
//         $requsers = "select *,SUM(totttc) as total from facturesentete where cs='$secteur' and mois = '$mois' and annee='$annee' and paye='non' ";
//         $num = $conn->oneRow($requsers);
//         $data = $num['total'];
//         return $data;
//     }

//     public function infoPropre($infos)
//     {
//         $infos = str_replace('<small>', '', $infos);
//         $infos = str_replace('<br>', "\n", $infos);
//         return trim($infos);
//     }

//     public function getSerieCA($a)
//     {
//         //$annee = date('Y');
//         $annee = $a;
//         $concatenated = "";
//         for ($i = 1; $i < 13; $i++) {
//             $mois = strlen($i) < 2 ? "0" . $i : $i;
//             $donnees = $this->getMoisCA($mois, $annee);
//             $concatenated .= "" . Dec_2($donnees) . ",";
//         }
//         $concatenated = rtrim($concatenated, ", ");
//         $mois_ca = "[" . $concatenated . "]";
//         return $mois_ca;
//     }

//     public function getSerieCAn($a)
//     {
//         //$annee = date('Y');
//         $annee = $a;
//         $concatenated = "";
//         for ($i = 1; $i < 13; $i++) {
//             $mois = strlen($i) < 2 ? "0" . $i : $i;
//             $donnees = $this->getMoisCAn($mois, $annee);
//             $concatenated .= "" . Dec_2($donnees) . ",";
//         }
//         $concatenated = rtrim($concatenated, ", ");
//         $mois_ca = "[" . $concatenated . "]";
//         return $mois_ca;
//     }

//     public function getSerieAnnee()
//     {
//         $mois_lettre = array();
//         $mois = array(
//             'Janvier',
//             'Février',
//             'Mars',
//             'Avril',
//             'Mai',
//             'Juin',
//             'Juillet',
//             'Août',
//             'Septembre',
//             'Octobre',
//             'Novembre',
//             'Décembre'
//         );
//         for ($i = 0; $i < count($mois); $i++) {
//             $mois_lettre[] = $mois[$i];
//         }
//         $concatenated = "";
//         foreach ($mois_lettre as $serie) {
//             $concatenated .= "'" . $serie . "',";
//         }
//         $concatenated = rtrim($concatenated, ", ");
//         $lettre = "[" . $concatenated . "]";
//         return $lettre;
//     }
//     public function askFacturesLignes($numero, $champ = "*")
//     {
//         $conn = new connBase();
//         $requette = "select $champ from factureslignes where numero='$numero'";
//         $infos = $conn->allRow($requette);
//         $data = array();
//         foreach ($infos as $k => $v) {
//             $data[$k] =  $v;
//         }
//         // if($infos){$data='existe';}else{$data='no data';}
//         return $data;
//     }

//     public function askFacturesEntete($numero)
//     {
//         $secteur = $this->secteur;
//         $conn = new connBase();
//         $reqligne = "select * from facturesentete where numero = '$numero' and cs='$secteur'";
//         $ligne = $conn->oneRow($reqligne);
//         return $ligne;
//     }

//     public function askFacturesAttente()
//     {
//         $secteur = $this->secteur;
//         $conn = new connBase();
//         $reqligne = "select SUM(totttc) as tot from facturesentete where paye = 'non' and cs='$secteur'";
//         $ligne = $conn->oneRow($reqligne);
//         return $ligne;
//     }

//     public function askFacturesRelance()
//     {
//         $secteur = $this->secteur;
//         $conn = new connBase();
//         $reqligne = "select * from facturesentete where paye = 'non' and cs='$secteur' group by id order by nom asc";
//         $ligne = $conn->allRow($reqligne);
//         return $ligne;
//     }

//     public function askFacturesAttenteClient($idcli)
//     {
//         $secteur = $this->secteur;
//         $conn = new connBase();
//         $reqligne = "select * from facturesentete where paye = 'non' and cs='$secteur' and id='$idcli' ";
//         $ligne = $conn->allRow($reqligne);
//         return $ligne;
//     }

//     public function askFacturesRelanceClientTotal($idcli)
//     {
//         $secteur = $this->secteur;
//         $conn = new connBase();
//         $reqligne = "select SUM(totttc) as tot from facturesentete where paye = 'non' and cs='$secteur' and id='$idcli' group  by id";
//         $ligne = $conn->oneRow($reqligne);
//         return $ligne;
//     }



//     public static function askRemises($secteur)
//     {

//         $conn = new connBase();
//         $requette = "select * from reglements where cs='$secteur' ";
//         $infos = $conn->allRow($requette);
//         $data = array();
//         foreach ($infos as $k => $v) {
//             $data[$k] =  $v;
//         }
//         // if($infos){$data='existe';}else{$data='no data';}
//         return $data;
//     }

//     public function getChemin()
//     {
//         $secteur = $this->secteur;
//         $chemin = $_SERVER['DOCUMENT_ROOT'];
//         if (!is_dir($chemin . '/documents/pdf/factures')) {
//             mkdir($chemin . '/documents/pdf/factures');
//         }
//         if (!is_dir($chemin . '/documents/pdf/factures/' . $secteur)) {
//             mkdir($chemin . '/documents/pdf/factures/' . $secteur);
//         }
//         $path = $chemin . '/documents/pdf/factures/' . $secteur . '/';
//         return $path;
//     }

//     public function getCheminRelance($idcli)
//     {
//         $secteur = $this->secteur;
//         $chemin = $_SERVER['DOCUMENT_ROOT'];
//         if (!is_dir($chemin . '/documents/pdf/relances')) {
//             mkdir($chemin . '/documents/pdf/relances');
//         }
//         if (!is_dir($chemin . '/documents/pdf/relances/' . $secteur)) {
//             mkdir($chemin . '/documents/pdf/relances/' . $secteur);
//         }

//         if (!is_dir($chemin . '/documents/pdf/relances/' . $secteur . '/client_' . $idcli)) {
//             mkdir($chemin . '/documents/pdf/relances/' . $secteur . '/client_' . $idcli);
//         }


//         $path = $chemin . '/documents/pdf/relances/' . $secteur . '/client_' . $idcli;
//         return $path;
//     }

//     public function getFichier($numero)
//     {
//         $secteur = $this->secteur;
//         // $chemin = $_SERVER['DOCUMENT_ROOT'];
//         // $repertoire = $chemin . '/documents/pdf/devis/' . $secteur . '/';
//         $repertoire = $this->getChemin();
//         $nomFichierRecherche = 'Facture_' . $numero . '.pdf';
//         $fichiers = glob($repertoire . '*' . $nomFichierRecherche);
//         if (!empty($fichiers)) {
//             $res = $nomFichierRecherche;
//         } else {
//             $res = 0;
//         }
//         return $res;
//     }

//     public function getRelance($idcli)
//     {
//         $secteur = $this->secteur;
//         // $chemin = $_SERVER['DOCUMENT_ROOT'];
//         // $repertoire = $chemin . '/documents/pdf/devis/' . $secteur . '/';
//         $repertoire = $this->getCheminRelance($idcli);
//         $nomFichierRecherche = 'Relance_' . $idcli . '.pdf';
//         //$fichiers = glob($repertoire . '*' . $nomFichierRecherche);
//         $fichiers = $repertoire . '/' . $nomFichierRecherche;
//         //var_dump($fichiers);
//         if (!empty($fichiers)) {
//             $res = $nomFichierRecherche;
//         } else {
//             $res = 0;
//         }
//         return $res;
//     }

//     public function askAcompte($numero, $client)
//     {
//         $conn = new connBase();
//         $secteur = $this->secteur;
//         $requette = "select * from reglements where cs='$secteur' and id like '$client' and factref like '$numero' and acompte = '1' ";
//         $infos = $conn->oneRow($requette);
//         $data = array();
//         foreach ($infos as $k => $v) {
//             $data[$k] =  $v;
//         }
//         // if($infos){$data='existe';}else{$data='no data';}
//         return $infos;
//     }
// }
