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
                    while ($donnees = mysql_fetch_array($retour)) {
                        if ($annee == $donnees['annee']) {
                            echo "<option selected = 'selected' value = '" . $donnees['annee'] . "'>" . $donnees['annee'] . "</option>";
                        } else {
                            echo "<option value = '" . $donnees['annee'] . "'>" . $donnees['annee'] . "</option>";
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
    while ($donneesAnnee = mysql_fetch_array($retourAnnee)) {
        $idEvenement = $donneesAnnee['idEvenement'];
        $idCategorie = $donneesAnnee['idCategorie'];
        $nomCategorie = $donneesAnnee['nom' . $_SESSION['__langue__']];
        $nbSetsGagnants = $donneesAnnee['nbSetsGagnants'];
        $nbEquipes = $donneesAnnee['nbEquipes'];
        if ($nbCategories > 1) {
            echo "<h3>" . $nomCategorie . "</h3>";
        }

        // On recherche les journées concernés et on prépare le bout de requête pour chercher les matchs
        $requeteJournee = "SELECT j.idJournee, j.no, j.dateDebut, j.idLieu, l.nom AS nomLieu, l.ville
    				   FROM CoupeCH_Journees j
    				   LEFT OUTER JOIN Lieux l ON l.id = j.idLieu
    				   WHERE j.idEvenement = " . $idEvenement;
        $retourJournee = mysql_query($requeteJournee);
        $j = 1;
        while ($donneesJournee = mysql_fetch_array($retourJournee)) {
            if ($j == 1) {
                $rechercheMatch = "m.idJournee = " . $donneesJournee['idJournee'];
            } else {
                $rechercheMatch .= " OR m.idJournee = " . $donneesJournee['idJournee'];
            }
            $j++;
            if (!is_null($donneesJournee['dateDebut']) && !is_null($donneesJournee['idLieu'])) {
                echo '<div class="descriptionJournee">';
                echo '<strong>Journée ' . $donneesJournee['no'] . '</strong><br />';
                echo date_sql2date_joli($donneesJournee['dateDebut'], '', $_SESSION['__langue__']) . '<br />';
                echo '<a href="/lieu/' . $donneesJournee['idLieu'] . '">' . $donneesJournee['nomLieu'] . ', ' . $donneesJournee['ville'] . '</a>';
                echo '</div>';
            }
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

        //Récupépation des informations sur tout les matchs de l'édition.
        $requete = "SELECT eA.idEquipe AS idEquipeA, eB.idEquipe AS idEquipeB, eA.nomEquipe AS nomEquipeA, eB.nomEquipe AS nomEquipeB, m.*, j.*, l.nom AS nomLieu, l.ville,
    			tm.nom" . $_SESSION["__langue__"] . " AS nomTypeMatch, tf.texte" . $_SESSION["__langue__"] . " AS texteTypeForfait
			  FROM CoupeCH_Matchs m
			  LEFT OUTER JOIN CoupeCH_Equipes eA ON m.equipeA = eA.idEquipe
			  LEFT OUTER JOIN CoupeCH_Equipes eB ON m.equipeB = eB.idEquipe
			  LEFT OUTER JOIN CoupeCH_Types_Matchs tm ON m.idTypeMatch = tm.idTypeMatch
			  LEFT OUTER JOIN CoupeCH_Journees j ON m.idJournee = j.idJournee
			  LEFT OUTER JOIN CoupeCH_Types_Forfaits tf ON m.idTypeForfait = tf.idTypeForfait
			  LEFT OUTER JOIN Lieux l ON j.idLieu = l.id
			  WHERE " . $rechercheMatch;
        //echo $requete;
        $retour = mysql_query($requete);
        while ($donnees = mysql_fetch_array($retour)) {

            // Détermination du nom des équipes.
            $equipeA = VAR_LANG_INCONNU;
            $equipeB = VAR_LANG_INCONNU;

            if ($donnees['autoQualification'] == "B") {
                $equipeA = "-";
            } else {
                if ($donnees['autoQualification'] == "A") {
                    $equipeB = "-";
                }
            }

            if ($donnees['idEquipeA'] != 0) {
                $equipeA = $donnees['nomEquipeA'];
            }

            if ($donnees['idEquipeB'] != 0) {
                $equipeB = $donnees['nomEquipeB'];
            }

            // Détermination du score final
            $scoreFinalA = 0;
            $scoreFinalB = 0;
            if ($nbSetsGagnants > 0) { // Jeu en set
                $maxSets = ($nbSetsGagnants * 2) - 1; //nombre de sets que l'on peut jouer au maximum. ATTENTION!!! La base de données supporte jusqu'à 3 sets gagnants, il faudra rajouter des colonnes si il y a + de sets gagnants.
                for ($i = 1; $i <= $maxSets; $i++) {
                    if ($donnees['scoreA' . $i] > $donnees['scoreB' . $i]) {
                        $scoreFinalA++;
                    } elseif ($donnees['scoreA' . $i] < $donnees['scoreB' . $i]) {
                        $scoreFinalB++;
                    }
                }
            } else // Jeu avec score normal
            {
                $scoreFinalA = $scoreA1;
                $scoreFinalB = $scoreB1;
            }

            // Récupération des informations sur la journée
            $noJournee = $donnees['no'];
            if (!is_null($donnees['nomLieu']) && !is_null($donnees['ville'])) {
                $lieu = $donnees['nomLieu'] . ', ' . $donnees['ville'];
            } else {
                $lieu = '&nbsp;';
            }
            $idLieu = $donnees['idLieu'];

            $typeMatch = $donnees['nomTypeMatch'];
            if ($donnees['idTypeMatch'] == 16 ||
                $donnees['idTypeMatch'] == 8 ||
                $donnees['idTypeMatch'] == 4 ||
                $donnees['idTypeMatch'] == 2 ||
                $donnees['idTypeMatch'] == 52 ||
                $donnees['idTypeMatch'] == 94 ||
                $donnees['idTypeMatch'] == 92 ||
                $donnees['idTypeMatch'] == 132
            ) {
                $numeroMatch = " " . $donnees['ordre'];
            } else {
                $numeroMatch = "";
            }

            $dateSQL = $donnees['dateDebut'];

            if (!is_null($dateSQL)) {
                //Transformation de la forme de la date et de l'heure
                $date = date_sql2date($dateSQL); //Date SQL en Date normale
                $date = preg_replace('#(.+)-(.+)-(.+)#', '$1.$2.$3', $date); //Remplacement des - par des .
                if ($donnees['heureDebut'] != '00:00:00') {
                    $heure = substr($donnees['heureDebut'], 0, 5); //Heure sans les secondes
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
            <div class="informationsMatch" id="infomatch<?php echo $donnees['idMatch']; ?>">
                <div class="informationsBoxJournee"><?php echo VAR_LANG_JOURNEE . " " . $noJournee; ?></div>
                <div class="informationsBoxTypeMatch"><?php echo $typeMatch . $numeroMatch; ?></div>
                <?php
                if ($donnees['autoQualification'] == null) {
                    echo '<div class = "informationsBoxEquipes">' . $equipeA . ' - ' . $equipeB . '</div>';
                }

                $equipeCasSpecial = '';
                if ($donnees['autoQualification'] == "A" || $donnees['forfait'] == "A" || $donnees['disqualification'] == "A") {
                    $equipeCasSpecial = $equipeA;
                } elseif ($donnees['autoQualification'] == "B" || $donnees['forfait'] == "B" || $donnees['disqualification'] == "B") {
                    $equipeCasSpecial = $equipeB;
                }

                if ($equipeCasSpecial != '') {
                    echo '<div class = "informationsBoxScore">' . $equipeCasSpecial . ' ' . $donnees['texteTypeForfait'] . '</div>';
                }

                if (in_array($donnees['idTypeForfait'], array(4, 5))) {
                    echo '<div class = "informationsBoxScore">' . $donnees['texteTypeForfait'] . '</div>';
                }

                // Affichage du score
                if ($donnees['autoQualification'] == null &&
                    $donnees['forfait'] == null &&
                    $donnees['disqualification'] == null &&
                    !($scoreFinalA == 0 && $scoreFinalB == 0)
                ) {
                    echo '<div class = "informationsBoxScore">' . VAR_LANG_SCORE_FINAL . ' : ' . $scoreFinalA . ' - ' . $scoreFinalB . '</div>';

                    if ($showSetsScore) {
                        for ($i = 1; $i <= $maxSets; $i++) { //boucle pour chaque set
                            if (!($donnees['scoreA' . $i] == 0 && $donnees['scoreB' . $i] == 0)) { //On n'affiche pas le score si il est nul.
                                echo '<div class = "informationsBoxSet">Set ' . $i . ' : ' . $donnees['scoreA' . $i] . ' - ' . $donnees['scoreB' . $i] . '</div>';
                            }
                        }
                    }
                }
                ?>
                <div class="informationsBoxDate">
                    <?php
                    if ($donnees['dateDebut'] != '0000-00-00') {
                        echo $date;
                    }
                    if ($donnees['heureDebut'] != "00:00:00") {
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
            } elseif ($a == 5 || $a == 13) {
                $classArbre = 'arbreCoupeDemis';
                $nbEquipes = 4;
            } elseif ($a == 9) {
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
								   AND (" . $rechercheMatch . ")
								   AND (tm.meilleurePlaceAtteignable = " . $meilleurePlaceAtteignable . "
								    	OR meilleurePlaceAtteignable = " . $meilleurePlaceAtteignableBis . ")";
            //echo $requeteExistenceMatchs;
            $retourExistenceMatchs = mysql_query($requeteExistenceMatchs);

            if (mysql_num_rows($retourExistenceMatchs) > 0) {

                echo '<div class="' . $classArbre . '">';
                echo $a != 1 ? '<h3>Coupe pour la ' . $meilleurePlaceAtteignable . 'ème place</h3>' : '';

                $idTypeMatch = $nbEquipes / 2;

                $c = 1;
                for ($k = $kInitial; $k <= 4; $k++) { // Boucle sur chaque colonne de l'arbre

                    if ($k == 1) {
                        $classColonne = 'colonneHuitiemes';
                    } elseif ($k == 2) {
                        $classColonne = 'colonneQuarts';
                    } elseif ($k == 3) {
                        $classColonne = 'colonneDemis';
                    } elseif ($k == 4) {
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

                    $requete = "SELECT * FROM CoupeCH_Matchs m, CoupeCH_Types_Matchs tm WHERE m.idTypeMatch=tm.idTypeMatch AND (" . $rechercheMatch . ") AND (" . $rechercheTypeMatch . ") ORDER BY m.ordre";
                    //echo $requete;
                    $retour = mysql_query($requete);
                    while ($donnees = mysql_fetch_array($retour)) {
                        $idMatch = $donnees['idMatch'];
                        // Détermination du nom des équipes.
                        if ($donnees['equipeA'] == 0) {
                            if ($donnees['idTypeForfait'] == 3) {
                                $equipeA = "-";
                            } else {
                                $equipeA = "";
                            }
                        } else {
                            $requeteEquipeA = "SELECT nomEquipe FROM CoupeCH_Equipes WHERE idEquipe = " . $donnees['equipeA'];
                            $retourEquipeA = mysql_query($requeteEquipeA);
                            $donneesEquipeA = mysql_fetch_array($retourEquipeA);
                            $equipeA = $donneesEquipeA['nomEquipe'];
                        }
                        if ($donnees['equipeB'] == 0) {
                            if ($donnees['idTypeForfait'] == 3) {
                                $equipeB = "-";
                            } else {
                                $equipeB = "";
                            }
                        } else {
                            $requeteEquipeB = "SELECT nomEquipe FROM CoupeCH_Equipes WHERE idEquipe = " . $donnees['equipeB'];
                            $retourEquipeB = mysql_query($requeteEquipeB);
                            $donneesEquipeB = mysql_fetch_array($retourEquipeB);
                            $equipeB = $donneesEquipeB['nomEquipe'];
                        }

                        // Détermination du core final
                        $scoreFinalA = 0;
                        $scoreFinalB = 0;
                        if ($nbSetsGagnants > 0) { //Jeu en set
                            $maxSets = ($nbSetsGagnants * 2) - 1; //nombre de sets que l'on peut jouer au maximum. ATTENTION!!! La base de données supporte jusqu'à 3 sets gagnants, il faudra rajouter des colonnes si il y a + de set gagnants.
                            for ($i = 1; $i <= $maxSets; $i++) {
                                if ($donnees['scoreA' . $i] > $donnees['scoreB' . $i]) {
                                    $scoreFinalA++;
                                } elseif ($donnees['scoreA' . $i] < $donnees['scoreB' . $i]) {
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
                        } elseif ($scoreFinalA < $scoreFinalB) {
                            $resultatA = "perdant";
                            $resultatB = "gagnant";
                        } elseif ($donnees['autoQualification'] == "A" || $donnees['forfait'] == "B" || $donnees['disqualification'] == "B") {
                            $resultatA = "gagnant";
                            $resultatB = "perdant";
                        } elseif ($donnees['autoQualification'] == "B" || $donnees['forfait'] == "A" || $donnees['disqualification'] == "A") {
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

    <div class="banniereSiteCoupeSuisse"><a href="http://www.coupesuisse.com"><img
                src="/pictures/banniere-coupesuisse.com.png" alt="Allez sur CoupeSuisse.com"/></a></div>

    <iframe
        src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fcoupesuisse&width=360&colorscheme=light&show_faces=false&stream=false&header=false&height=77"
        scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:360px; height:77px;"
        allowTransparency="true"></iframe>

    <?php showCommissionHead(3); ?>

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
