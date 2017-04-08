<h3>
    <?php echo VAR_LANG_ETAPE_4; ?>
</h3>
<?php
if (!isset($_POST['idMatch'])) {
    echo "Erreur: il manque des informations.";
} else {
    $idMatch = $_POST['idMatch'];
    $saison = $_POST['saison'];
    $idCategorie = $_POST['idCategorie'];
    $idTour = $_POST['idTour'];
    $idGroupe = $_POST['idGroupe'];
    $idEquipeA = $_POST['equipeA'];
    $idEquipeB = $_POST['equipeB'];

    // Récupération des types de pénalités
    $penaltiesTypesQuery = "SELECT id, name" . $_SESSION['__langue__'] . " AS name, attributPenalite
                           FROM Championnat_Types_Penalites
                           ORDER BY name";
    $penaltiesTypes = [];
    if ($penaltiesTypesResult = mysql_query($penaltiesTypesQuery)) {
        while ($penaltyType = mysql_fetch_assoc($penaltiesTypesResult)) {
            $penaltiesTypes[$penaltyType['id']] = $penaltyType;
        }
    } else {
        printErrorMessage('Problème lors de la récupération des types de pénalités');
    }

    // Récupération des points de pénalités par type
    foreach ($penaltiesTypes as $penaltyType) {
        $penaltyPointsQuery = "SELECT " . $penaltyType['attributPenalite'] . " AS penaltyPoints
                               FROM Championnat_Saisons
                               WHERE saison=" . $saison . "
                               LIMIT 1";
        if ($penaltyPointsResult = mysql_query($penaltyPointsQuery)) {
            $penaltyPoints = mysql_fetch_assoc($penaltyPointsResult);
            $penaltiesTypes[$penaltyType['id']]['penaltyPoints'] = $penaltyPoints['penaltyPoints'];
        } else {
            printErrorMessage('Problème lors de la récupération des points de pénalités<br />' .
                mysql_error() . '<br />' . $penaltyPointsQuery);
        }
    }

    // Sélection de la politique des points de l'année choisie.
    $retour = mysql_query("SELECT * FROM Championnat_Saisons WHERE saison=" . $saison);
    $donnees = mysql_fetch_array($retour);
    $pointsMatchGagne = $donnees['pointsMatchGagne'];
    $pointsMatchNul = $donnees['pointsMatchNul'];
    $pointsMatchPerdu = $donnees['pointsMatchPerdu'];
    $pointsMatchForfait = $donnees['pointsMatchForfait'];
    $scoreGagnantParForfait = $donnees['scoreGagnantParForfait'];
    $nbMatchGagnantPlayoff = $donnees['nbMatchGagnatPlayoff'];
    $nbMatchGagnantPlayout = $donnees['nbMatchGagnantPlayout'];
    $nbMatchGagnantPromoReleg = $donnees['nbMatchGagnantPromoReleg'];
    $systemePassageTours = $donnees['systemePassageTours'];

    $idTypeMatchValide = false;

    if ($idCategorie == 0) { // Promotion / Relegation
        $idTypeMatch = 1000;
        $idTypeMatchValide = true;
        $idGroupe = 0;
        $idTour = 2000;
    } elseif ($idTour == 1 OR $idTour == 2 OR $idTour == 3 OR $idTour == 4) {
        $idTypeMatch = 0;
        $idTypeMatchValide = true;
    } elseif (!isset($_POST['idTypeMatch'])) {
        $idTypeMatchValide = false;
    } else {
        $idTypeMatch = $_POST['idTypeMatch'];
        $idTypeMatchValide = true;
    }

    if ($idTour == 10000 OR $idTour == 3000 OR $idTour == 4000) {
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

    $penaltiesIds = $_POST['penaltiesIds'];
    $penaltiesIds = explode(',', $penaltiesIds);
    $deletedPenaltiesIds = $_POST['deletedPenaltiesIds'];
    $deletedPenaltiesIds = explode(',', $deletedPenaltiesIds);
    $penalties = array();
    foreach ($penaltiesIds as $penaltyId) {
        if ($penaltyId != '') {
            $penalties[$penaltyId]['teamId'] = $_POST['penalty-' . $penaltyId . '-teamId'];
            $penalties[$penaltyId]['typeId'] = $_POST['penalty-' . $penaltyId . '-typeId'];
            $penalties[$penaltyId]['points'] = $penaltiesTypes[$penalties[$penaltyId]['typeId']]['penaltyPoints'];

            if ($penalties[$penaltyId]['teamId'] == $idEquipeA) {
                $pointsA += $penalties[$penaltyId]['points'];
            } else if ($penalties[$penaltyId]['teamId'] == $idEquipeB) {
                $pointsB += $penalties[$penaltyId]['points'];
            }
        }
    }

    $scoreValide = is_numeric($pointsA) && is_numeric($pointsB);


    if (isset($_POST['necessiteDefraiementArbitre'])) {
        $necessiteDefraiementArbitre = 1;
    } else {
        $necessiteDefraiementArbitre = 0;
    }

    if (isset($_POST['matchReporte'])) {
        $dateReportDebut = $_POST['debutAnneeReport'] . "-" . $_POST['debutMoisReport'] . "-" . $_POST['debutJourReport'];
        $dateReportFin = $_POST['finAnneeReport'] . "-" . $_POST['finMoisReport'] . "-" . $_POST['finJourReport'];
        $heureReportDebut = $_POST['debutHeureReport'] . ":" . $_POST['debutMinuteReport'] . ":00";
        $heureReportFin = $_POST['finHeureReport'] . ":" . $_POST['finMinuteReport'] . ":00";
    } else {
        $dateReportDebut = "0000-00-00";
        $dateReportFin = "0000-00-00";
        $heureReportDebut = "00:00:00";
        $heureReportFin = "00:00:00";
    }

    $idLieu = isValidVenueID($_POST['idLieu']) ? $_POST['idLieu'] : 'NULL';

    $nbSpectateurs = is_numeric($_POST['nbSpectateurs']) ? $_POST['nbSpectateurs'] : 'NULL';

    if ($idTypeMatchValide && $scoreValide) {
        $requeteA = "SELECT equipe FROM Championnat_Equipes WHERE idEquipe=" . $_POST['equipeA'];
        $retourA = mysql_query($requeteA);
        $donneesA = mysql_fetch_array($retourA);
        $nomEquipeA = $donneesA['equipe'];

        $requeteB = "SELECT equipe FROM Championnat_Equipes WHERE idEquipe=" . $_POST['equipeB'];
        $retourB = mysql_query($requeteB);
        $donneesB = mysql_fetch_array($retourB);
        $nomEquipeB = $donneesB['equipe'];

        // Pour l'instant on continue d'enregistrer le score total dans Championnat_Matchs
        // car ce sont des informations qui sont souvent utilisées, notamment pour calculer
        // le classement, même si l'information est redondante avec ce qui est enregistré
        // dans Championnat_Periodes.
        $requete = "UPDATE `Championnat_Matchs`
        			SET equipeA=" . $idEquipeA . ",
        				equipeB=" . $idEquipeB . ",
        				pointsA=" . $pointsA . ",
        				pointsB=" . $pointsB . ",
        				forfait=" . $forfait . ",
        				saison=" . $saison . ",
        				idCategorie=" . $idCategorie . ",
        				idTour=" . $idTour . ",
        				idTypeMatch=" . $idTypeMatch . ",
        				noGroupe=" . $idGroupe . ",
        				journee=" . $_POST['journee'] . ",
        				dateDebut='" . $_POST['debutAnnee'] . "-" . $_POST['debutMois'] . "-" . $_POST['debutJour'] . "',
						dateFin='" . $_POST['finAnnee'] . "-" . $_POST['finMois'] . "-" . $_POST['finJour'] . "',
						heureDebut='" . $_POST['debutHeure'] . ":" . $_POST['debutMinute'] . ":00',
						heureFin='" . $_POST['finHeure'] . ":" . $_POST['finMinute'] . ":00',
						dateReportDebut='" . $dateReportDebut . "',
						dateReportFin='" . $dateReportFin . "',
						heureReportDebut='" . $heureReportDebut . "',
						heureReportFin='" . $heureReportFin . "',
						necessiteDefraiementArbitre=" . $necessiteDefraiementArbitre . ",
						idLieu='" . $idLieu . "',
						nbSpectateurs='" . $nbSpectateurs . "',
						utilisateur='" . $_SESSION['__prenom__'] . $_SESSION['__nom__'] . "'
        			WHERE idMatch=" . $idMatch;
        //echo $requete;
        mysql_query($requete) or die(printErrorMessage("Le match " . $nomEquipeA . " - " . $nomEquipeB . " n'a pas été modifié.<br />Requête : " . $requete . "<br />Message : " . mysql_error()));
        printSuccessMessage($nomEquipeA . " - " . $nomEquipeB . " : OK !");


        // As some periods might have been delete in the form, we first delete all the periods of the match from
        // the database and then, add all the ones we receive from the previous step.
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
            if (!mysql_query($requetePeriode)) {
                printErrorMessage("La période n° " . $p . " n'a pas pu être insérée/modifiée.
                                   Les périodes précédentes ont pu être traitées.<br />
                                   Requête : " . $requetePeriode . "<br />
                                   Message : " . mysql_error());
                exit;
            }
            if (isAdmin()) {
                printSuccessMessage("La période n° " . $p . " a été insérée/modifiée.");
            }
        }

        foreach ($penalties as $penaltyId => $penalty) {
            // Newly added penalties have the string "new" in their id and therefore aren't numeric
            if (is_numeric($penaltyId)) {
                $penaltyQuery = "UPDATE Championnat_Penalites
                                 SET idTypePenalite = " . $penalty['typeId'] . ",
                                     idEquipePenalise = " . $penalty['teamId'] . "
                                 WHERE id = " . $penaltyId;
            } else {
                $penaltyQuery = "INSERT INTO Championnat_Penalites (idMatch, idTypePenalite, idEquipePenalise)
                                 VALUES(" . $idMatch . ", " . $penalty['typeId'] . ", " . $penalty['teamId'] . ")";
            }
            if (mysql_query($penaltyQuery)) {
                if (isAdmin()) {
                    printSuccessMessage("Pénalité ajoutée/mise à jour");
                }
            } else {
                printErrorMessage("La pénalité n'as pas pu être insérée/modifiée
                                   Les pénalités précédentes ont pu être traitées.<br />
                                   Requête : " . $penaltyQuery . "<br />
                                   Message : " . mysql_error());
                exit;
            }
        }

        foreach ($deletedPenaltiesIds as $penaltyId) {
            if (is_numeric($penaltyId)) {
                $penaltyQuery = "DELETE FROM Championnat_Penalites WHERE id=" . $penaltyId;
                if (mysql_query($penaltyQuery)) {
                    if (isAdmin()) {
                        printSuccessMessage("Pénalité supprimée");
                    }
                } else {
                    printErrorMessage("La pénalité n'as pas pu être supprimée
                                       Les périodes précédentes ont pu être traitées.<br />
                                       Requête : " . $penaltyQuery . "<br />
                                       Message : " . mysql_error());
                    exit;
                }
            } else {
                //If it is a new penalty that was deleted in the form, then it doesn't exist in the database.
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
}
?>
<form name="remodification" action="">
    <input type="hidden" name="menuselection" value="<?php echo $menuselection; ?>"/>
    <input type="hidden" name="smenuselection" value="<?php echo $smenuselection; ?>"/>
    <p class="center"><input type="submit" class="button" value="Modifier d'autres matchs"/></p>
</form>
<form name="remodificationMemePhase" action="">
    <input type="hidden" name="menuselection" value="<?php echo $menuselection; ?>"/>
    <input type="hidden" name="smenuselection" value="<?php echo $smenuselection; ?>"/>
    <input type="hidden" name="saison" value="<?php echo $saison; ?>"/>
    <input type="hidden" name="idCat" value="<?php echo $idCategorie; ?>"/>
    <input type="hidden" name="idTour" value="<?php echo $idTour; ?>"/>
    <input type="hidden" name="idGroupe" value="<?php echo $idGroupe; ?>"/>
    <p class="center"><input type="submit" class="button button--primary" value="Modifier d'autres matchs de la même phase"/></p>
</form>


