<?php
class FactureFPDF extends FPDF
{
    private $numero;
    private $secteur;
    private $chemin;
    private $pdf;

    // Propriétés supplémentaires pour l'en-tête
    private $clientNum;
    private $clientNom;
    private $clientAdresse;
    private $clientAdresseChantier;
    private $clientVille;

    private $factureNumber;
    private $factureType;
    private $factureDate;
    private $factureLieu;
    private $facturePaye;

    private $commercialName;
    private $commercialTel;

    private $secteurNom;
    private $secteurAdresse;
    private $secteurInfos;
    private $secteurModalite;
    private $secteurEscompte;
    private $secteurRetard;
    private $secteurPenal;
    private $secteurBank;
    private $secteurSAP;
    private $secteurTVA;

    public function __construct($secteur, $numero, $pdf)
    {
        $this->chemin = $_SERVER['DOCUMENT_ROOT'];
        $this->numero = $numero;
        $this->pdf = $pdf;
        $this->secteur = $secteur;

        $this->pdf->AliasNbPages();
        $this->pdf->AddFont('Nunito', '', 'Nunito-Regular.php');
        $this->pdf->AddFont('Nunito', 'B', 'Nunito-Bold.php');
        $this->pdf->SetAutoPageBreak(true, 10);
        $this->pdf->AcceptPageBreak();

        // Charger les données pour l'en-tête
        $this->dataHeader($secteur, $numero);

        // Ajouter une page pour cette facture
        $this->pdf->AddPage();

        // Générer les lignes de la facture
        // $this->Lignes($numero);
    }

    // Méthode pour charger les données de l'en-tête de chaque facture
    private function dataHeader($secteur, $numero)
    {
        $conn = new connBase();

        //*********************
        // INFOS LOGO
        /* #region LOGO */
        $chemin_logo = '../../../documents/img/' . $secteur . '/logo.png';
        if (file_exists($chemin_logo)) {
            define('ISLOGO', 'ok');
        } else {
            define('ISLOGO', null);
        }
        /* #endregion */



        //*********************
        // INFOS FACTURE
        /* #region FACTURE */
        $reqentete = $conn->askFactureNum($secteur, $numero);
        foreach ($reqentete as $key => $value) {
            ${$key} = mb_convert_encoding($value, 'ISO-8859-1');
            // echo '$' . $key . ' = ' . $value . '<br>';
        }


        // $id = 4986
        // $datefact = 2024-06-25
        // $dateche = 2024-06-30
        // $factav = FACTURE
        // $cs = C4X
        // $numero = C4X-F-2406-6684
        // $md5_f =
        // $numdev = 33385
        // $nom = POZZO
        // $commercial = 61
        // $devref =
        // $titre = Entretien du jardin
        // $totttc = 120
        // $txtva = 20
        // $commentaire =
        // $jour = 25
        // $mois = 06
        // $annee = 2024
        // $paye = non
        // $echejour = 30
        // $echemois = 06
        // $echeannee = 2024


        $this->factureNumber = mb_convert_encoding('N° ' . $numero, 'ISO-8859-1');
        $this->factureDate = $jour . '/' . $mois . '/' . $annee;
        $this->factureType = strtoupper($factav);
        $this->facturePaye = $paye;
        /* #endregion */


        //*********************
        // INFOS SECTEUR
        /* #region SECTEUR */
        $idcompte = $conn->askIdcompte($secteur);
        foreach ($idcompte as $key => $value) {
            ${'s_' . $key} = $value;
            //echo '$s_' . $key . ' = ' . $value . '<br>';
        }
        $this->factureLieu = 'A ' . $s_ville . ', le ' . $this->factureDate;
        $this->secteurNom = strEncoding($s_secteur . ' ' . $s_statut);
        $this->secteurAdresse = ($s_adresse . ' ' . $s_cp . ' ' . $s_ville);
        $this->secteurInfos = strEncoding($s_telephone . ' ' . $s_email . ' SIRET : ' . $s_siret . ' ' . $s_rcs . ' Agrément : ' . $s_agre);
        $this->secteurModalite = strEncoding($s_modreg . ' ' . $s_delpai . ', au plus tard le ' . $echejour . '/' . $echemois . '/' . $echeannee);
        $this->secteurEscompte = strEncoding('Aucun escompte pour paiement anticipé.');
        $this->secteurRetard = strEncoding($s_penal . '.');
        $this->secteurPenal = strEncoding('Indemnité forfaitaire de 40 euros pour frais de recouvrement.');
        $this->secteurSAP = $s_sap;
        $this->secteurTVA = $s_t7;
        // $s_idcompte = C4X
        // $s_secteur = CS LOIRE ATLANTIQUE
        // $s_denomination = EJERI JARDINS
        // $s_nic = 00025
        // $s_naf = 81.30Z
        // $s_abo = 0
        // $s_recu = 1
        // $s_sap = oui
        // $s_adresse = 3 Place de l'Eglise
        // $s_cp = 45740
        // $s_ville = Lailly-en-Val
        // $s_telephone = 02.38.45.15.78
        // $s_portable =
        // $s_email = contact@ejeri.fr
        // $s_fax =
        // $s_siret = 802910448
        // $s_agre = SAP802910448
        // $s_tva = oui
        // $s_rcs = RCS Nantes 802 910 448
        // $s_civ =
        // $s_nom =
        // $s_prenom =
        // $s_statut = EURL
        // $s_capital = 5000
        // $s_fonc =
        // $s_tr = 48
        // $s_tr1 = 0
        // $s_tr2 = 0
        // $s_phnf = 15
        // $s_t7 = 20
        // $s_fac_racine = F
        // $s_avo_racine = AV
        // $s_dev_racine = D
        // $s_valdev = 1 mois
        // $s_delpai = à réception de la facture
        // $s_delj = 5
        // $s_modreg = Chèque ou virement
        // $s_penal = Pénalités de retard à 15% annuel
        // $s_cgv =
        // $s_police = Arial
        // $s_baspage = oui
        // $s_logosect = logosect.jpg
        // $s_largsect = 30
        // $s_logosap = logosap.jpg
        // $s_largsap = 20
        // $s_logopro = logopro.jpg
        // $s_largpro = 20
        // $s_logobdp = bdp.jpg
        // $s_largbdp = 130
        // $s_identnova =
        // $s_mdpnova =
        // $s_time = 2024-06-10 10:37:50
        /* #endregion */


        //*********************
        // INFOS COMMERCIAL
        /* #region COMMERCIAL */
        $test_type = ctype_alpha($commercial);
        if ($test_type != true) {

            $commercial = $conn->askIduser($commercial);
            foreach ($commercial as $key => $value) {
                ${'c_' . $key} = $value;
                //echo '$c_' . $key . ' = ' . $value . '<br>';
            }
            $this->commercialName = mb_convert_encoding(NomColla($c_idauth), 'ISO-8859-1');
            $this->commercialTel = Tel($c_telephone) . ' - ' . $c_email;
        } else {
            $this->commercialName = $commercial;
            $this->commercialTel = Tel($s_telephone) . ' - ' . $s_email;
        }


        // $c_payeok = 1
        // $c_idauth = 54
        // $c_idusers = 6
        // $c_idcompte = C4X
        // $c_email = renaud@ejeri.fr
        // $c_username = abelard
        // $c_civilite = M.
        // $c_nom = ABELARD
        // $c_prenom = Renaud
        // $c_adresse =
        // $c_cp =
        // $c_ville =
        // $c_telephone = 06.59.23.80.28
        // $c_cte = 0
        // $c_actif = 1
        // $c_statut = Admin
        // $c_mail_valid = 0
        // $c_time = 2024-08-22 13:26:18
        /* #endregion */


        //*********************
        // INFOS CLIENT
        /* #region CLIENT */
        $client = $conn->askClient($id);
        foreach ($client as $key => $value) {
            ${'u_' . $key} = mb_convert_encoding($value, 'ISO-8859-1');
            //echo '$u_' . $key . ' = ' . $value . '<br>';
        }


        // $u_idcompte = C4X
        // $u_idcli = 4065
        // $u_civilite = M. et Mme
        // $u_nom = DOUET
        // $u_prenom = Jacqueline
        // $u_adresse = 14 Ourcelle
        // $u_cp = 41370
        // $u_ville = Josnes
        // $u_email = didier.rp.douet@gmail.com
        // $u_mailing = 1
        // $u_telephone = 06.85.71.44.80
        // $u_portable = 06.85.71.44.80
        // $u_datecrea = 26-06-2018
        // $u_time_maj = 2022-11-03 16:01:24


        $client = $conn->askClientAdresse($id);
        foreach ($client as $key => $value) {
            ${$key} = ($value);
            //echo '$' . $key . ' = ' . $value . '<br>';
        }

        // $idcompte = C4X
        // $idcli = 4986
        // $datecrea = 17-06-2021
        // $civifact = M.
        // $nomfact = POZZO
        // $prenomfact = Claude
        // $mention =
        // $adressfact = 7 Sentier Des Sous Lutz
        // $cpfact = 45190
        // $villefact = Beaugency
        // $time_maj = 2022-11-02 18:22:22

        $this->clientNum = mb_convert_encoding('N° client : ' . $idcli, 'ISO-8859-1');
        $this->clientNom = mb_convert_encoding(NomClient($idcli), 'ISO-8859-1');
        $this->clientAdresse = mb_convert_encoding($adressfact, 'ISO-8859-1');
        $this->clientVille = mb_convert_encoding($cpfact . ' ' . $villefact, 'ISO-8859-1');

        $adresse_chantier = trim(strtolower($u_adresse));
        $adresse_adresse = trim(strtolower($adressfact));
        $compare = strcmp($adresse_adresse, $adresse_chantier);
        $this->clientAdresseChantier = null;
        if ($compare != '0') {
            $this->clientAdresseChantier = 'Chantier : ' . $u_adresse . ' ' . $u_cp . ' ' . $u_ville;
        }
        /* #endregion */


        //*********************
        // INFOS BANK
        /* #region BANK */
        $idbank = $conn->askBankDefault($secteur);
        $this->secteurBank = strEncoding('Virement : ' . $idbank[0]['nom_bank'] . ' IBAN : ' . $idbank[0]['iban'] . ' BIC : ' . $idbank[0]['bic']);
        /* #endregion */
    }


    function Header()
    {
        // Utiliser les informations spécifiques à chaque facture
        if (!is_null(ISLOGO)) {
            $path_image = $this->chemin . '/documents/img/' . $this->secteur . '/logo.png';
            $h = definirHauteurImageProgressive($path_image);
            $this->pdf->Image($path_image, 8, 8, $h);
        }

        $this->pdf->SetFont('Nunito', 'B', 10);
        $this->pdf->Cell(0, 9,  $this->factureLieu, '', 1, 'R');
        $this->pdf->SetFont('Nunito', 'B', 20);
        $this->pdf->Cell(0, 9,  $this->factureType, '', 1, 'R');
        $this->pdf->SetFont('Nunito', 'B', 15);
        $this->pdf->Cell(0, 5,  $this->factureNumber, '', 1, 'R');
        $this->pdf->SetFont('Nunito', 'B', 9);
        $this->pdf->Cell(0, 5, 'Edition du ' . date('d/m/Y'), '', 1, 'R');
        $this->pdf->Ln(28);
        $y = $this->pdf->GetY();
        define('Y', $y);
        $this->pdf->SetFont('Nunito', 'B', 10);
        $this->pdf->SetY($y - 5);
        $this->pdf->Cell(100, 5, $this->clientNum, 0, 0, 'L');
        $this->pdf->Ln(25);
        $yy = $this->pdf->GetY(-25);
        define('YY', $yy);
    }

    function Footer()
    {
        $this->pdf->SetY(YY + 180);
        $this->pdf->SetFont('Nunito', 'B', 8);
        $this->pdf->Cell(190, 4, $this->secteurNom, 0, 1, 'C');
        $this->pdf->SetFont('Nunito', '', 8);
        $this->pdf->Cell(190, 4, strEncoding($this->secteurAdresse), 0, 1, 'C');
        $this->pdf->Cell(190, 4, $this->secteurInfos, 0, 1, 'C');
        $this->pdf->SetFont('Nunito', 'B', 7);
        $this->pdf->Cell(193, 7, 'Page ' . $this->pdf->PageNo() . '/{nb}', 0, 1, 'C');
    }

    public function Adresse()
    {
        $this->pdf->SetY(Y);
        $this->pdf->SetFont('Nunito', 'B', 10);
        $this->pdf->Cell(100, 5, 'Votre contact', 0, 0, 'L');
        $this->pdf->Cell(95, 5, $this->clientNom, 0, 1, 'L');
        $this->pdf->SetFont('Nunito', '', 10);
        $this->pdf->Cell(100, 5, $this->commercialName, 0, 0, 'L');
        $this->pdf->Cell(95, 5, $this->clientAdresse, 0, 1, 'L');
        $this->pdf->Cell(100, 5, $this->commercialTel, 0, 0, 'L');
        $this->pdf->Cell(95, 5, $this->clientVille, 0, 1, 'L');
        $this->pdf->SetFont('Nunito', 'B', 8);
        $this->pdf->Cell(100, 5, $this->clientAdresseChantier, 0, 0, 'L');
        $this->pdf->Ln(8);
    }

    public function InfosReg()
    {
        $this->pdf->SetFont('Nunito', 'BU', 8);
        $regc = strEncoding('Conditions de règlement : ');
        // $this->pdf->SetTextColor(0, 103, 40);
        $this->pdf->Cell(100, 5,  $regc, 0, 1, 'L');
        $this->pdf->SetFont('Nunito', 'U', 8);
        $this->pdf->Cell(100, 5,  $this->secteurModalite, 0, 1, 'L');
        $this->pdf->SetFont('Nunito', '', 8);
        $this->pdf->Cell(100, 5,  $this->secteurEscompte, 0, 1, 'L');
        $this->pdf->Cell(100, 5,  $this->secteurRetard, 0, 1, 'L');
        $this->pdf->Cell(100, 5,  $this->secteurPenal, 0, 1, 'L');

        $this->pdf->Ln(8);
        // $this->pdf->SetY(YY + 155);
        $adrenvoi = strEncoding('Envoi du chèque : ' . $this->secteurAdresse);
        $this->pdf->SetFont('Nunito', 'B', 8);
        $this->pdf->Cell(25, 6, $adrenvoi, 0, 1, 'L');
        $adrenvoi = strEncoding('Virement : ');
        $this->pdf->SetFont('Nunito', 'B', 8);
        $this->pdf->Cell(25, 6, $this->secteurBank, 0, 1, 'L');
        $this->pdf->SetTextColor(255, 0, 38);
        $notation = strEncoding('Merci de noter le numéro de facture lors de vos virements ou au dos du chèque.');

        $this->pdf->SetFont('Nunito', 'B', 8);
        $this->pdf->Cell(25, 5, $notation, 0, 1, 'L');
        $this->pdf->SetTextColor(0, 0, 0);
    }

    public function getMontant($numero)
    {
        $devis = new Factures($this->secteur);
        $montant = $devis->askFacturesEntete($numero);
        return $montant;
    }

    public function Lignes($numero)
    {
        $devis = new Factures($this->secteur);
        $lignes = $devis->askFacturesLignes($numero);
        $entete = $devis->askFacturesEntete($numero);
        $acompte = $devis->askAcompte($entete['devref'], $this->clientNum);
        //echo $entete['devref'];
        //var_dump($entete);
        if ($acompte['montant']) {
            $acompte_client = $acompte['montant'];
        } else {
            $acompte_client = 0;
        }
        $acompte_client = 0;
        $desi = mb_convert_encoding('Désignation', 'ISO-8859-1');
        $titre = mb_convert_encoding($entete['titre'], 'ISO-8859-1');
        $titre = $titre === '' ? 'Prestation' : $titre;
        $this->pdf->SetFont('Nunito', 'B', 9);
        $this->pdf->Cell(190, 4 + 2, $titre, 'B', 1, 'L');
        $this->pdf->Ln(4);
        $this->pdf->Cell(115, 4 + 1, $desi, 0, 0, 'L');
        $this->pdf->Cell(25, 4 + 1, 'Q.', 0, 0, 'R');
        $this->pdf->Cell(25, 4 + 1, 'PU', 0, 0, 'R');
        $this->pdf->Cell(25, 4 + 1, 'PTTC', 0, 1, 'R');
        $this->pdf->Ln(5);
        foreach ($lignes as $l) {
            $this->pdf->SetFont('Nunito', '', 9);
            $desi = mb_convert_encoding($l['designation'], 'ISO-8859-1');
            //$this->pdf->Cell(95, 4 + 1, $desi, 0, 0, 'L');
            if ($l['q'] === 0.00 || $l['puttc'] === 0.00 || $l['ptttc'] === 0.00) {
                $largeurWW = 195;
            } else {
                $largeurWW = 105;
            }
            $d = $this->pdf->WordWrap($desi, $largeurWW);
            //$this->pdf->MultiCell(115,4,$d,0,'L',0);
            //$this->pdf->Cell(95, 4 + 1, $d, 0, 0, 'L');
            $this->pdf->Write(4 + 1, $d);
            $this->pdf->SetX(125);
            $q = $l['q'] === 0.00 ? "" : Dec_2($l['q']);
            $this->pdf->Cell(25, 4 + 1, $q, 0, 0, 'R');
            $pu = $l['puttc'] === 0.00 ? "" : Dec_2($l['puttc']);
            $this->pdf->Cell(25, 4 + 1, $pu, 0, 0, 'R');
            $tot = $l['ptttc'] === 0.00 ? "" : Dec_2($l['ptttc']);
            $this->pdf->Cell(25, 4 + 1, $tot, 0, 1, 'R');
            $this->pdf->Ln(3);
        }
        $this->pdf->SetFont('Nunito', '', 9);
        $montant_tva =  mb_convert_encoding('Montant TVA à ' . $this->secteurTVA . '%', 'ISO-8859-1');
        $this->pdf->Cell(190, 4 + 1, '', 'T', 1, 'L');
        $y = $this->pdf->GetY();
        $this->pdf->SetY($y + 36);
        $this->pdf->SetFont('Nunito', '', 9);
        $this->pdf->SetY($y + 4);
        $this->pdf->SetX(125);
        $this->pdf->Cell(25, 4 + 1, 'Montant HT', 0, 0, 'L');
        $this->pdf->Cell(25, 4 + 1, '', 0, 0, 'L');
        $this->pdf->Cell(25, 4 + 1, $ht = Dec_2(($entete['totttc'] + $acompte_client) / 1.2), 0, 1, 'R');
        $this->pdf->Cell(115, 4 + 1, '', 0, 0, 'R');
        $this->pdf->Cell(25, 4 + 1, $montant_tva, 0, 0, 'L');
        $this->pdf->Cell(25, 4 + 1, '', 0, 0, 'L');
        $this->pdf->Cell(25, 4 + 1, Dec_2($entete['totttc'] + $acompte_client - $ht), 0, 1, 'R');
        $this->pdf->Cell(115, 4 + 1, '', 0, 0, 'R');
        $this->pdf->SetFont('Nunito', 'B', 9);
        $montant_a_payer = mb_convert_encoding('Montant à payer TTC', 'ISO-8859-1');
        $this->pdf->Cell(25, 4 + 1, $montant_a_payer, 0, 0, 'L');
        $this->pdf->Cell(25, 4 + 1, '', 0, 0, 'L');
        $this->pdf->Cell(25, 4 + 1, Dec_2($entete['totttc'] + $acompte_client), 0, 1, 'R');
        if ($acompte_client > 0) {
            $this->pdf->Cell(115, 4 + 1, '', 0, 0, 'R');
            $this->pdf->SetFont('Nunito', '', 9);
            $acompte_verse = mb_convert_encoding('Acompte versé', 'ISO-8859-1');
            $this->pdf->Cell(25, 4 + 1, $acompte_verse, 0, 0, 'L');
            $this->pdf->Cell(25, 4 + 1, '', 0, 0, 'L');
            $this->pdf->Cell(25, 4 + 1, Dec_2($acompte_client), 0, 1, 'R');

            $this->pdf->Cell(115, 4 + 1, '', 0, 0, 'R');
            $this->pdf->SetFont('Nunito', 'B', 9);
            $acompte_verse = mb_convert_encoding('Solde', 'ISO-8859-1');
            $this->pdf->Cell(25, 4 + 1, $acompte_verse, 0, 0, 'L');
            $this->pdf->Cell(25, 4 + 1, '', 0, 0, 'L');
            $this->pdf->Cell(25, 4 + 1, Dec_2($entete['totttc']), 0, 1, 'R');
        }
        if ($this->secteurSAP != null) {

            $montant_reduction = Dec_2(($entete['totttc'] + $acompte_client) / 2);
            $reduc = $montant_reduction > 5000 ? 5000 : $montant_reduction;
            $reduction = mb_convert_encoding('Réduction d\'impôts envisagée', 'ISO-8859-1');
            $this->pdf->SetFont('Nunito', 'B', 9);
            $this->pdf->Cell(115, 4 + 1, '', 0, 0, 'R');
            $this->pdf->SetTextColor(160, 160, 160);
            $this->pdf->Cell(25, 4 + 1, $reduction, 0, 0, 'L');
            $this->pdf->Cell(25, 4 + 1, '', 0, 0, 'L');
            $this->pdf->Cell(25, 4 + 1,  Dec_2($reduc), 0, 1, 'R');
            $this->pdf->SetTextColor(0, 0, 0);
        }
        $this->pdf->Ln(8);

        if ($this->facturePaye === 'oui') {
            $y = $this->pdf->GetY();
            $this->pdf->Image('../../../assets/img/payee_trans.png', 85, 80, 30);
        }
    }


    public function Payee($path, $x, $a)
    {
        $y = $this->GetY();
        $this->Image($path, $x, $y - $a - 14, 30);
    }
}
