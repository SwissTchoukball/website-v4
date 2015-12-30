<h3>
    <?php echo VAR_LANG_ETAPE_3; ?>
</h3>
<?php
if (!isset($_GET['idMatch'])) {
    echo "Erreur: il manque des informations.";
} else {
    $idMatch = $_GET['idMatch'];
    $requete = "SELECT *, p.scoreA AS periodScoreA, p.scoreB AS periodScoreB, ea.equipe AS nomEquipeA, eb.equipe AS nomEquipeB
				FROM Championnat_Matchs m
				LEFT OUTER JOIN Championnat_Periodes p ON m.idMatch = p.idMatch
                LEFT OUTER JOIN Championnat_Equipes ea ON m.equipeA = ea.idEquipe
                LEFT OUTER JOIN Championnat_Equipes eb ON m.equipeB = eb.idEquipe
				WHERE m.idMatch=" . $idMatch . "
				ORDER BY p.noPeriode";
    $retour = mysql_query($requete);
    $firstPeriod = true;
    $scoreA = 0;
    $scoreB = 0;
    $periodScoreA = array();
    $periodScoreB = array();
    $defaultNbPeriods = 3;
    $defaultPeriodTypeID = 3; // 20 minutes period

    while ($donnees = mysql_fetch_array($retour)) {
        if ($firstPeriod) {
            $idEquipeA = $donnees['equipeA'];
            $idEquipeB = $donnees['equipeB'];
            $nomEquipeA = $donnees['nomEquipeA'];
            $nomEquipeB = $donnees['nomEquipeB'];
            $forfait = $donnees['forfait'];
            $saison = $donnees['saison'];
            $idCategorie = $donnees['idCategorie'];
            $idTour = $donnees['idTour'];
            $idTypeMatch = $donnees['idTypeMatch'];
            $idGroupe = $donnees['noGroupe'];
            $journee = $donnees['journee'];
            $dateDebut = $donnees['dateDebut'];
            $dateFin = $donnees['dateFin'];
            $heureDebut = $donnees['heureDebut'];
            $heureFin = $donnees['heureFin'];
            $dateReportDebut = $donnees['dateReportDebut'];
            $dateReportFin = $donnees['dateReportFin'];
            $heureReportDebut = $donnees['heureReportdebut'];
            $heureReportFin = $donnees['heureReportFin'];
            $necessiteDefraiementArbitre = $donnees['necessiteDefraiementArbitre'];
            $idLieu = $donnees['idLieu'];
            $nbSpectateurs = $donnees['nbSpectateurs'];

            $firstPeriod = false;
        }

        $noPeriod = $donnees['noPeriode'];

        $periodScoreA[$noPeriod] = $donnees['periodScoreA'];
        $periodScoreB[$noPeriod] = $donnees['periodScoreB'];
        $periodTypeId[$noPeriod] = $donnees['idTypePeriode'];
        $periodRefereeA[$noPeriod] = $donnees['idArbitreA'];
        $periodRefereeB[$noPeriod] = $donnees['idArbitreB'];
        $periodRefereeC[$noPeriod] = $donnees['idArbitreC'];
    }
    // Si $noPeriod est vide, alors il s'agit d'un match dont le score n'a pas encore été entré.
    if ($noPeriod == '') {
        $nbPeriods = $defaultNbPeriods;
    } else {
        $nbPeriods = $noPeriod;
    }

    $orderRefereesByLevel = true;
    $referees = getReferees($orderRefereesByLevel);

    // Si les données viennent d'un formulaire à cause d'un changement de saison, prendre ces données, sinon aller chercher dans la base de données.
    /*
        $idEquipeA = isset($_POST['equipeA']) ? $_POST['equipeA'] : $idEquipeA;
        $idEquipeB = isset($_POST['equipeB']) ? $_POST['equipeB'] : $idEquipeB;
        $periodScoreA = isset($_POST['periodScoreA']) ? $_POST['periodScoreA'] : $periodScoreA;
        $periodScoreB = isset($_POST['periodScoreB']) ? $_POST['periodScoreB'] : $periodScoreB;
        $periodTypeId = isset($_POST['periodTypeId']) ? $_POST['periodTypeid'] : $periodTypeId;
        $forfait = isset($_POST['forfait']) ? $_POST['forfait'] : $forfait;
        $saison = isset($_POST['saison']) ? $_POST['saison'] : $saison;
        $idCategorie = isset($_POST['idCategorie']) ? $_POST['idCategorie'] : $idCategorie;
        $idTour = isset($_POST['idTour']) ? $_POST['idTour'] : $idTour;
        $idTypeMatch = isset($_POST['idTypeMatch']) ? $_POST['idTypeMatch'] : $idTypeMatch;
        $idGroupe = isset($_POST['idGroupe']) ? $_POST['idGroupe'] : $idGroupe;
        $journee = isset($_POST['journee']) ? $_POST['journee'] : $journee;
        $dateDebut = isset($_POST['debutAnnee']) ? $_POST['debutAnnee']."-".$_POST['debutMois']."-".$_POST['debutJour'] : $dateDebut;
        $dateFin = isset($_POST['finAnnee']) ? $_POST['finAnnee']."-".$_POST['finMois']."-".$_POST['finJour'] : $dateFin;
        $heureDebut = isset($_POST['debutHeure']) ? $_POST['debutHeure'].":".$_POST['debutMinute'].":00" : $heureDebut;
        $heureFin = isset($_POST['finHeure']) ? $_POST['finHeure'].":".$_POST['finMinute'].":00" : $heureFin;
        $dateReportDebut = isset($_POST['debutAnneeReport']) ? $_POST['debutAnneeReport']."-".$_POST['debutMoisReport']."-".$_POST['debutJourReport'] : $dateReportDebut;
        $dateReportFin = isset($_POST['finAnneeReport']) ? $_POST['finAnneeReport']."-".$_POST['finMoisReport']."-".$_POST['finJourReport'] : $dateReportFin;
        $heureReportDebut = isset($_POST['debutHeureReport']) ? $_POST['debutHeureReport'].":".$_POST['debutMinuteReport'].":00" : $heureReportDebut;
        $heureReportFin = isset($_POST['finHeureReport']) ? $_POST['finHeureReport'].":".$_POST['finMinuteReport'].":00" : $heureReportFin;
        $necessiteDefraiementArbitre = isset($_POST['necessiteDefraiementArbitre']) ? $_POST['necessiteDefraiementArbitre'] : $necessiteDefraiementArbitre;
        $idLieu = isset($_POST['idLieu']) ? $_POST['idLieu'] : $idLieu;
        $nbSpectateurs = isset($_POST['nbSpectateurs']) ? $_POST['nbSpectateurs'] : $nbSpectateurs;
    */

    $utilisateur = $_SESSION['__prenom__'] . $_SESSION['__nom__'];

    $scoreA = array_sum($periodScoreA);
    $scoreB = array_sum($periodScoreB);

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
    $nbMatchGagnatPromoReleg = $donnees['nbMatchGagnatPromoReleg'];
    $systemePassageTours = $donnees['systemePassageTours'];
    $penaliteArbitreNiveauInferieur = $donnees['penaliteArbitreNiveauInferieur'];
    $penaliteNonMixiteParTiers = $donnees['penaliteNonMixiteParTiers'];

    function optionsParticipant($saison, $idCategorie, $idTour, $idGroupe, $idEquipeSelected) {
        /*if($saison=='' OR $idCategorie=='' OR $idTour=='' OR $idGroupe==''){
            $requete = "SELECT * FROM Championnat_Equipes ORDER BY equipe";
            $retour = mysql_query($requete);
            $nbLigne = mysql_affected_rows();
            while($donnees = mysql_fetch_array($retour)){
                echo "<option value='".$donnees["idEquipe"]."'>".$donnees["equipe"]."</option>";
            }
        }*/ // Pas besoin ici vu que la saison, la categorie, le tour et le gourpe sont obligatoirement définis.
        if ($saison != '' AND $idCategorie != '' AND $idTour != '' AND $idGroupe != '') {
            $requete = "SELECT equipe, idEquipe FROM Championnat_Equipes ORDER BY equipe";
            $retour = mysql_query($requete);
            $nbLigne = mysql_affected_rows();
            while ($donnees = mysql_fetch_array($retour)) {
                $requeteA = "SELECT idEquipe FROM Championnat_Equipes_Tours WHERE saison=" . $saison . " AND idCategorie=" . $idCategorie . " AND idTour=" . $idTour . " AND noGroupe=" . $idGroupe;
                $retourA = mysql_query($requeteA);
                while ($donneesA = mysql_fetch_array($retourA)) {
                    if ($donnees['idEquipe'] == $donneesA['idEquipe']) {
                        if ($donnees['idEquipe'] == $idEquipeSelected) {
                            $selected = "selected='selected'";
                        } else {
                            $selected = "";
                        }
                        echo "<option value='" . $donnees["idEquipe"] . "' " . $selected . ">" . $donnees["equipe"] . "</option>";
                    }
                }
            }
        } else {
            echo "<option>ERREUR</option>";
        }

    }

    function optionsScore($scoreEquipeSelected) {
        for ($i = 0; $i <= 128; $i++) {
            if ($i == $scoreEquipeSelected) {
                $selected = "selected='selected'";
            } else {
                $selected = "";
            }
            echo "<option value='" . $i . "' " . $selected . ">$i</option>";
        }
    }

    function optionsPeriodType($periodTypeSelected, $defaultPeriodType) {
        $query = "SELECT * FROM Championnat_Types_Periodes WHERE duree > 0 ORDER BY nom, duree";
        $result = mysql_query($query);
        while ($periodType = mysql_fetch_assoc($result)) {
            if (!$periodTypeSelected) {
                $periodTypeSelected = $defaultPeriodType;
            }

            if ($periodType['id'] == $periodTypeSelected) {
                $selected = 'selected="selected"';
            } else {
                $selected = '';
            }

            echo '<option value="' . $periodType['id'] . '" ' . $selected . '>' . $periodType['nom'] . ' de ' . $periodType['duree'] . ' minutes</option>';
        }
    }

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

    // Récupération des points de péanlités par type
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
    ?>
    <script language="javascript">

        function isInt(value) {
            return !isNaN(value) &&
                parseInt(Number(value)) == value && !isNaN(parseInt(value, 10));
        }

        function validateForm() {
            setPenaltiesIdsInput();

            if (modifierUnMatch.equipeA.value == modifierUnMatch.equipeB.value) {
                alert("Erreur, un match doit avoir des participants différents");
                return false;
            }

            if (modifierUnMatch.idCategorie.value != 0 && modifierUnMatch.idTour.value == 2000) {
                alert("Vous ne pouvez pas choisir Promotion / Relegation comme tour si la catégorie n'est pas sur Promotion / Relegation.");
                return false;
            }

            if (modifierUnMatch.nbSpectateurs.value != '' && !isInt(modifierUnMatch.nbSpectateurs.value)) {
                alert("Le nombre de spectateurs indiqué doit être un nombre entier.");
                return false;
            }

            return true;
        }

        function changeValeurActionForm() {
            if (modifierUnMatch.saison.value == '<?php echo $saison ?>') {
                modifierUnMatch.action.value = 'modifierMatch3';
            } else {
                modifierUnMatch.action.value = 'modifierMatch2';
            }
            document.modifierUnMatch.submit();
        }
        function changeEtatCategorie() {
            if (modifierUnMatch.idCategorie.value == 0) {
                modifierUnMatch.idGroupe.disabled = true;
                modifierUnMatch.idGroupe.value = 0;
                modifierUnMatch.idTour.disabled = true;
                modifierUnMatch.idTour.value = 2000;
                modifierUnMatch.idTypeMatch.disabled = true;
                modifierUnMatch.idTypeMatch.value = 1000;
            } else {
                modifierUnMatch.idGroupe.disabled = false;
                modifierUnMatch.idGroupe.value =<?php echo $idGroupe; ?>;
                modifierUnMatch.idTour.disabled = false;
                modifierUnMatch.idTour.value =<?php echo $idTour; ?>;
                modifierUnMatch.idTypeMatch.value = <?php echo $idTypeMatch; ?>;
            }
        }
        function changeEtatTour() {
            if (modifierUnMatch.idTour.value == 10000 || modifierUnMatch.idTour.value == 2000 || modifierUnMatch.idTour.value == 3000 || modifierUnMatch.idTour.value == 4000) {
                modifierUnMatch.idGroupe.disabled = true;
                modifierUnMatch.idGroupe.value = 0;
            } else modifierUnMatch.idGroupe.disabled = false;

            if (modifierUnMatch.idTour.value == 1 || modifierUnMatch.idTour.value == 2 || modifierUnMatch.idTour.value == 3 || modifierUnMatch.idTour.value == 4) {
                modifierUnMatch.idTypeMatch.value = 0;
                modifierUnMatch.idTypeMatch.disabled = true;
            }
            else if (modifierUnMatch.idTour.value == 2000) {
                modifierUnMatch.idTypeMatch.value = 1000;
                modifierUnMatch.idTypeMatch.disabled = true;
            } else {
                modifierUnMatch.idTypeMatch.disabled = false;
                modifierUnMatch.idTypeMatch.value = <?php echo $idTypeMatch; ?>;
            }
        }
        function changePartieAgendaReporte(chkbox) {
            var tr = document.getElementById("partieAgendaReporte");
            if (chkbox.checked) {
                tr.style.visibility = "visible";
            } else {
                tr.style.visibility = "hidden";
            }
        }

        function selectionAutomatiqueAnne() {
            modifierUnMatch.finAnnee.value = modifierUnMatch.debutAnnee.value;
        }
        function selectionAutomatiqueMois() {
            modifierUnMatch.finMois.value = modifierUnMatch.debutMois.value;
        }
        function selectionAutomatiqueJour() {
            modifierUnMatch.finJour.value = modifierUnMatch.debutJour.value;
        }
        function selectionAutomatiqueHeure() {
            modifierUnMatch.finHeure.value = parseInt(modifierUnMatch.debutHeure.value) + 1;
        }
        function selectionAutomatiqueMinute() {
            modifierUnMatch.finMinute.value = modifierUnMatch.debutMinute.value;
        }
        function selectionAutomatiqueAnneReport() {
            modifierUnMatch.finAnneeReport.value = modifierUnMatch.debutAnneeReport.value;
        }
        function selectionAutomatiqueMoisReport() {
            modifierUnMatch.finMoisReport.value = modifierUnMatch.debutMoisReport.value;
        }
        function selectionAutomatiqueJourReport() {
            modifierUnMatch.finJourReport.value = modifierUnMatch.debutJourReport.value;
        }
        function selectionAutomatiqueHeureReport() {
            modifierUnMatch.finHeureReport.value = parseInt(modifierUnMatch.debutHeureReport.value) + 1;
        }
        function selectionAutomatiqueMinuteReport() {
            modifierUnMatch.finMinuteReport.value = modifierUnMatch.debutMinuteReport.value;
        }

        function selectionAutomatiqueArbitreA() {
            $("select[name^=periodRefereeA]").val($("select[name^=periodRefereeA]").val());
        }

        function selectionAutomatiqueArbitreB() {
            $("select[name^=periodRefereeB]").val($("select[name^=periodRefereeB]").val());
        }

        function selectionAutomatiqueArbitreC() {
            $("select[name^=periodRefereeC]").val($("select[name^=periodRefereeC]").val());
        }

        function updateScore() {
            var scoreA = 0;
            var scoreB = 0;
            var idEquipeA = <?php echo $idEquipeA; ?>;
            var idEquipeB = <?php echo $idEquipeB; ?>;

            //Sum of the periods
            $('.period select[name^="periodScoreA"]').each(function () {
                scoreA += parseFloat(this.value);
            });
            $('.period select[name^="periodScoreB"]').each(function () {
                scoreB += parseFloat(this.value);
            });

            //Handling the penalties
            penalties.forEach(function(penaltyId) {
                var teamId = $('.penalty select[name=penalty-' + penaltyId + '-teamId]').val();
                var typeId = $('.penalty select[name=penalty-' + penaltyId + '-typeId]').val();
                if (teamId == idEquipeA) {
                    scoreA += penaltiesTypes[typeId];
                } else if (teamId == idEquipeB) {
                    scoreB += penaltiesTypes[typeId];
                }
            });

            $('#scoreA').text(scoreA);
            $('#scoreB').text(scoreB);
        }

        function updateForfaitStatus(checkedbox) {
            var AForfaitCheckbox = $('input[name="AGagneParForfait"]');
            var BForfaitCheckbox = $('input[name="BGagneParForfait"]');

            if (checkedbox) {
                if (checkedbox.name == 'AGagneParForfait') {
                    BForfaitCheckbox.attr('checked', false);
                } else if (checkedbox.name == 'BGagneParForfait') {
                    AForfaitCheckbox.attr('checked', false);
                }
            }

            var forfaitFromA = AForfaitCheckbox.is(':checked');
            var forfaitFromB = BForfaitCheckbox.is(':checked');

            // Si les deux cases sont cochées (ne devrais pas arriver),
            // On définit que A gagne par forfait
            if (forfaitFromA && forfaitFromB) {
                forfaitFromA = false;
                BForfaitCheckbox.attr('checked', false);
            }

            var forfait = forfaitFromA || forfaitFromB;

            if (forfait) {
                $('.period').hide();
                $('.periodReferees').hide();
                $('#periodAdder').hide();
                if (forfaitFromA) {
                    $('#scoreA').text(0);
                    $('#scoreB').text(<?php echo $scoreGagnantParForfait; ?>);
                }
                if (forfaitFromB) {
                    $('#scoreA').text(<?php echo $scoreGagnantParForfait; ?>);
                    $('#scoreB').text(0);
                }
            } else {
                $('.period').show();
                $('.periodReferees').show();
                $('#periodAdder').show();
                updateScore();
            }
        }

        function addPeriod() {
            var currentNbPeriods = $('.period').length;
            var newPeriodNo = currentNbPeriods + 1;

            // Période pour le score
            var newPeriod = $('<tr class="period"></tr>').appendTo($('#score'));
            var scoreA = $('<td align="right"></td>').appendTo(newPeriod);
            scoreA.text(newPeriodNo + '. ');
            $('<td>-</td>').appendTo(newPeriod);
            var scoreB = $('<td></td>').appendTo(newPeriod);
            var selectA = $('<select name="periodScoreA[' + newPeriodNo + ']"></select>').appendTo(scoreA);
            var selectB = $('<select name="periodScoreB[' + newPeriodNo + ']"></select>').appendTo(scoreB);
            scoreB.append(' ');
            $('<select name="periodTypeId[' + newPeriodNo + ']"><?php echo optionsPeriodType(null, $defaultPeriodTypeID) ?></select>').appendTo(scoreB);

            var options = [];
            for (n = 0; n <= 128; n++) {
                options[n] = n;
                selectA.append($("<option>").attr('value', n).text(n));
                selectB.append($("<option>").attr('value', n).text(n));
            }

            // Période pour les arbitres
            var newPeriodReferees = $('<tr class="periodReferees"></tr>').appendTo($('#referees'));
            var periodNo = $('<td></td>').appendTo(newPeriodReferees);
            periodNo.text(newPeriodNo + '. ');
            var refereeA = $('<td></td>').appendTo(newPeriodReferees);
            var refereeB = $('<td></td>').appendTo(newPeriodReferees);
            var refereeC = $('<td></td>').appendTo(newPeriodReferees);
            var refereeSelectA = $('<select name="periodRefereeA[' + newPeriodNo + ']" id="periodRefereeA[' + newPeriodNo + ']"><option value="">Arbitre A</option><?php printRefereesOptionsList($referees, null, true); ?></select>').appendTo(refereeA);
            var refereeSelectB = $('<select name="periodRefereeB[' + newPeriodNo + ']" id="periodRefereeB[' + newPeriodNo + ']"><option value="">Arbitre B</option><?php printRefereesOptionsList($referees, null, true); ?></select>').appendTo(refereeB);
            var refereeSelectC = $('<select name="periodRefereeC[' + newPeriodNo + ']" id="periodRefereeC[' + newPeriodNo + ']"><option value="">Arbitre C</option><?php printRefereesOptionsList($referees, null, true); ?></select>').appendTo(refereeC);

        }

        function removePeriod() {
            var currentNbPeriods = $('.period').length;
            if (currentNbPeriods > 1) {
                $('.period:last-child').remove();
                $('.periodReferees:last-child').remove();
                updateScore();
            }
        }

        var penalties = [];
        var newPenalties = 0;
        var deletedPenalties = [];
        var penaltiesTypes = [];
        <?php
            // Transposing PHP array to Javascript
            foreach($penaltiesTypes as $penaltyType) {
                echo 'penaltiesTypes[' . $penaltyType['id'] . '] = ' . $penaltyType['penaltyPoints'] . ';';
            }
        ?>

        function addPenalty(penaltyId) {
            if (penaltyId) {
                penalties.push(penaltyId);
            } else {
                newPenalties++;
                newPenaltyId = 'new-' + newPenalties;
                penalties.push(newPenaltyId);

                //Adding to DOM
                var lastPenalty;
                if ($('tr.penalty').length > 0) {
                    lastPenalty = $('tr.penalty').last();
                } else {
                    lastPenalty = $('tr.period').last();
                }

                var newPenalty = $('<tr></tr>');
                newPenalty.attr('id', 'penalty-' + newPenaltyId);
                newPenalty.addClass('penalty');

                var newPenaltyCell = $('<td></td>');
                newPenaltyCell.attr('align', 'center');
                newPenaltyCell.attr('colspan', '3');
                newPenalty.append(newPenaltyCell);

                //This PHP/javascript mix is really disgusting …
                var newPenaltyTeamSelect = $('<select name="penalty-' + newPenaltyId + '-teamId" onchange="updateScore()"></select>');
                newPenaltyTeamSelect.append($('<option value="<?php echo $idEquipeA; ?>"><?php echo $nomEquipeA; ?></option>'));
                newPenaltyTeamSelect.append($('<option value="<?php echo $idEquipeB; ?>"><?php echo $nomEquipeB; ?></option>'));
                newPenaltyCell.append(newPenaltyTeamSelect);

                newPenaltyCell.append(' : ');
                var newPenaltyTypeSelect = $('<select name="penalty-' + newPenaltyId + '-typeId"  onchange="updateScore()"></select>');
                <?php
                foreach ($penaltiesTypes as $penaltyType) {
                    echo 'newPenaltyTypeSelect.append($(\'<option value="' . $penaltyType['id'] . '" ' .
                        $penaltyTypeSelected . '>' . $penaltyType['name'] .
                        ' (' . $penaltyType['penaltyPoints'] . ' points)' .
                        '</option>\'));';
                }
                ?>
                newPenaltyCell.append(newPenaltyTypeSelect);

                newPenaltyCell.append($(' <button type="button" onclick="removePenalty(\'' + newPenaltyId + '\');">Supprimer</button>'));

                newPenalty.insertAfter(lastPenalty);
            }
            updateScore();
        }

        function removePenalty(penaltyId) {
            $('#penalty-' + penaltyId).remove();
            penalties.splice(penalties.indexOf(penaltyId + ''), 1);
            deletedPenalties.push(penaltyId);
            updateScore();
        }

        function setPenaltiesIdsInput() {
            $('#penaltiesIds').val(penalties);
            $('#deletedPenaltiesIds').val(deletedPenalties);
        }

    </script>
    <form name="modifierUnMatch"
          action="?menuselection=<?php echo $menuselection; ?>&smenuselection=<?php echo $smenuselection; ?>"
          method="post" onSubmit="return validateForm();">
        <table border="0" align="center" id="score">
            <tr>
                <td>
                    <select name="equipeA">
                        <?php optionsParticipant($saison, $idCategorie, $idTour, $idGroupe, $idEquipeA); ?>
                    </select>
                </td>
                <td>-</td>
                <td>
                    <select name="equipeB">
                        <?php optionsParticipant($saison, $idCategorie, $idTour, $idGroupe, $idEquipeB); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <?php
                if ($forfait == 1) {
                    if ($scoreA == $scoreGagnantParForfait AND $scoreB == 0) {
                        $AGagneParForfait = "checked='checked'";
                        $BGagneParForfait = "";
                    } elseif ($scoreB == $scoreGagnantParForfait AND $scoreA == 0) {
                        $BGagneParForfait = "checked='checked'";
                        $AGagneParForfait = "";
                    }
                } else {
                    $BGagneParForfait = "";
                    $AGagneParForfait = "";
                }
                ?>
                <td align="right">
                    <p>Gagne par forfait<input name="AGagneParForfait" type="checkbox" class='couleurCheckBox'
                                               onclick="updateForfaitStatus(this);" <?php echo $AGagneParForfait; ?> />
                    </p>
                </td>
                <td></td>
                <td>
                    <p><input name="BGagneParForfait" type="checkbox" class='couleurCheckBox'
                              onclick="updateForfaitStatus(this);" <?php echo $BGagneParForfait; ?> />Gagne par forfait
                    </p>
                </td>
            </tr>
            <tr id="score">
                <td id="scoreA" align="right">
                    <?php echo $scoreA; ?>
                </td>
                <td>-</td>
                <td id="scoreB">
                    <?php echo $scoreB; ?>
                </td>
            </tr>
            <tr id="periodAdder">
                <td align="center" colspan="3"><span onclick="addPeriod();">Ajouter</span>/<span
                        onclick="removePeriod();">supprimer</span> une période
                </td>
            </tr>
            <?php
            for ($p = 1; $p <= $nbPeriods; $p++) {
                ?>
                <tr class="period">
                    <td align="right"><?php echo $p; ?>.
                        <select name="periodScoreA[<?php echo $p; ?>]" onchange="updateScore()">
                            <?php optionsScore($periodScoreA[$p]); ?>
                        </select>
                    </td>
                    <td>-</td>
                    <td>
                        <select name="periodScoreB[<?php echo $p; ?>]" onchange="updateScore()">
                            <?php optionsScore($periodScoreB[$p]); ?>
                        </select>
                        <select name="periodTypeId[<?php echo $p; ?>]" onchange="updateScore()">
                            <?php optionsPeriodType($periodTypeId[$p], $defaultPeriodTypeID); ?>
                        </select>
                    </td>
                </tr>
                <?php
            }

            // Récupération des pénalités pour le match
            $penaltiesQuery = "SELECT pen.id, pen.idTypePenalite, pen.idEquipePenalise, e.equipe,
                                      tpen.name" . $_SESSION['__langue__'] . " AS name, ppps.pointsPenalite
                               FROM Championnat_Matchs m,
                                    Championnat_Equipes e,
                                    Championnat_Penalites pen,
                                    Championnat_Types_Penalites tpen,
                                    Championnat_Points_Penalites_Par_Saison ppps
                               WHERE m.idMatch = " . $idMatch . "
                                 AND m.idMatch = pen.idMatch
                                 AND m.saison = ppps.saison
                                 AND tpen.id = pen.idTypePenalite
                                 AND tpen.id = ppps.idTypePenalite
                                 AND pen.idEquipePenalise = e.idEquipe";
            if ($penaltiesResult = mysql_query($penaltiesQuery)) {
                while ($penalty = mysql_fetch_assoc($penaltiesResult)) {
                    ?>
                    <script>addPenalty('<?php echo $penalty["id"]; ?>');</script>
                    <tr id="penalty-<?php echo $penalty['id']; ?>" class="penalty">
                        <td align="center" colspan="3">
                            <?php
                            if ($penalty['idEquipePenalise'] == $idEquipeA) {
                                $equipeASelected = 'selected';
                                $equipeBSelected = '';
                            } else {
                                $equipeASelected = '';
                                $equipeBSelected = 'selected';
                            }

                            echo '<select name="penalty-' . $penalty['id'] . '-teamId" onchange="updateScore()">';
                            echo '<option value="' . $idEquipeA . '" ' . $equipeASelected . '>' .
                                $nomEquipeA . '</option>';
                            echo '<option value="' . $idEquipeB . '" ' . $equipeBSelected . '>' .
                                $nomEquipeB . '</option>';
                            echo '</select>';
                            echo ' : ';
                            echo '<select name="penalty-' . $penalty['id'] . '-typeId" onchange="updateScore()">';
                            foreach ($penaltiesTypes as $penaltyType) {
                                if ($penaltyType['id'] == $penalty['idTypePenalite']) {
                                    $penaltyTypeSelected = 'selected';
                                } else {
                                    $penaltyTypeSelected = '';
                                }
                                echo '<option value="' . $penaltyType['id'] . '" ' . $penaltyTypeSelected . '>' .
                                    $penaltyType['name'] . ' (' . $penaltyType['penaltyPoints'] . ' points)' .
                                    '</option>';
                            }
                            echo '</select>';
                            ?>
                            <button type="button" onclick="removePenalty(<?php echo $penalty['id']; ?>);">Supprimer
                            </button>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                printErrorMessage('Problème lors de la récupération des pénalités du match');
            }
            ?>
            <tr>
                <td align="center" colspan="3">
                    <input type="hidden" id="penaltiesIds" name="penaltiesIds" value=""/>
                    <input type="hidden" id="deletedPenaltiesIds" name="deletedPenaltiesIds" value=""/>
                    <button type="button" onclick="addPenalty();">Ajouter une pénalité</button>
                </td>
            </tr>
        </table>
        <br/>
        <table align="center" id="referees">
            <?php
            for ($p = 1; $p <= $nbPeriods; $p++) {
                if ($p == 1) {
                    $automaticRefereeSetter = ' onchange="selectionAutomatiqueArbitre%s();"';
                } else {
                    $automaticRefereeSetter = '';
                }
                ?>
                <tr class="periodReferees">
                    <td><?php echo $p; ?>.</td>
                    <td>
                        <select name="periodRefereeA[<?php echo $p; ?>]"
                                id="periodRefereeA[<?php echo $p; ?>]"<?php printf($automaticRefereeSetter, 'A'); ?>>
                            <option value="">Arbitre A</option>
                            <?php printRefereesOptionsList($referees, $periodRefereeA[$p]); ?>
                        </select>
                    </td>
                    <td>
                        <select name="periodRefereeB[<?php echo $p; ?>]"
                                id="periodRefereeB[<?php echo $p; ?>]"<?php printf($automaticRefereeSetter, 'B'); ?>>
                            <option value="">Arbitre B</option>
                            <?php printRefereesOptionsList($referees, $periodRefereeB[$p]); ?>
                        </select>
                    </td>
                    <td>
                        <select name="periodRefereeC[<?php echo $p; ?>]"
                                id="periodRefereeC[<?php echo $p; ?>]"<?php printf($automaticRefereeSetter, 'C'); ?>>
                            <option value="">Arbitre C</option>
                            <?php printRefereesOptionsList($referees, $$periodRefereeC[$p]); ?>
                        </select>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <input type="hidden" name="saison" value="<?php echo $saison; ?>"/>
        <input type="hidden" name="idCategorie" value="<?php echo $idCategorie; ?>"/>
        <input type="hidden" name="idTour" value="<?php echo $idTour; ?>"/>
        <input type="hidden" name="idGroupe" value="<?php echo $idGroupe; ?>"/>
        <table border="0" align="center">
            <!--<tr>
                <td align="right"><p>Saison :</p></td>
                <td>
                    <select name="saison" onChange="changeValeurActionForm();">
                        <?php
            for ($i = $saison - 5; $i <= $saison + 5; $i++) {
                if ($i == $saison) {
                    echo "<option value='$i' SELECTED>$i-" . ($i + 1) . "</option>";
                } else {
                    echo "<option value='$i'>$i-" . ($i + 1) . "</option>";
                }
            }
            ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right"><p>Catégorie :</p></td>
                <td>
                    <select name="idCategorie" onChange="changeEtatCategorie();">
                        <?php
            $requete = "SELECT * FROM Championnat_Categories ORDER BY idCategorie";
            $retour = mysql_query($requete);
            while ($donnees = mysql_fetch_array($retour)) {
                if ($donnees['idCategorie'] == $idCategorie) {
                    $selected = "selected='selected'";
                } else {
                    $selected = "";
                }
                echo "<option value='" . $donnees['idCategorie'] . "' " . $selected . ">" . $donnees["categorie" . $_SESSION["__langue__"] . ""] . "</option>";
            }
            ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right"><p>Tour :</p></td>
                <td>
                    <select name="idTour" onChange="changeEtatTour();">
                        <?php
            $requete = "SELECT * FROM Championnat_Types_Tours ORDER BY idTour";
            $retour = mysql_query($requete);
            while ($donnees = mysql_fetch_array($retour)) {
                if ($donnees['idTour'] == $idTour) {
                    $selected = "selected='selected'";
                } else {
                    $selected = "";
                }
                echo "<option value='" . $donnees['idTour'] . "' " . $selected . ">" . $donnees["tour" . $_SESSION["__langue__"] . ""] . "</option>";
            }
            ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right"><p>Groupe :</p></td>
                <td>
                    <select name="idGroupe">
                        <option value='0'>Qualification</option>
                        <?php
            for ($i = 1; $i <= 4; $i++) {
                if ($i == $idGroupe) {
                    $selected = "selected='selected'";
                } else {
                    $selected = "";
                }
                echo "<option valute='" . $i . "' " . $selected . ">" . $i . "</option>";
            }
            ?>
                    </select>
                </td>
            </tr>-->
            <tr>
                <td align="right"><p>Journée :</p></td>
                <td>
                    <select name="journee">
                        <?php $anneeActuelle = date("Y");
                        for ($i = 1; $i < 50; $i++) {
                            if ($i == $journee) {
                                $selected = "selected='selected'";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='" . $i . "' " . $selected . ">" . $i . "</option>";
                        }
                        ?>
                    </select></td>
            </tr>
            <tr>
                <td align="right"><p>Type de match :</p></td>
                <td>
                    <select name="idTypeMatch">
                        <?php
                        $requete = "SELECT * FROM Championnat_Types_Matchs ORDER BY idTypeMatch";
                        $retour = mysql_query($requete);
                        while ($donnees = mysql_fetch_array($retour)) {
                            if ($idTypeMatch == $donnees['idTypeMatch']) {
                                $selected = " selected='selected'";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='" . $donnees['idTypeMatch'] . "'" . $selected . ">" . $donnees["type" . $_SESSION["__langue__"] . ""] . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <?php
                if ($necessiteDefraiementArbitre == 1) {
                    $checked = "checked='checked' ";
                } else {
                    $checked = "";
                }
                ?>
                <td align="right"><input type="checkbox" name="necessiteDefraiementArbitre"
                                         id="necessiteDefraiementArbitre" <?php echo $checked; ?>/></td>
                <td><p>Nécessite le défraiement des arbitres</p></td>
            </tr>
        </table>
        <br/>
        <table class="tableauLieuDateChampionnat" align="center">
            <!--<tr>
                <td><p><?php echo VAR_LANG_SALLE; ?></p></td>
                <td><input name="salle" type="text" size="20" value="<?php echo $salle; ?>" ></td>
                <td><p><?php echo VAR_LANG_VILLE; ?></p></td>
                <td><input name="ville" type="text" size="20" value="<?php echo $ville; ?>"></td>
            </tr>-->
            <tr>
                <td><p><?php echo $agenda_lieu; ?></p></td>
                <td colspan="3">
                    <select name="idLieu">
                        <option value="NULL">Non défini</option>
                        <?php
                        $requete = "SELECT * FROM Lieux ORDER BY nomCourt";
                        $retour = mysql_query($requete);
                        while ($donnees = mysql_fetch_array($retour)) {
                            if ($donnees['id'] == $idLieu) {
                                $selected = "selected='selected'";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='" . $donnees['id'] . "' " . $selected . ">" . $donnees["nomCourt"] . ", " . $donnees['ville'] . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><p><?php echo $agenda_debut; ?></p></td>
                <td colspan="3">
                    <p>
                        <?php echo $agenda_date; ?> :
                        <select name="debutJour" id="debutJour" onChange="selectionAutomatiqueJour()">
                            <?php echo modif_liste_jour(jour($dateDebut)); ?>
                        </select>
                        <select name="debutMois" id="debutMois" onChange="selectionAutomatiqueMois()">
                            <?php echo modif_liste_mois(mois($dateDebut)); ?>
                        </select>
                        <select name="debutAnnee" id="debutAnnee" onChange="selectionAutomatiqueAnne()">
                            <?php
                            $anneeActuelle = date('Y');
                            for ($i = $saison; $i <= $saison + 1; $i++) {
                                if ($i == annee($dateDebut)) {
                                    echo "<option value=" . $i . " SELECTED>" . $i . "</option>";
                                } else {
                                    echo "<option value=" . $i . ">" . $i . "</option>";
                                }
                            }
                            ?>
                        </select>
                        <?php echo $agenda_heure; ?> :
                        <select name="debutHeure" id="debutHeure" onChange="selectionAutomatiqueHeure()">
                            <?php echo modif_liste_heure(heure($heureDebut)); ?>
                        </select>
                        <select name="debutMinute" id="debutMinute" onChange="selectionAutomatiqueMinute()">
                            <?php echo modif_liste_minute(minute($heureDebut)); ?>
                        </select>
                    </p>
                </td>
            </tr>
            <tr>
                <td><p><?php echo $agenda_fin; ?></p></td>
                <td colspan="3">
                    <p>
                        <?php echo $agenda_date; ?> :
                        <select name="finJour" id="finJour">
                            <?php echo modif_liste_jour(jour($dateFin)); ?>
                        </select>
                        <select name="finMois" id="finMois">
                            <?php echo modif_liste_mois(mois($dateFin)); ?>
                        </select>
                        <select name="finAnnee" id="finAnnee">
                            <?php
                            $anneeActuelle = date('Y');
                            for ($i = $saison; $i <= $saison + 1; $i++) {
                                if ($i == annee($dateDebut)) {
                                    echo "<option value=" . $i . " SELECTED>" . $i . "</option>";
                                } else {
                                    echo "<option value=" . $i . ">" . $i . "</option>";
                                }
                            }
                            ?>
                        </select>
                        <?php echo $agenda_heure; ?> :
                        <select name="finHeure" id="finHeure">
                            <?php echo modif_liste_heure(heure($heureFin)); ?>
                        </select>
                        <select name="finMinute" id="finMinute">
                            <?php echo modif_liste_minute(minute($heureFin)); ?>
                        </select>
                    </p>
                </td>
            </tr>
        </table>
        <p class="center"><label for="nbSpectateurs">Nombre de spectateurs : </label><input type="text"
                                                                                            name="nbSpectateurs"
                                                                                            id="nbSpectateurs"
                                                                                            value="<?php echo $nbSpectateurs; ?>"/>
        </p>
        <p class="center">Match report&eacute; <input name="matchReporte" type="checkbox"
                                                      onClick="changePartieAgendaReporte(this);"
                                                      class='couleurCheckBox'></p>
        <table class="tableauLieuDateChampionnat" align="center" style="visibility:hidden" id="partieAgendaReporte">
            <tr>
                <td><p><?php echo $agenda_debut; ?></p></td>
                <td colspan="3">
                    <p>
                        <?php echo $agenda_date; ?> :
                        <select name="debutJourReport" id="debutJourReport" onChange="selectionAutomatiqueJourReport()">
                            <?php echo modif_liste_jour(jour($dateReportDebut)); ?>
                        </select>
                        <select name="debutMoisReport" id="debutMoisReport" onChange="selectionAutomatiqueMoisReport()">
                            <?php echo modif_liste_mois(mois($dateReportDebut)); ?>
                        </select>
                        <select name="debutAnneeReport" id="debutAnneeReport"
                                onChange="selectionAutomatiqueAnneReport()">
                            <?php
                            $anneeActuelle = date('Y');
                            for ($i = $saison; $i <= $saison + 1; $i++) {
                                if ($i == annee($dateDebut)) {
                                    echo "<option value=" . $i . " SELECTED>" . $i . "</option>";
                                } else {
                                    echo "<option value=" . $i . ">" . $i . "</option>";
                                }
                            }
                            ?>
                        </select>
                        <?php echo $agenda_heure; ?> :
                        <select name="debutHeureReport" id="debutHeureReport"
                                onChange="selectionAutomatiqueHeureReport()">
                            <?php echo modif_liste_heure(heure($heureReportDebut)); ?>
                        </select>
                        <select name="debutMinuteReport" id="debutMinuteReport">
                            <?php echo modif_liste_minute(minute($heureReportDebut)); ?>
                        </select>
                    </p>
                </td>
            </tr>
            <tr>
                <td><p><?php echo $agenda_fin; ?></p></td>
                <td colspan="3">
                    <p>
                        <?php echo $agenda_date; ?> :
                        <select name="finJourReport" id="finJourReport">
                            <?php echo modif_liste_jour(jour($dateReportFin)); ?>
                        </select>
                        <select name="finMoisReport" id="finMoisReport">
                            <?php echo modif_liste_mois(mois($dateReportFin)); ?>
                        </select>
                        <select name="finAnneeReport" id="finAnneeReport">
                            <?php
                            $anneeActuelle = date('Y');
                            for ($i = $saison; $i <= $saison + 1; $i++) {
                                if ($i == annee($dateDebut)) {
                                    echo "<option value=" . $i . " SELECTED>" . $i . "</option>";
                                } else {
                                    echo "<option value=" . $i . ">" . $i . "</option>";
                                }
                            }
                            ?>
                        </select>
                        <?php echo $agenda_heure; ?> :
                        <select name="finHeureReport" id="finHeureReport">
                            <?php echo modif_liste_heure(heure($heureReportFin)); ?>
                        </select>
                        <select name="finMinuteReport" id="finMinuteReport">
                            <?php echo modif_liste_minute(minute($heureReportFin)); ?>
                        </select>
                    </p>
                </td>
            </tr>
        </table>
        <br/>
        <p align="center">
            <input type="hidden" name="idMatch" value="<?php echo $idMatch; ?>">
            <input type="hidden" name="action" value="modifierMatch3">
            <input type='submit' value='<?php echo VAR_LANG_MODIFIER; ?>'>
        </p>
        <script language="javascript">
            changeEtatCategorie();
            changeEtatTour();
            updateForfaitStatus();
        </script>
    </form>
    <?php
}
?>
