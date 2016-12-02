<?php
statInsererPageSurf(__FILE__);
?>

<?php

if (isset($_POST["action"]) && $_POST["action"] == "insererMatch") {

    $participantRecevant = $_POST["participantRecevant"];
    $participantVisiteur = $_POST["participantVisiteur"];
    $pointRecevant = $_POST["pointRecevant"];
    $pointVisiteur = $_POST["pointVisiteur"];
    $recevantGagneParForfait = $_POST["recevantGagneParForfait"];
    $visiteurGagneParForfait = $_POST["visiteurGagneParForfait"];
    $saison = $_POST["saison"];
    $tour = $_POST["tour"];
    $groupe = $_POST["groupe"];
    $journee = $_POST["journee"];

    $lieu = $_POST["lieu"];
    $debutJour = $_POST["debutJour"];
    $debutMois = $_POST["debutMois"];
    $debutAnnee = $_POST["debutAnnee"];
    $debutHeure = $_POST["debutHeure"];
    $debutMinute = $_POST["debutMinute"];

    $finJour = $_POST["finJour"];
    $finMois = $_POST["finMois"];
    $finAnnee = $_POST["finAnnee"];
    $finHeure = $_POST["finHeure"];
    $finMinute = $_POST["finMinute"];

    $matchReporte = $_POST["matchReporte"];

    $lieuReporteAu = $_POST["lieuReporteAu"];
    $debutJourReporteAu = $_POST["debutJourReporteAu"];
    $debutMoisReporteAu = $_POST["debutMoisReporteAu"];
    $debutAnneeReporteAu = $_POST["debutAnneeReporteAu"];
    $debutHeureReporteAu = $_POST["debutHeureReporteAu"];
    $debutMinuteReporteAu = $_POST["debutMinuteReporteAu"];

    $finJourReporteAu = $_POST["finJourReporteAu"];
    $finMoisReporteAu = $_POST["finMoisReporteAu"];
    $finAnneeReporteAu = $_POST["finAnneeReporteAu"];
    $finHeureReporteAu = $_POST["finHeureReporteAu"];
    $finMinuteReporteAu = $_POST["finMinuteReporteAu"];

    $descriptionFr = $_POST["descriptionFr"];

    $typeMatch = 5000; // 5000 dans l'agenda

    $forfait = 0;
    if ($recevantGagneParForfait) {
        $forfait = 1;
    }
    if ($visiteurGagneParForfait) {
        $forfait = 2;
    }

    $idAgenda = 0;
    $idAgendaReporteAu = -1;

    $utilisateur = $_SESSION["__nom__"] . $_SESSION["__prenom__"];


    $nbErreur = 0;

    // equipe se trouvant dans le tour selectionne.

    $requeteSQL = "SELECT DISTINCT * FROM `ChampionnatPtDepartClassement` WHERE " .
        "`saison`='$saison' AND `tour`='$tour' AND `groupe`='$groupe' AND `idParticipant`='$participantRecevant'";
    $recordset = mysql_query($requeteSQL);
    if (mysql_num_rows($recordset) != 1) {
        $nbErreur++;
    }

    $requeteSQL = "SELECT DISTINCT * FROM `ChampionnatPtDepartClassement` WHERE " .
        "`saison`='$saison' AND `tour`='$tour' AND `groupe`='$groupe' AND `idParticipant`='$participantVisiteur'";
    $recordset = mysql_query($requeteSQL);
    if (mysql_num_rows($recordset) != 1) {
        $nbErreur++;
    }

    if ($nbErreur == 0) {
        // test si la date est valide
        if (dateDebutFinValide($debutAnnee, $debutMois, $debutJour, $debutHeure, $debutMinute,
            $finAnnee, $finMois, $finJour, $finHeure, $finMinute)) {

            // si le match est reporte, tester la date
            if ($matchReporte && !dateDebutFinValide($debutAnneeReporteAu, $debutMoisReporteAu, $debutJourReporteAu,
                    $debutHeureReporteAu, $debutMinuteReporteAu,
                    $finAnneeReporteAu, $finMoisReporteAu, $finJourReporteAu, $finHeureReporteAu, $finMinuteReporteAu)
            ) {
                $nbErreur++;
                echo "<h4>date de match reporté incorrecte</h4>";
            }

            // valeur 11 => participant inconnu dans la BD
            // meme participant (sauf deux inconnus) ou un seul inconnu => erreur
            if (($participantRecevant == $participantVisiteur && $participantRecevant != 11) ||
                ($participantRecevant == 11 && $participantVisiteur != 11) ||
                ($participantRecevant != 11 && $participantVisiteur == 11)
            ) {
                $nbErreur++;
                echo "<h4>Erreur, les participants du match ne sont pas valides</h4>";
            }

            // pas d'erreur on peut créer l'entrée
            if ($nbErreur == 0) {

                // rechercher les noms des participants
                $requeteSQL = "SELECT * FROM ChampionnatParticipant WHERE idParticipant='$participantRecevant'";
                $recordset = mysql_query($requeteSQL);
                $record = mysql_fetch_array($recordset);
                $nomParticipantRecevant = $record["nomParticipant"];
                $requeteSQL = "SELECT * FROM ChampionnatParticipant WHERE idParticipant='$participantVisiteur'";
                $recordset = mysql_query($requeteSQL);
                $record = mysql_fetch_array($recordset);
                $nomParticipantVisiteur = $record["nomParticipant"];

                // créer la date initiale
                $dateDebut = "$debutAnnee-$debutMois-$debutJour";
                $dateFin = "$finAnnee-$finMois-$finJour";
                $heureDebut = "$debutHeure:$debutMinute:00";
                $heureFin = "$finHeure:$finMinute:00";

                $description = "$nomParticipantRecevant - $nomParticipantVisiteur";

                if ($descriptionFr != "") {
                    $description = $description . " : " . $descriptionFr;
                }
                $descriptionAgenda = $description;
                if ($matchReporte) {
                    $descriptionAgenda = $descriptionAgenda . " : Match report&eacute; au $debutJourReporteAu-$debutMoisReporteAu-$debutAnneeReporteAu";
                }

                // creer la date de l'agenda
                $requeteInsertion = "INSERT INTO `Agenda_Evenement` (`description` , `lieu` ,
									`dateDebut` , `dateFin` , `heureDebut` , `heureFin` , `id_TypeEve` , `utilisateur` )
									VALUES ('$descriptionAgenda','$lieu','$dateDebut','$dateFin','$heureDebut','$heureFin','$typeMatch','$utilisateur');";
                mysql_query($requeteInsertion) or die ("<h4>Erreur, date inconvenable pour l'agenda</h4>");
                $idAgenda = mysql_insert_id();

                // match reporte
                if ($matchReporte) {

                    $descriptionAgendaReporte = $description . " (report du $debutJour-$debutMois-$debutAnnee)";

                    // créer la date du match reporte
                    $dateDebutReporteAu = "$debutAnneeReporteAu-$debutMoisReporteAu-$debutJourReporteAu";
                    $dateFinReporteAu = "$finAnneeReporteAu-$finMoisReporteAu-$finJourReporteAu";
                    $heureDebutReporteAu = "$debutHeureReporteAu:$debutMinuteReporteAu:00";
                    $heureFinReporteAu = "$finHeureReporteAu:$finMinuteReporteAu:00";

                    $requeteInsertion = "INSERT INTO `Agenda_Evenement` (`description` , `lieu` ,
										`dateDebut` , `dateFin` , `heureDebut` , `heureFin` , `id_TypeEve` , `utilisateur` )
										VALUES ('$descriptionAgendaReporte','$lieuReporteAu','$dateDebutReporteAu','$dateFinReporteAu','$heureDebutReporteAu','$heureFinReporteAu','$typeMatch','$utilisateur');";

                    mysql_query($requeteInsertion) or die ("<h4>Erreur, date du match reporte inconvenable pour l'agenda</h4>");
                    $idAgendaReporteAu = mysql_insert_id();
                }

                // mise en forme
                $nomChamp = "";
                for ($i = 0; $i < count($VAR_TABLEAU_DES_LANGUES); $i++) {
                    $nomChamp .= "`desc" . $VAR_TABLEAU_DES_LANGUES[$i][0] . "`";
                    if ($i < count($VAR_TABLEAU_DES_LANGUES) - 1) {
                        $nomChamp .= ",";
                    }
                }

                $valeurChamp = "";
                for ($i = 0; $i < count($VAR_TABLEAU_DES_LANGUES); $i++) {
                    $valeurChamp .= "'" . validiteInsertionTextBd($_POST["description" . $VAR_TABLEAU_DES_LANGUES[$i][0]]) . "'";
                    if ($i < count($VAR_TABLEAU_DES_LANGUES) - 1) {
                        $valeurChamp .= ",";
                    }
                }


                $requeteSQL = "INSERT INTO `ChampionnatMatch` " .
                    "(`idMatch`,`idClubRecevant`,`idClubVisiteur`,`idAgenda`,`idAgendaReporte`,`pointRecevant`,`pointVisiteur`," .
                    "`saison`,`tour`,`journee`,`idGroupe`,`forfait`," . $nomChamp . ") " .
                    "VALUES ('','$participantRecevant','$participantVisiteur','$idAgenda','$idAgendaReporteAu'," .
                    "'$pointRecevant','$pointVisiteur','$saison','$tour','$journee','$groupe','$forfait'," . $valeurChamp . ");";

                mysql_query($requeteSQL) or die ("<h4>Erreur, l'insertion du match a echoué</h4>");
            }
        } // mauvaise date
        else {
            echo "<h4>Erreur, date de match incorrecte</h4>";
        }
    } else {
        echo "<h4>Erreur, Les participants du match ne font pas partie d'un tour de championnat commun</h4>";
    }
}
?>

<?php
function optionsParticipant()
{
    $requeteSQL = "SELECT * FROM ChampionnatParticipant ORDER BY nomParticipant";
    $recordset = mysql_query($requeteSQL);
    $nbLigne = mysql_affected_rows();
    while ($record = mysql_fetch_array($recordset)) {
        echo "<option value='" . $record["idParticipant"] . "'>" . $record["nomParticipant"] . "</option>";
    }
}

function optionsScore()
{
    for ($i = 0; $i < 100; $i++) {
        echo "<option value='$i'>$i</option>";
    }
}

//creation d'un tableau javascript pour aider l'utiliseur a ne pas entrer un tour existant
$requeteSQL = "SELECT DISTINCT saison, tour, groupe FROM ChampionnatPtDepartClassement";
$recordset = mysql_query($requeteSQL);
echo "<script language='JavaScript'>var tourChampionnatExistant = new Array();";
while ($record = mysql_fetch_array($recordset)) {
    echo "tourChampionnatExistant['" . $record["saison"] . ":" . $record["tour"] . ":" . $record["groupe"] . "']=true;";
}
echo "</script>";

?>
<script language="javascript">

    function validateForm() {
        // get the text
        //document.getElementById('participantRecevant').options[document.getElementById('participantRecevant').selectedIndex].text

        /*
         (	document.getElementById('participantRecevant').options[document.getElementById('participantRecevant').selectedIndex].text !=
         document.getElementById('participantVisiteur').options[document.getElementById('participantVisiteur').selectedIndex].text
         ) ||
         (	document.getElementById('participantRecevant').options[document.getElementById('participantRecevant').selectedIndex].text == ""
         document.getElementById('participantVisiteur').options[document.getElementById('participantVisiteur').selectedIndex].text == ""
         )

         */


        // soit différent, soit égaux a la chaine vise
        if (
            insertion.participantRecevant.options[insertion.participantRecevant.selectedIndex].text ==
            insertion.participantVisiteur.options[insertion.participantVisiteur.selectedIndex].text
            &&
            insertion.participantRecevant.options[insertion.participantRecevant.selectedIndex].text != ""
            &&
            insertion.participantVisiteur.options[insertion.participantVisiteur.selectedIndex].text != ""
        ) {
            alert("Un match doit avoir des participants différents");
            return false;
        }

        // soit différent, soit égaux a la chaine vise
        if (
            (
                insertion.participantRecevant.options[insertion.participantRecevant.selectedIndex].text == ""
                &&
                insertion.participantVisiteur.options[insertion.participantVisiteur.selectedIndex].text != ""
            )
            ||
            (
                insertion.participantRecevant.options[insertion.participantRecevant.selectedIndex].text != ""
                &&
                insertion.participantVisiteur.options[insertion.participantVisiteur.selectedIndex].text == ""
            )
        ) {
            alert("Un match doit soit avoir des participants différents soit avoir deux participants inconnus");
            return false;
        }

        // tour existant
        if (!tourChampionnatExistant[insertion.saison.value + ":" + insertion.tour.value + ":" + insertion.groupe.value]) {
            alert("Erreur, la saison, le tour ou groupe n'existe pas, impossible de continuer");
            return false;
        }

        //if(!chronologieDate()) return false;
        //if(insertion.matchReporte.checked && !chronologieDateReporteAu()) return false;

        return true;
    }

    function changeEtatForfait(chkbox) {
        if (chkbox.name == "recevantGagneParForfait") {
            //alert("recevantGagneParForfait");
            var chkVisiteur = document.getElementById("visiteurGagneParForfait");
            if (chkbox.checked && chkVisiteur.checked) {
                chkVisiteur.checked = false;
            }
        }
        else {
            //alert("visiteurGagneParForfait");
            var chkRecevant = document.getElementById("recevantGagneParForfait");
            if (chkbox.checked && chkRecevant.checked) {
                chkRecevant.checked = false;
            }
        }
    }

    function changeEtatParticipant(liste) {

        var activer = liste.options[liste.selectedIndex].text == "";
        if (liste.name == "participantRecevant") {
            document.getElementById("pointRecevant").disabled = activer;
            document.getElementById("recevantGagneParForfait").disabled = activer;
            if (activer) {
                document.getElementById("pointRecevant").selectedIndex = 0;
                document.getElementById("recevantGagneParForfait").checked = false;
            }
        }
        else {
            document.getElementById("pointVisiteur").disabled = activer;
            document.getElementById("visiteurGagneParForfait").disabled = activer;
            if (activer) {
                document.getElementById("pointVisiteur").selectedIndex = 0;
                document.getElementById("visiteurGagneParForfait").checked = false;
            }
        }
    }

    function changePartieAgendaReporte(chkbox) {
        var tr = document.getElementById("partieAgendaReporte");
        if (chkbox.checked) {
            tr.style.visibility = "visible";
        }
        else {
            tr.style.visibility = "hidden";
        }
    }


    function selectionAutomatiqueAnne() {
        insertion.finAnnee.value = insertion.debutAnnee.value;
    }
    function selectionAutomatiqueMois() {
        insertion.finMois.value = insertion.debutMois.value;
    }
    function selectionAutomatiqueJour() {
        insertion.finJour.value = insertion.debutJour.value;
    }
    function selectionAutomatiqueHeure() {
        insertion.finHeure.value = insertion.debutHeure.value;
    }
    function selectionAutomatiqueMinute() {
        insertion.finMinute.value = insertion.debutMinute.value;
    }

    function chronologieDate() {
        dateActuelle = new Date();
        dateDebut = new Date(insertion.debutAnnee.value,
            insertion.debutMois.value,
            insertion.debutJour.value,
            insertion.debutHeure.value,
            insertion.debutMinute.value,
            00);
        dateFin = new Date(insertion.finAnnee.value,
            insertion.finMois.value,
            insertion.finJour.value,
            insertion.finHeure.value,
            insertion.finMinute.value,
            00);

        if (dateDebut.getTime() > dateFin.getTime()) {
            alert('Erreur dans les dates : \n\n' +
                'début : ' + dateDebut.toLocaleString() + '\n' +
                'fin : ' + dateFin.toLocaleString() + '\n');
            return false;
        }
        if (dateActuelle.getTime() > dateDebut.getTime()) {
            alert('Erreur dans les dates, vous ne pouvez pas introduire une date dans le passé');
            return false;
        }

        return true;
    }


    // reporte au :
    function selectionAutomatiqueAnneReporteAu() {
        insertion.finAnneeReporteAu.value = insertion.debutAnneeReporteAu.value;
    }
    function selectionAutomatiqueMoisReporteAu() {
        insertion.finMoisReporteAu.value = insertion.debutMoisReporteAu.value;
    }
    function selectionAutomatiqueJourReporteAu() {
        insertion.finJourReporteAu.value = insertion.debutJourReporteAu.value;
    }
    function selectionAutomatiqueHeureReporteAu() {
        insertion.finHeureReporteAu.value = insertion.debutHeureReporteAu.value;
    }
    function selectionAutomatiqueMinuteReporteAu() {
        insertion.finMinuteReporteAu.value = insertion.debutMinuteReporteAu.value;
    }

    function chronologieDateReporteAu() {
        dateActuelle = new Date();
        dateDebut = new Date(insertion.debutAnneeReporteAu.value,
            insertion.debutMoisReporteAu.value,
            insertion.debutJourReporteAu.value,
            insertion.debutHeureReporteAu.value,
            insertion.debutMinuteReporteAu.value,
            00);
        dateFin = new Date(insertion.finAnneeReporteAu.value,
            insertion.finMoisReporteAu.value,
            insertion.finJourReporteAu.value,
            insertion.finHeureReporteAu.value,
            insertion.finMinuteReporteAu.value,
            00);

        if (dateDebut.getTime() > dateFin.getTime()) {
            alert('Erreur dans les dates pour le match reporté : \n\n' +
                'début : ' + dateDebut.toLocaleString() + '\n' +
                'fin : ' + dateFin.toLocaleString() + '\n');
            return false;
        }
        if (dateActuelle.getTime() > dateDebut.getTime()) {
            alert('Erreur dans les dates pour le match reporté, vous ne pouvez pas introduire une date dans le passé');
            return false;
        }

        return true;
    }

</script>

<form name="insertion" method="post" action="" onSubmit="return validateForm();">
    <table width="80%" border="0" align="center">
        <tr>
            <td height="44">
                <table border="0" align="center">
                    <tr>
                        <td><h3>Match</h3></td>
                    </tr>
                </table>
                <p>&nbsp;</p>
                <table border="0" align="center">
                    <tr>
                        <td><select name="participantRecevant" onChange="changeEtatParticipant(this);">
                                <?php optionsParticipant(); ?>
                            </select></td>
                        <td>-</td>
                        <td><select name="participantVisiteur" onChange="changeEtatParticipant(this);">
                                <?php optionsParticipant(); ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td align="right"><select name="pointRecevant" DISABLED>
                                <?php optionsScore(); ?>
                            </select></td>
                        <td>-</td>
                        <td><select name="pointVisiteur" DISABLED>
                                <?php optionsScore(); ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td align="right"><p>Gagne par forfait<input name="recevantGagneParForfait" type="checkbox"
                                                                     class='couleurCheckBox'
                                                                     onclick="changeEtatForfait(this);" DISABLED></p>
                        <td></td>
                        <td><p><input name="visiteurGagneParForfait" type="checkbox" class='couleurCheckBox'
                                      onclick="changeEtatForfait(this);" DISABLED>Gagne par forfait</p></td>
                    </tr>
                </table>
                <p>&nbsp;</p>
                <table border="0" align="center">
                    <tr>
                        <td width="150"><p>Saison :
                                <select name="saison">
                                    <?php
                                    $anneeActuelle = date("Y");
                                    for ($i = $anneeActuelle - 5; $i < $anneeActuelle + 5; $i++) {
                                        if ($i == $anneeActuelle) {
                                            echo "<option value='$i' SELECTED>$i-" . ($i + 1) . "</option>";
                                        } else {
                                            echo "<option value='$i'>$i-" . ($i + 1) . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </p></td>
                        <td width="150">
                            <p>Tour :
                                <select name="tour">
                                    <?php $anneeActuelle = date("Y");
                                    for ($i = 1; $i < 5; $i++) {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                    ?>
                                    <option value='500'>Promotion / Relégation</option>
                                    <option value='1000'>Final</option>
                                </select>
                            </p>
                        </td>
                        <td width="150"><p>Groupe :
                                <select name="groupe">
                                    <?php $requeteSQL = "SELECT * FROM ChampionnatGroupe ORDER BY numeroGroupe";
                                    $recordset = mysql_query($requeteSQL);

                                    while ($record = mysql_fetch_array($recordset)) {
                                        echo "<option value='" . $record["idGroupe"] . "'>" . $record["nomGroupe" . $_SESSION["__langue__"]] . "</option>";
                                    }
                                    ?>
                                </select>
                            </p></td>
                        <td width="150"><p>Journée :
                                <select name="journee">
                                    <?php $anneeActuelle = date("Y");
                                    for ($i = 1; $i < 50; $i++) {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                    ?>
                                </select>
                            </p></td>
                    </tr>
                </table>
                <p>&nbsp;</p>
                <table border="0" align="center">
                    <tr>
                        <td colspan="2"><h4>Description</h4></td>
                    </tr>
                    <?php
                    for ($i = 0; $i < count($VAR_TABLEAU_DES_LANGUES); $i++) {
                        echo "<tr>";
                        echo "<td><p>" . $VAR_TABLEAU_DES_LANGUES[$i][1] . "</p></td>";
                        echo "<td><input type='text' name='description" . $VAR_TABLEAU_DES_LANGUES[$i][0] . "' size='35' maxlength='35'></p></td>";
                        echo "</tr>";
                    }
                    ?>
                </table>

                <p>&nbsp;</p>
                <table width="400" border="0" align="center">
                    <tr>
                        <td colspan="2"><h4>Pour la partie Agenda</h4></td>
                    </tr>
                    <tr>
                        <td><p><?php echo $agenda_lieu; ?></p></td>
                        <td><input name="lieu" type="text" id="lieu4" size="50" maxlength="50"></td>
                    </tr>
                    <tr>
                        <td><p><?php echo $agenda_debut; ?></p></td>
                        <td><p><?php echo $agenda_date; ?> :
                                <select name="debutJour" id="debutJour" onChange="selectionAutomatiqueJour()">
                                    <?php echo creation_liste_jour(); ?> </select> <select name="debutMois"
                                                                                           id="debutMois"
                                                                                           onChange="selectionAutomatiqueMois()">
                                    <?php echo creation_liste_mois(); ?> </select> <select name="debutAnnee"
                                                                                           id="debutAnnee"
                                                                                           onChange="selectionAutomatiqueAnne()">
                                    <?php echo creation_liste_annee(0, 5); ?>
                                </select>
                                <?php echo $agenda_heure; ?> :
                                <select name="debutHeure" id="debutHeure" onChange="selectionAutomatiqueHeure()">
                                    <?php echo creation_liste_heure(); ?> </select> <select name="debutMinute"
                                                                                            id="debutMinute">
                                    <?php echo creation_liste_minute(); ?> </select></p></td>
                    </tr>
                    <tr>
                        <td><p><?php echo $agenda_fin; ?></p></td>
                        <td><p><?php echo $agenda_date; ?> :
                                <select name="finJour" id="finJour">
                                    <?php echo creation_liste_jour(); ?> </select> <select name="finMois" id="finMois">
                                    <?php echo creation_liste_mois(); ?> </select> <select name="finAnnee"
                                                                                           id="finAnnee">
                                    <?php echo creation_liste_annee(0, 5); ?>
                                </select>
                                <?php echo $agenda_heure; ?> :
                                <select name="finHeure" id="finHeure">
                                    <?php echo creation_liste_heure(); ?> </select> <select name="finMinute"
                                                                                            id="finMinute">
                                    <?php echo creation_liste_minute(); ?> </select></p></td>
                    </tr>
                </table>
                <p>&nbsp;</p>
                <table width="400" border="0" align="center">
                    <tr>
                        <td>
                            <p>Match report&eacute; <input name="matchReporte" type="checkbox"
                                                           onClick="changePartieAgendaReporte(this);"
                                                           class='couleurCheckBox'></p>
                        </td>
                    </tr>
                    <tr id="partieAgendaReporte" style="visibility:hidden">
                        <td><h4>Match reporté au</h4>
                            <table width="400" border="0" align="center">
                                <tr>
                                    <td><p><?php echo $agenda_lieu; ?></p></td>
                                    <td><input name="lieuReporteAu" type="text" size="50" maxlength="50"></td>
                                </tr>
                                <tr>
                                    <td><p><?php echo $agenda_debut; ?></p></td>
                                    <td><p><?php echo $agenda_date; ?> :
                                            <select name="debutJourReporteAu" id="debutJourReporteAu"
                                                    onChange="selectionAutomatiqueJourReporteAu()">
                                                <?php echo creation_liste_jour(); ?> </select> <select
                                                name="debutMoisReporteAu" id="debutMoisReporteAu"
                                                onChange="selectionAutomatiqueMoisReporteAu()">
                                                <?php echo creation_liste_mois(); ?> </select> <select
                                                name="debutAnneeReporteAu" id="debutAnneeReporteAu"
                                                onChange="selectionAutomatiqueAnneReporteAu()">
                                                <?php echo creation_liste_annee(0, 5); ?>
                                            </select>
                                            <?php echo $agenda_heure; ?> :
                                            <select name="debutHeureReporteAu" id="debutHeureReporteAu"
                                                    onChange="selectionAutomatiqueHeureReporteAu()">
                                                <?php echo creation_liste_heure(); ?> </select> <select
                                                name="debutMinuteReporteAu" id="debutMinuteReporteAu">
                                                <?php echo creation_liste_minute(); ?> </select></p></td>
                                </tr>
                                <tr>
                                    <td><p><?php echo $agenda_fin; ?></p></td>
                                    <td><p><?php echo $agenda_date; ?> :
                                            <select name="finJourReporteAu" id="finJourReporteAu">
                                                <?php echo creation_liste_jour(); ?> </select> <select
                                                name="finMoisReporteAu" id="finMoisReporteAu">
                                                <?php echo creation_liste_mois(); ?> </select> <select
                                                name="finAnneeReporteAu" id="finAnneeReporteAu">
                                                <?php echo creation_liste_annee(0, 5); ?>
                                            </select>
                                            <?php echo $agenda_heure; ?> :
                                            <select name="finHeureReporteAu" id="finHeureReporteAu">
                                                <?php echo creation_liste_heure(); ?> </select> <select
                                                name="finMinuteReporteAu" id="finMinuteReporteAu">
                                                <?php echo creation_liste_minute(); ?> </select></p></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <p align="center">
        <input type="hidden" name="action" value="insererMatch">
        <input name='submit' type='submit' value='<?php echo VAR_LANG_INSERER; ?>'>
    </p>
</form>
