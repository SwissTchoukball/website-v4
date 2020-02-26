<div id="coupesuisse">
    <div class="selectionResultatsCoupeCH">
        <?php
        $mostRecentEditionQuery = "SELECT MAX(annee) AS year FROM CoupeCH_Evenements";
        $mostRecenteEditionData = mysql_query($mostRecentEditionQuery);
        $mostRecentEdition = mysql_fetch_assoc($mostRecenteEditionData);
        $thisYear = date('Y');
        if (isset($_POST['annee']) && is_numeric($_POST['annee'])) {
            $annee = $_POST['annee'];
        } else {
            $annee = $mostRecentEdition['year'];
        }
        ?>
        <form name="resultatsCoupeCH" action="" method="post">
            <h2 class="alt">
                Coupe Suisse
                <select name="annee" id="select" onChange="resultatsCoupeCH.submit();" title="Édition de la coupe">
                    <?php
                    $requete = "SELECT annee FROM CoupeCH_Evenements ORDER BY annee DESC";
                    $retour = mysql_query($requete);
                    while ($swissCupEvent = mysql_fetch_array($retour)) {
                        if ($annee == $swissCupEvent['annee']) {
                            echo "<option selected = 'selected' value = '" . $swissCupEvent['annee'] . "'>" . $swissCupEvent['annee'] . "</option>";
                        } else {
                            echo "<option value = '" . $swissCupEvent['annee'] . "'>" . $swissCupEvent['annee'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </h2>
        </form>
    </div>
    <?php
    $showSetsScore = $annee > 2010; //Because they are unknown and 15-0 scores were entered in the database

    ?>

    <?php

    // On récupère des informations sur l'édition de la coupe suisse que l'on veut afficher
    $requeteAnnee = "SELECT * FROM CoupeCH_Evenements e, CoupeCH_Categories c WHERE c.idCategorie = e.idCategorie AND e.annee = " . $annee;
    $retourAnnee = mysql_query($requeteAnnee);
    $nbCategories = mysql_num_rows($retourAnnee);
    // We loop over all the Swiss Cup categories for the selected year
    while ($eventCategory = mysql_fetch_array($retourAnnee)) {
        $idEvenement = $eventCategory['idEvenement'];
        $idCategorie = $eventCategory['idCategorie'];
        $nomCategorie = $eventCategory['nom' . $_SESSION['__langue__']];
        $nbSetsGagnants = $eventCategory['nbSetsGagnants'];
        $nbEquipes = $eventCategory['nbEquipes'];
        $dateTirage = $eventCategory['dateTirage'];
        if ($nbCategories > 1) {
            echo "<h3>" . $nomCategorie . "</h3>";
        }

        // On recherche les journées concernés et on prépare le bout de requête pour chercher les matchs
        $requeteJournee = "SELECT j.idJournee, j.no, j.dateDebut, j.idLieu, l.nom AS nomLieu, l.ville
    				   FROM CoupeCH_Journees j
    				   LEFT OUTER JOIN Lieux l ON l.id = j.idLieu
    				   WHERE j.idEvenement = " . $idEvenement;
        $retourJournee = mysql_query($requeteJournee);
        $showDayNumbers = mysql_num_rows($retourJournee) > 1;
        $j = 1;
        // We loop over all the "days" for the current category
        while ($swissCupDay = mysql_fetch_array($retourJournee)) {
            if ($j == 1) {
                $rechercheMatch = "m.idJournee = " . $swissCupDay['idJournee'];
            } else {
                $rechercheMatch .= " OR m.idJournee = " . $swissCupDay['idJournee'];
            }
            $j++;
            if (!is_null($swissCupDay['dateDebut']) && !is_null($swissCupDay['idLieu'])) {
                echo '<div class="descriptionJournee">';
                if ($showDayNumbers) {
                    echo 'Journée ' . $swissCupDay['no'] . '<br />';
                }
                echo '<strong>' . date_sql2date_joli($swissCupDay['dateDebut'], '',
                        $_SESSION['__langue__']) . '</strong><br />';
                echo '<a href="/lieu/' . $swissCupDay['idLieu'] . '">' . $swissCupDay['nomLieu'] . ', ' . $swissCupDay['ville'] . '</a>';
                echo '</div>';
            }
        }

        // Information about the draw (tirage au sort)
        if ($dateTirage >= date('Y-m-d')) {
            echo "<p class='swiss-cup__draw-announcement'>" .
                "Le tirage au sort pour le placement des équipes dans le tableau de départ aura lieu " .
                date_sql2date_joli($dateTirage, 'le', $_SESSION['__langue__']) . ".</p>";
        }

        //Calcul du nombre de matchs (pour l'instant inutile, à supprimer ou à utiliser dans l'admin)
        /*
        $nbMatchs = 0;
        $r = $nbEquipes;
        while($r != 0.5){
            $nbMatchs = $nbMatchs+$r;
            $r/2;
        }
        */

        //Délimitation de l'année
        $dateMax = $annee . "-12-31";
        $dateMin = $annee . "-01-01";

        // Initinalisation du compteur pour savoir on est quelle journée
        $idJournee = 0;

        // Récupépation des informations sur tout les matchs de l'édition.
        // We take the matches which are hapenning during an official days, and those who aren't
        $requete = "
            SELECT eA.idEquipe AS idEquipeA, eB.idEquipe AS idEquipeB, eA.nomEquipe AS nomEquipeA, eB.nomEquipe AS nomEquipeB,
            m.idMatch, m.idTypeMatch, m.ordre, j.dateDebut, TIME_FORMAT(m.heureDebut, '%H:%i:%s') AS heureDebut,
            j.idLieu, m.idTypeForfait, m.autoQualification, m.forfait, m.disqualification,
            m.scoreA1, m.scoreB1, m.scoreA2, m.scoreB2, m.scoreA3, m.scoreB3, m.scoreA4, m.scoreB4, m.scoreA5, m.scoreB5,
            j.no AS noJournee,
            l.nom AS nomLieu, l.ville,
    		tm.nom{$_SESSION["__langue__"]} AS nomTypeMatch, tf.texte{$_SESSION["__langue__"]} AS texteTypeForfait
			FROM CoupeCH_Matchs m
			LEFT OUTER JOIN CoupeCH_Equipes eA ON m.equipeA = eA.idEquipe
			LEFT OUTER JOIN CoupeCH_Equipes eB ON m.equipeB = eB.idEquipe
			LEFT OUTER JOIN CoupeCH_Types_Matchs tm ON m.idTypeMatch = tm.idTypeMatch
			LEFT OUTER JOIN CoupeCH_Journees j ON m.idJournee = j.idJournee
			LEFT OUTER JOIN CoupeCH_Types_Forfaits tf ON m.idTypeForfait = tf.idTypeForfait
			LEFT OUTER JOIN Lieux l ON j.idLieu = l.id
			WHERE m.idEvenement = {$idEvenement} AND m.idJournee IS NOT NULL
			UNION
			SELECT eA.idEquipe AS idEquipeA, eB.idEquipe AS idEquipeB, eA.nomEquipe AS nomEquipeA, eB.nomEquipe AS nomEquipeB,
            m.idMatch, m.idTypeMatch, m.ordre, m.dateDebut, TIME_FORMAT(m.heureDebut, '%H:%i:%s') AS heureDebut,
            m.idLieu, m.idTypeForfait, m.autoQualification, m.forfait, m.disqualification,
            m.scoreA1, m.scoreB1, m.scoreA2, m.scoreB2, m.scoreA3, m.scoreB3, m.scoreA4, m.scoreB4, m.scoreA5, m.scoreB5,
            NULL AS noJournee,
            l.nom AS nomLieu, l.ville,
    		tm.nom{$_SESSION["__langue__"]} AS nomTypeMatch, tf.texte{$_SESSION["__langue__"]} AS texteTypeForfait
			FROM CoupeCH_Matchs m
			LEFT OUTER JOIN CoupeCH_Equipes eA ON m.equipeA = eA.idEquipe
			LEFT OUTER JOIN CoupeCH_Equipes eB ON m.equipeB = eB.idEquipe
			LEFT OUTER JOIN CoupeCH_Types_Matchs tm ON m.idTypeMatch = tm.idTypeMatch
			LEFT OUTER JOIN CoupeCH_Types_Forfaits tf ON m.idTypeForfait = tf.idTypeForfait
			LEFT OUTER JOIN Lieux l ON m.idLieu = l.id
			WHERE m.idEvenement = {$idEvenement} AND m.idJournee IS NULL
			";
        //echo $requete;
        $retour = mysql_query($requete);
        // We loop over each match for the current category
        while ($match = mysql_fetch_array($retour)) {

            // Détermination du nom des équipes.
            $equipeA = VAR_LANG_INCONNU;
            $equipeB = VAR_LANG_INCONNU;

            if ($match['autoQualification'] == "B") {
                $equipeA = "-";
            } else {
                if ($match['autoQualification'] == "A") {
                    $equipeB = "-";
                }
            }

            if ($match['idEquipeA'] != 0) {
                $equipeA = $match['nomEquipeA'];
            }

            if ($match['idEquipeB'] != 0) {
                $equipeB = $match['nomEquipeB'];
            }

            // Détermination du score final
            $scoreFinalA = 0;
            $scoreFinalB = 0;
            if ($nbSetsGagnants > 0) { // Jeu en set
                $maxSets = ($nbSetsGagnants * 2) - 1; //nombre de sets que l'on peut jouer au maximum. ATTENTION!!! La base de données supporte jusqu'à 3 sets gagnants, il faudra rajouter des colonnes si il y a + de sets gagnants.
                for ($i = 1; $i <= $maxSets; $i++) {
                    if ($match['scoreA' . $i] > $match['scoreB' . $i]) {
                        $scoreFinalA++;
                    } else if ($match['scoreA' . $i] < $match['scoreB' . $i]) {
                        $scoreFinalB++;
                    }
                }
            } else // Jeu avec score normal
            {
                $scoreFinalA = $scoreA1;
                $scoreFinalB = $scoreB1;
            }

            // Récupération des informations sur la journée
            $noJournee = $match['noJournee'];
            if (!is_null($match['nomLieu']) && !is_null($match['ville'])) {
                $lieu = $match['nomLieu'] . ', ' . $match['ville'];
            } else {
                $lieu = '&nbsp;';
            }
            $idLieu = $match['idLieu'];

            $typeMatch = $match['nomTypeMatch'];
            if ($match['idTypeMatch'] == 16 ||
                $match['idTypeMatch'] == 8 ||
                $match['idTypeMatch'] == 4 ||
                $match['idTypeMatch'] == 2 ||
                $match['idTypeMatch'] == 52 ||
                $match['idTypeMatch'] == 94 ||
                $match['idTypeMatch'] == 92 ||
                $match['idTypeMatch'] == 132
            ) {
                $numeroMatch = " " . $match['ordre'];
            } else {
                $numeroMatch = "";
            }

            $dateSQL = $match['dateDebut'];

            if (!is_null($dateSQL)) {
                //Transformation de la forme de la date et de l'heure
                $date = date_sql2date($dateSQL); //Date SQL en Date normale
                $date = preg_replace('#(.+)-(.+)-(.+)#', '$1.$2.$3', $date); //Remplacement des - par des .
                if ($match['heureDebut'] != '00:00:00') {
                    $heure = substr($match['heureDebut'], 0, 5); //Heure sans les secondes
                    $heure = preg_replace('#(.+):(.+)#', '$1h$2', $heure); //Remplacement du : par h
                } else {
                    $heure = '';
                }
            } else {
                $date = '';
                $heure = '';
            }

            // Tableaux qui apparaissent quand la souris survole le match concerné
            ?>
            <div class="informationsMatch" id="messageInitial">
                <div class="informationsBoxEquipes"><?php echo VAR_LANG_SURVOL_TABLEAU; ?></div>
            </div>
            <div class="informationsMatch" id="infomatch<?php echo $match['idMatch']; ?>">
                <div class="informationsBoxJournee">
                    <?php echo !$showDayNumbers || is_null($noJournee) ? '' : VAR_LANG_JOURNEE . " " . $noJournee; ?>
                </div>
                <div class="informationsBoxTypeMatch"><?php echo $typeMatch . $numeroMatch; ?></div>
                <?php
                if ($match['autoQualification'] == null) {
                    echo '<div class = "informationsBoxEquipes">' . $equipeA . ' - ' . $equipeB . '</div>';
                }

                $equipeCasSpecial = '';
                if ($match['autoQualification'] == "A" || $match['forfait'] == "A" || $match['disqualification'] == "A") {
                    $equipeCasSpecial = $equipeA;
                } else if ($match['autoQualification'] == "B" || $match['forfait'] == "B" || $match['disqualification'] == "B") {
                    $equipeCasSpecial = $equipeB;
                }

                if ($equipeCasSpecial != '') {
                    echo '<div class = "informationsBoxScore">' . $equipeCasSpecial . ' ' . $match['texteTypeForfait'] . '</div>';
                }

                if (in_array($match['idTypeForfait'], array(4, 5))) {
                    echo '<div class = "informationsBoxScore">' . $match['texteTypeForfait'] . '</div>';
                }

                // Affichage du score
                if ($match['autoQualification'] == null &&
                    $match['forfait'] == null &&
                    $match['disqualification'] == null &&
                    !($scoreFinalA == 0 && $scoreFinalB == 0)
                ) {
                    echo '<div class = "informationsBoxScore">' . VAR_LANG_SCORE_FINAL . ' : ' . $scoreFinalA . ' - ' . $scoreFinalB . '</div>';

                    if ($showSetsScore) {
                        for ($i = 1; $i <= $maxSets; $i++) { //boucle pour chaque set
                            if (!($match['scoreA' . $i] == 0 && $match['scoreB' . $i] == 0)) { //On n'affiche pas le score si il est nul.
                                echo '<div class = "informationsBoxSet">Set ' . $i . ' : ' . $match['scoreA' . $i] . ' - ' . $match['scoreB' . $i] . '</div>';
                            }
                        }
                    }
                }
                ?>
                <div class="informationsBoxDate">
                    <?php
                    if ($match['dateDebut'] != '0000-00-00') {
                        echo $date;
                    }
                    if ($match['heureDebut'] != "00:00:00") {
                        echo " à " . $heure;
                    }
                    ?>
                </div>
                <div class="informationsBoxLieu"><a href="/lieu/<?php echo $idLieu; ?>"><?php echo $lieu; ?></a></div>
            </div>
            <?php
        }


        // Affichage des arbres
        // Le script accepte maximum 16 équipes, donc

        for ($a = 1; $a <= 13; $a += 4) {

            if ($a == 1) {
                $classArbre = 'arbreCoupeHuitiemes';
                $nbEquipes = 16;
            } else if ($a == 5 || $a == 13) {
                $classArbre = 'arbreCoupeDemis';
                $nbEquipes = 4;
            } else if ($a == 9) {
                $classArbre = 'arbreCoupeQuarts';
                $nbEquipes = 8;
            }

            $nbTours = log($nbEquipes) / log(2);
            $kInitial = 5 - $nbTours;

            $meilleurePlaceAtteignable = $a;
            $meilleurePlaceAtteignableBis = $a + 2; // Petite-finales (3ème, 7ème, 11ème, 1ème place)

            $requeteExistenceMatchs = "SELECT m.idMatch
								   FROM CoupeCH_Matchs m, CoupeCH_Types_Matchs tm
								   WHERE m.idTypeMatch=tm.idTypeMatch
								   AND m.idEvenement = {$idEvenement}
								   AND (tm.meilleurePlaceAtteignable = {$meilleurePlaceAtteignable}
								    	OR meilleurePlaceAtteignable = {$meilleurePlaceAtteignableBis})";
            //echo $requeteExistenceMatchs;
            $retourExistenceMatchs = mysql_query($requeteExistenceMatchs);

            if (mysql_num_rows($retourExistenceMatchs) > 0) {

                echo '<div class="' . $classArbre . '">';
                echo $a != 1 ? '<h3>Coupe pour la ' . $meilleurePlaceAtteignable . 'ème place</h3>' : '';

                $idTypeMatch = $nbEquipes / 2;

                $c = 1;
                // We loop over each column of the tree
                for ($k = $kInitial; $k <= 4; $k++) {

                    if ($k == 1) {
                        $classColonne = 'colonneHuitiemes';
                    } else if ($k == 2) {
                        $classColonne = 'colonneQuarts';
                    } else if ($k == 3) {
                        $classColonne = 'colonneDemis';
                    } else if ($k == 4) {
                        $classColonne = 'colonneFinales';
                    }

                    if ($meilleurePlaceAtteignable != 1) {
                        if ($idTypeMatch == 1) {
                            $idTypeMatch = $meilleurePlaceAtteignable;
                        } else {
                            $idTypeMatch = $meilleurePlaceAtteignable . "" . $idTypeMatch;
                        }
                    }

                    echo '<div class="' . $classColonne . '">';
                    //Si colonne de la finale, afficher aussi la petite finale.
                    if ($idTypeMatch == $meilleurePlaceAtteignable) {
                        $rechercheTypeMatch = "(m.idTypeMatch = " . $meilleurePlaceAtteignable . " OR m.idTypeMatch = " . $meilleurePlaceAtteignableBis . ")";
                    } else {
                        $rechercheTypeMatch = "m.idTypeMatch = " . $idTypeMatch;
                    }
                    $rechercheTypeMatch .= " AND (meilleurePlaceAtteignable = " . $meilleurePlaceAtteignable . " OR meilleurePlaceAtteignable = " . $meilleurePlaceAtteignableBis . ")";

                    $requete = "SELECT * FROM CoupeCH_Matchs m, CoupeCH_Types_Matchs tm WHERE m.idTypeMatch=tm.idTypeMatch AND m.idEvenement = {$idEvenement} AND {$rechercheTypeMatch} ORDER BY m.ordre";
                    //echo $requete;
                    $retour = mysql_query($requete);
                    while ($match = mysql_fetch_array($retour)) {
                        $idMatch = $match['idMatch'];
                        // Détermination du nom des équipes.
                        if ($match['equipeA'] == 0) {
                            if ($match['idTypeForfait'] == 3) {
                                $equipeA = "-";
                            } else {
                                $equipeA = "";
                            }
                        } else {
                            $requeteEquipeA = "SELECT nomEquipe FROM CoupeCH_Equipes WHERE idEquipe = " . $match['equipeA'];
                            $retourEquipeA = mysql_query($requeteEquipeA);
                            $teamA = mysql_fetch_array($retourEquipeA);
                            $equipeA = $teamA['nomEquipe'];
                        }
                        if ($match['equipeB'] == 0) {
                            if ($match['idTypeForfait'] == 3) {
                                $equipeB = "-";
                            } else {
                                $equipeB = "";
                            }
                        } else {
                            $requeteEquipeB = "SELECT nomEquipe FROM CoupeCH_Equipes WHERE idEquipe = " . $match['equipeB'];
                            $retourEquipeB = mysql_query($requeteEquipeB);
                            $teamB = mysql_fetch_array($retourEquipeB);
                            $equipeB = $teamB['nomEquipe'];
                        }

                        // Détermination du core final
                        $scoreFinalA = 0;
                        $scoreFinalB = 0;
                        if ($nbSetsGagnants > 0) { //Jeu en set
                            $maxSets = ($nbSetsGagnants * 2) - 1; //nombre de sets que l'on peut jouer au maximum. ATTENTION!!! La base de données supporte jusqu'à 3 sets gagnants, il faudra rajouter des colonnes si il y a + de set gagnants.
                            for ($i = 1; $i <= $maxSets; $i++) {
                                if ($match['scoreA' . $i] > $match['scoreB' . $i]) {
                                    $scoreFinalA++;
                                } else if ($match['scoreA' . $i] < $match['scoreB' . $i]) {
                                    $scoreFinalB++;
                                }
                            }
                        } else { // Jeu avec score normal
                            $scoreFinalA = $scoreA1;
                            $scoreFinalB = $scoreB1;
                        }

                        if ($scoreFinalA > $scoreFinalB) {
                            $resultatA = "gagnant";
                            $resultatB = "perdant";
                        } else if ($scoreFinalA < $scoreFinalB) {
                            $resultatA = "perdant";
                            $resultatB = "gagnant";
                        } else if ($match['autoQualification'] == "A" || $match['forfait'] == "B" || $match['disqualification'] == "B") {
                            $resultatA = "gagnant";
                            $resultatB = "perdant";
                        } else if ($match['autoQualification'] == "B" || $match['forfait'] == "A" || $match['disqualification'] == "A") {
                            $resultatA = "perdant";
                            $resultatB = "gagnant";
                        } else {
                            $resultatA = "ajouer";
                            $resultatB = "ajouer";
                        }
                        ?>
                        <div class="boxEquipe<?php echo $c; ?>A"
                             onMouseOver="afficherInfoMatch('<?php echo $idMatch; ?>');">
                            <span class="<?php echo $resultatA; ?>"><?php echo $equipeA ?></span>
                        </div>
                        <div class="boxEquipe<?php echo $c; ?>B"
                             onMouseOver="afficherInfoMatch('<?php echo $idMatch; ?>');">
                            <span class="<?php echo $resultatB; ?>"><?php echo $equipeB ?></span>
                        </div>

                        <?php
                    }
                    echo '</div>';
                    $c++;
                    $idTypeMatch = $nbEquipes / pow(2, $c - 1) / 2;
                }
                echo '</div>';
            }
        }
    } // fin de la boucle pour chaque catégorie
    ?>

    <?php showCommissionHead(21); ?>

    <iframe
            src="https://www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fcoupesuisse&width=360&colorscheme=light&show_faces=false&stream=false&header=false&height=77"
            scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:360px; height:77px;"
            allowTransparency="true"></iframe>

    <script language="JavaScript" type="text/javascript">
        // Javascript pour afficher la box d'information sur un match.
        var current;
        $('#messageInitial').show();

        function afficherInfoMatch(num) {
            $('#messageInitial').hide();
            $('#infomatch' + current).hide();
            $('#infomatch' + num).show();
            current = num;
        }

        $('#contenu').mouseleave(function () {
            $('#infomatch' + current).hide();
            $('#messageInitial').show();
        });
    </script>
</div>
