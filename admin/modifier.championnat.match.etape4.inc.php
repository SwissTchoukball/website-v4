<?
?>
<h3>
<? echo VAR_LANG_ETAPE_4; ?>
</h3>
<?
if(!isset($_POST['idMatch'])){
	echo"Erreur: il manque des informations.";
}
else{
	$idMatch = $_POST['idMatch'];
	$saison = $_POST['saison'];
	$idCategorie = $_POST['idCategorie'];
	$idTour = $_POST['idTour'];
	$idGroupe = $_POST['idGroupe'];

	// Sélection de la politique des points de l'année choisie.
	$retour = mysql_query("SELECT * FROM Championnat_Saisons WHERE saison=".$saison."");
	$donnees = mysql_fetch_array($retour);
	$pointsMatchGagne = $donnees['pointsMatchGagne'];
	$pointsMatchNul = $donnees['pointsMatchNul'];
	$pointsMatchPerdu = $donnees['pointsMatchPerdu'];
	$pointsMatchForfait = $donnees['pointsMatchForfait'];
	$scoreGagnantParForfait = $donnees['scoreGagnantParForfait'];
	$nbMatchGagnantPlayoff = $donnees['nbMatchGagnatPlayoff'];
	$nbMatchGagnantPlayout = $donnees['nbMatchGagnantPlayout'];
	$nbMatchGagnatPromoReleg = $donnees['nbMatchGagnatPromoReleg'];
	$systemePassageTours = $donnees['systemePassageTours'];

    $idTypeMatchValide = false;

    if($idCategorie == 0){ // Promotion / Relegation
        $idTypeMatch = 1000;
        $idTypeMatchValide = true;
        $idGroupe=0;
        $idTour=2000;
    }
    elseif($idTour == 1 OR $idTour == 2 OR $idTour == 3 OR $idTour == 4){
        $idTypeMatch = 0;
        $idTypeMatchValide = true;
    }
    elseif(!isset($_POST['idTypeMatch'])){
        $idTypeMatchValide = false;
    }
    else{
        $idTypeMatch = $_POST['idTypeMatch'];
        $idTypeMatchValide = true;
    }

    if($idTour == 10000 OR $idTour == 3000 OR $idTour == 4000){
        $idGroupe = 0;
    }

    $forfait = 0;
    $periodScoreA = array();
    $periodScoreB = array();
    if (isset($_POST['AGagneParForfait'])) {
        $forfait = 1;
        $periodTypeId[1] = 5; // Match forfait
        $periodScoreA[1] = $scoreGagnantParForfait;
        $periodScoreB[1] = 0;
    } elseif (isset($_POST['BGagneParForfait'])) {
        $forfait = 1;
        $periodTypeId[1] = 5; // Match forfait
        $periodScoreA[1] = 0;
        $periodScoreB[1] = $scoreGagnantParForfait;
    } elseif (isset($_POST['periodScoreA']) && isset($_POST['periodScoreB']) && isset($_POST['periodTypeId'])) {
	    $periodScoreA = $_POST['periodScoreA'];
	    $periodScoreB = $_POST['periodScoreB'];
	    $periodTypeId = $_POST['periodTypeId'];
	    $periodRefereeA = $_POST['periodRefereeA'];
	    $periodRefereeB = $_POST['periodRefereeB'];
	    $periodRefereeC = $_POST['periodRefereeC'];
	} else {
        printErrorMessage('Score introuvable');
    }
    $pointsA = array_sum($periodScoreA);
    $pointsB = array_sum($periodScoreB);

    $scoreValide = is_numeric($pointsA) && is_numeric($pointsB);

    if (isset($_POST['necessiteDefraiementArbitre'])) {
    	$necessiteDefraiementArbitre=1;
    } else {
    	$necessiteDefraiementArbitre=0;
    }

    if(isset($_POST['matchReporte'])){
        $dateReportDebut = $_POST['debutAnneeReport']."-".$_POST['debutMoisReport']."-".$_POST['debutJourReport'];
        $dateReportFin = $_POST['finAnneeReport']."-".$_POST['finMoisReport']."-".$_POST['finJourReport'];
        $heureReportDebut = $_POST['debutHeureReport'].":".$_POST['debutMinuteReport'].":00";
        $heureReportFin = $_POST['finHeureReport'].":".$_POST['finMinuteReport'].":00";
    }
    else{
        $dateReportDebut = "0000-00-00";
        $dateReportFin = "0000-00-00";
        $heureReportDebut = "00:00:00";
        $heureReportFin = "00:00:00";
    }

    $idLieu = isValidVenueID($_POST['idLieu']) ? $_POST['idLieu'] : 'NULL';

    $nbSpectateurs = is_numeric($_POST['nbSpectateurs']) ? $_POST['nbSpectateurs'] : 'NULL';

    if($idTypeMatchValide && $scoreValide){
        $requeteA = "SELECT equipe FROM Championnat_Equipes WHERE idEquipe=".$_POST['equipeA']."";
        $retourA = mysql_query($requeteA);
        $donneesA = mysql_fetch_array($retourA);
        $nomEquipeA = $donneesA['equipe'];

        $requeteB = "SELECT equipe FROM Championnat_Equipes WHERE idEquipe=".$_POST['equipeB']."";
        $retourB = mysql_query($requeteB);
        $donneesB = mysql_fetch_array($retourB);
        $nomEquipeB = $donneesB['equipe'];

		// Pour l'instant on continue d'enregistrer le score total dans Championnat_Matchs
		// car ce sont des informations qui sont souvent utilisées, notamment pour calculer
		// le classement, même si l'information est redondante avec ce qui est enregistré
		// dans Championnat_Periodes.
        $requete = "UPDATE `Championnat_Matchs`
        			SET equipeA=".$_POST['equipeA'].",
        				equipeB=".$_POST['equipeB'].",
        				pointsA=".$pointsA.",
        				pointsB=".$pointsB.",
        				forfait=".$forfait.",
        				saison=".$saison.",
        				idCategorie=".$idCategorie.",
        				idTour=".$idTour.",
        				idTypeMatch=".$idTypeMatch.",
        				noGroupe=".$idGroupe.",
        				journee=".$_POST['journee'].",
        				dateDebut='".$_POST['debutAnnee']."-".$_POST['debutMois']."-".$_POST['debutJour']."',
						dateFin='".$_POST['finAnnee']."-".$_POST['finMois']."-".$_POST['finJour']."',
						heureDebut='".$_POST['debutHeure'].":".$_POST['debutMinute'].":00',
						heureFin='".$_POST['finHeure'].":".$_POST['finMinute'].":00',
						dateReportDebut='".$dateReportDebut."',
						dateReportFin='".$dateReportFin."',
						heureReportDebut='".$heureReportDebut."',
						heureReportFin='".$heureReportFin."',
						necessiteDefraiementArbitre=".$necessiteDefraiementArbitre.",
						idLieu='".$idLieu."',
						nbSpectateurs='".$nbSpectateurs."',
						utilisateur='".$_SESSION['__prenom__'].$_SESSION['__nom__']."'
        			WHERE idMatch=".$idMatch."";
        //echo $requete;
        mysql_query($requete) or die(printErrorMessage("Le match ".$nomEquipeA." - ".$nomEquipeB." n'a pas été modifié.<br />Requête : ".$requete."<br />Message : ".mysql_error()));
        printSuccessMessage($nomEquipeA." - ".$nomEquipeB." : OK !");

        $requetePeriodeVidange = "DELETE FROM Championnat_Periodes WHERE idMatch=" . $idMatch;
        if (mysql_query($requetePeriodeVidange)) {
	        if (isAdmin()) {
		        printMessage("Toutes les périodes du match ont été supprimées.");
	        }
        } else {
	        printErrorMessage("La suppression des périodes ne s'est pas correctement effectuée.");
        }

        for ($p = 1; $p <= count($periodScoreA); $p++) {

			if ($periodRefereeA[$p] == '') {
				$periodRefereeAID = "NULL";
			} else {
				$periodRefereeAID = $periodRefereeA[$p];
			}

			if ($periodRefereeB[$p] == '') {
				$periodRefereeBID = "NULL";
			} else {
				$periodRefereeBID = $periodRefereeB[$p];
			}

			if ($periodRefereeC[$p] == '') {
				$periodRefereeCID = "NULL";
			} else {
				$periodRefereeCID = $periodRefereeC[$p];
			}

	        $requetePeriode = "INSERT INTO Championnat_Periodes (idMatch, noPeriode, idTypePeriode, scoreA, scoreB, idArbitreA, idArbitreB, idArbitreC)
	        				   VALUES(" . $idMatch . ", " . $p . ", " . $periodTypeId[$p] . ", " . $periodScoreA[$p] . ", " . $periodScoreB[$p] . ", " . $periodRefereeAID . ", " . $periodRefereeBID . ", " . $periodRefereeCID . ")
	        				   ON DUPLICATE KEY UPDATE idTypePeriode=" . $periodTypeId[$p] . ",
	        				   						   scoreA=" . $periodScoreA[$p] . ",
	        				   						   scoreB=" . $periodScoreB[$p] . ",
	        				   						   idArbitreA=" . $periodRefereeAID . ",
	        				   						   idArbitreB=" . $periodRefereeBID . ",
	        				   						   idArbitreC=" . $periodRefereeCID;
	        mysql_query($requetePeriode) or die(printErrorMessage("La période n° " . $p . " n'a pas pu être insérée/modifiée. Les périodes précédentes ont pu être traitées.<br />
	        													   Requête : ".$requetePeriode."<br />
	        													   Message : ".mysql_error()));
	        if (isAdmin()) {
		        printSuccessMessage("La période n° " . $p . " a été insérée/modifiée.");
	        }
        }
    } elseif (!$idTypeMatchValide) {
	    printErrorMessage('ID du type de match invalide');
    } elseif (!$scoreValide) {
	    printErrorMessage('Score invalide');
    } else {
	    printErrorMessage('Erreur inconnue');
    }

	//Mise à jour des points d'arbitres
    computeAndSaveRefereeChampionshipPoints($saison, $idCategorie);

    // Redénomination des variables pour l'include suivant.
	$categorie = $idCategorie;
	$tour = $idTour;
	$groupe = $idGroupe;
	include('championnat.miseajour.equipes.tour.inc.php');


	//Insertion pour technique.tchoukball.ch
	@mysql_select_db($sql['basetechnique']);

    $idArbitreATiers1 = $idArbitreATiers1 == 'NULL' ? 0 : $idArbitreATiers1;
    $idArbitreATiers2 = $idArbitreATiers2 == 'NULL' ? 0 : $idArbitreATiers2;
    $idArbitreATiers3 = $idArbitreATiers3 == 'NULL' ? 0 : $idArbitreATiers3;
    $idArbitreBTiers1 = $idArbitreBTiers1 == 'NULL' ? 0 : $idArbitreBTiers1;
    $idArbitreBTiers2 = $idArbitreBTiers2 == 'NULL' ? 0 : $idArbitreBTiers2;
    $idArbitreBTiers3 = $idArbitreBTiers3 == 'NULL' ? 0 : $idArbitreBTiers3;
    $idArbitreCTiers1 = $idArbitreCTiers1 == 'NULL' ? 0 : $idArbitreCTiers1;
    $idArbitreCTiers2 = $idArbitreCTiers2 == 'NULL' ? 0 : $idArbitreCTiers2;
    $idArbitreCTiers3 = $idArbitreCTiers3 == 'NULL' ? 0 : $idArbitreCTiers3;

	$arbitrages = $idArbitreATiers1.";".$idArbitreBTiers1.";".$idArbitreCTiers1.";".$idArbitreATiers2.";".$idArbitreBTiers2.";".$idArbitreCTiers2.";".$idArbitreATiers3.";".$idArbitreBTiers3.";".$idArbitreCTiers3;

	$requeteSuppressionFeuilleTechnique = "DELETE FROM feuilles WHERE feu_match=".$idMatch;
	if (mysql_query($requeteSuppressionFeuilleTechnique)) {
		$requeteAjoutFeuilleTechnique = "INSERT INTO feuilles(feu_match,feu_arbitres)VALUES(".$idMatch.",'".$arbitrages."')";
		if (mysql_query($requeteAjoutFeuilleTechnique)) {
			echo '<p class="success">Insertion sur technique.tchoukball.ch réussie.</p>';
		} else {
			echo '<p class="error">Erreur lors de l\'insertion sur technique.tchoukball.ch<br />Requête : '.$requeteAjoutFeuilleTechnique.'<br />Message : '.mysql_error().'</p>';
		}
	} else {
		echo '<p class="error">Erreur lors de l\'écrasement de précédentes données sur technique.tchoukball.ch<br />Requête : '.$requeteSuppressionFeuilleTechnique.'<br />Message : '.mysql_error().'</p>';
	}

	@mysql_select_db($sql['base']);
}
?>
<form name="remodification" action="" >
<input type="hidden" name="menuselection" value="<? echo $menuselection; ?>" />
<input type="hidden" name="smenuselection" value="<? echo $smenuselection; ?>" />
<p class="center"><input type="submit" value="Modifier d'autres matchs" /></p>
</form>
<form name="remodificationMemePhase" action="" >
<input type="hidden" name="menuselection" value="<? echo $menuselection; ?>" />
<input type="hidden" name="smenuselection" value="<? echo $smenuselection; ?>" />
<input type="hidden" name="saison" value="<? echo $saison; ?>" />
<input type="hidden" name="idCat" value="<? echo $idCategorie; ?>" />
<input type="hidden" name="idTour" value="<? echo $idTour; ?>" />
<input type="hidden" name="idGroupe" value="<? echo $idGroupe; ?>" />
<p class="center"><input type="submit" value="Modifier d'autres matchs de la même phase" /></p>
</form>


