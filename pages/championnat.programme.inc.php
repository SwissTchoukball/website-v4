<?php
$categorie = 7; // ==> catergorie championnat

$currentSeasonStartYear = getCurrentSeasonStartYear();

$showFutureGamesOnly = true;

// Set the selected year
if (isset($_GET['year']) && is_numeric($_GET['year'])) {
    $selectedSeasonStartYear = $_GET['year'];
    $showFutureGamesOnly = false;
} else {
    $selectedSeasonStartYear = $currentSeasonStartYear;
}


//Récupération de la liste des arbitres
$referees = getReferees();

if ((!isset($_GET['matchID']) || !isValidMatchID($_GET['matchID'])) && (!isset($_GET['venueID']) || !isValidVenueID($_GET['venueID']))) {
    // ------------------------------------------ //
    // ! Affichage du programme
    // ------------------------------------------ //
    ?>

    <script type="text/javascript">
        function updateSelection() {
            var seasonSelector = document.getElementById('seasonSelector');
            var categoryTeamSelector = document.getElementById('categoryTeamSelector');
            var period = 'avenir';
            if (seasonSelector.value !== 'Avenir') {
                var seasonStartYear = parseInt(seasonSelector.value);
                period = seasonStartYear + '-' + (seasonStartYear + 1)
            }
            window.location = '/championnat/programme/' + period +
                '/' + slugify(categoryTeamSelector.options[categoryTeamSelector.selectedIndex].text) +
                '-' + categoryTeamSelector.value;
        }
    </script>
    <form onsubmit="$event.preventDefault()">
        <input type="hidden" name="lien" value="<?php echo $idPage; ?>"/>
        <table class="formagenda">
            <tr>
                <td align="right" width="50%"><label for="seasonSelector"><?php echo VAR_LANG_SAISON; ?> :</label></td>
                <td align="left">
                    <select name="annee" id="seasonSelector" title="Année" onChange="updateSelection();">
                        <?php
                        if ($showFutureGamesOnly) {
                            echo "<option selected='selected' value='Avenir'>" . VAR_LANG_RENCONTRES_A_VENIR . "</option>";
                        } else {
                            echo "<option value='Avenir'>" . VAR_LANG_RENCONTRES_A_VENIR . "</option>";
                        }

                        $selectedYearForSelect = $selectedSeasonStartYear;
                        if ($showFutureGamesOnly) {
                            $selectedYearForSelect = null;
                        }

                        echo getChampionshipSeasonsOptionsForSelect($currentSeasonStartYear, $selectedYearForSelect);
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right" width="50%"><label for="categoryTeamSelector"><?php echo VAR_LANG_CATEGORIE . " / " . VAR_LANG_EQUIPE; ?> :</label></td>
                <td align="left">
                    <select name="recherche" id="categoryTeamSelector" onChange="updateSelection();">
                        <option value="all">Tout</option>
                        <?php
                        $requeteCategorie = "SELECT DISTINCT Championnat_Tours.idCategorie, Championnat_Categories.categorie" . $_SESSION['__langue__'] . " FROM Championnat_Tours, Championnat_Categories WHERE saison=" . $selectedSeasonStartYear . " AND Championnat_Tours.idCategorie=Championnat_Categories.idCategorie";
                        $retourCategorie = mysql_query($requeteCategorie);
                        echo "<optgroup label='" . VAR_LANG_CATEGORIE . "'>";
                        while ($donneesCategorie = mysql_fetch_array($retourCategorie)) {
                            if ($_GET['cat-team'] == "cat" . $donneesCategorie['idCategorie']) {
                                $selected = "selected='selected'";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='cat" . $donneesCategorie['idCategorie'] . "' " . $selected . ">" . $donneesCategorie['categorie' . $_SESSION['__langue__']] . "</option>";
                        }
                        echo "</optgroup>";

                        $requeteEquipes = "SELECT DISTINCT Championnat_Equipes.idEquipe, Championnat_Equipes.equipe, Championnat_Equipes_Tours.idCategorie, Championnat_Categories.categorie" . $_SESSION['__langue__'] . " FROM Championnat_Equipes_Tours, Championnat_Equipes, Championnat_Categories WHERE saison=" . $selectedSeasonStartYear . " AND Championnat_Equipes.idEquipe=Championnat_Equipes_Tours.idEquipe AND Championnat_Equipes_Tours.idCategorie!=0 AND Championnat_Equipes_Tours.idCategorie=Championnat_Categories.idCategorie ORDER BY Championnat_Equipes_Tours.idCategorie, Championnat_Equipes.equipe";
                        $retourEquipes = mysql_query($requeteEquipes);
                        $idCategorie = "nothing";
                        while ($donneesEquipes = mysql_fetch_array($retourEquipes)) {
                            if ($idCategorie != $donneesEquipes['idCategorie']) {
                                if ($idCategorie != "nothing") {
                                    echo "</optgroup>";
                                }
                                echo "<optgroup label='" . $donneesEquipes['categorie' . $_SESSION['__langue__']] . "'>";
                                $idCategorie = $donneesEquipes['idCategorie'];
                            }
                            if ($_GET['cat-team'] == $donneesEquipes['idEquipe']) {
                                $selected = "selected='selected'";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='" . $donneesEquipes['idEquipe'] . "' " . $selected . ">" . $donneesEquipes['equipe'] . "</option>";
                        }
                        ?>
                    </select>
                    <?php
                    //echo $requeteEquipes;
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <a href="#" onclick="$('.arbitres').toggle();">Afficher/masquer les arbitres</a>
                </td>
            </tr>
        </table>
    </form>

    <?php

    // affichage des dates
    if ($showFutureGamesOnly) {
        $seasonStart = date_actuelle();
        $finAffichage = '';
    } else {
        $seasonStart = "$selectedSeasonStartYear-08-01";
        $seasonEnd = $selectedSeasonStartYear + 1;
        $seasonEnd .= "-08-01";
        $finAffichage = "AND (dateDebut<='" . $seasonEnd . "' OR dateFin<='" . $seasonEnd . "')";
    }
    if (isset($_GET['cat-team'])) {
        if ($_GET['cat-team'] == "all") {
            $recherche = "";
        } elseif (preg_match("#^cat#", $_GET['cat-team'])) {
            $rechercheCat = preg_replace("#cat(.+)#", "$1", $_GET['cat-team']);
            $recherche = "AND c.idCategorie=" . $rechercheCat;
        } else {
            $recherche = "AND (equipeA=" . $_GET['cat-team'] . " OR equipeB=" . $_GET['cat-team'] . ")";
        }
    } else {
        $recherche = "";
    }
    /*$requete = "SELECT cm.idMatch, cea.equipe AS equipeA, ceb.equipe AS equipeB, categorie".$_SESSION['__langue__']." AS categorie, dateDebut, heureDebut, heureFin, salle, ville, dateReportDebut, dateReportFin, heureReportDebut, heureReportFin, cm.idArbitreATiers1, cm.idArbitreATiers2, cm.idArbitreATiers3, cm.idArbitreBTiers1, cm.idArbitreBTiers2, cm.idArbitreBTiers3, cm.idArbitreCTiers1, cm.idArbitreCTiers2, cm.idArbitreCTiers3
                FROM Championnat_Matchs cm, Championnat_Equipes cea, Championnat_Equipes ceb, Championnat_Categories cc
                WHERE (dateDebut>='".$selectedSeasonStartYear."' OR dateFin>='".$selectedSeasonStartYear."') AND cea.idEquipe=cm.equipeA AND ceb.idEquipe=cm.equipeB AND cc.idCategorie=cm.idCategorie ".$finAffichage." ".$recherche."
                ORDER BY dateDebut, heureDebut";*/

    // TODO Remove dependency to referees id in the matches table and take the information from the the periods table.
    //		Be careful, because doing that will generate a row by period instead of a row by match.
    $requete = "SELECT m.idMatch, ea.equipe AS equipeA, eb.equipe AS equipeB, c.categorie" . $_SESSION['__langue__'] . " AS categorie,
					   m.dateDebut, m.heureDebut, m.heureFin, m.dateReportDebut, m.dateReportFin, m.heureReportDebut, m.heureReportFin, l.id AS idLieu, l.nom AS nomLieu, l.ville,
					   m.idArbitreATiers1, m.idArbitreATiers2, m.idArbitreATiers3, m.idArbitreBTiers1, m.idArbitreBTiers2, m.idArbitreBTiers3, m.idArbitreCTiers1, m.idArbitreCTiers2, m.idArbitreCTiers3
				FROM Championnat_Matchs m
				LEFT OUTER JOIN Championnat_Equipes ea ON m.equipeA = ea.idEquipe
				LEFT OUTER JOIN Championnat_Equipes eb ON m.equipeB = eb.idEquipe
				LEFT OUTER JOIN Championnat_Categories c ON m.idCategorie = c.idCategorie
				LEFT OUTER JOIN Lieux l ON m.idLieu = l.id
				WHERE (dateDebut>='" . $seasonStart . "' OR dateFin>='" . $seasonStart . "') " . $finAffichage . " " . $recherche . "
				ORDER BY dateDebut, heureDebut";

    // echo $requete;
    $retour = mysql_query($requete);
    if (!$retour) {
        printErrorMessage("Erreur lors de la récupération de la liste des matchs.<br />Message : " . mysql_error() . "<br />Requête : " . $requete);
    } else {
        $nbMatchs = mysql_num_rows($retour);
        if ($nbMatchs == 0) {
            printBlankSlateMessage("Aucun match planifié pour la sélection.");
        } else {
            ?>

            <table class="agenda">
                <tr>
                    <th width="60px"><?php echo $agenda_date; ?></th>
                    <th><?php echo $agenda_description; ?></th>
                    <th><?php echo $agenda_lieu; ?></th>
                    <th width="40px"><?php echo $agenda_debut; ?></th>
                    <th width="40px"><?php echo $agenda_fin; ?></th>
                </tr>
                <?php
                while ($donnees = mysql_fetch_array($retour)) {
                    $arbitresMatchs = array(
                        $donnees['idArbitreATiers1'],
                        $donnees['idArbitreATiers2'],
                        $donnees['idArbitreATiers3'],
                        $donnees['idArbitreBTiers1'],
                        $donnees['idArbitreBTiers2'],
                        $donnees['idArbitreBTiers3'],
                        $donnees['idArbitreCTiers1'],
                        $donnees['idArbitreCTiers2'],
                        $donnees['idArbitreCTiers3']
                    );
                    $arbitresMatchs = array_replace($arbitresMatchs,
                        array_fill_keys(
                            array_keys($arbitresMatchs, ''),
                            0
                        )
                    );
                    $arbitresMatchs = array_count_values($arbitresMatchs);

                    if ($donnees['dateDebut'] != $previousMatchDate) {
                        $dateToPrint = date_sql2date($donnees['dateDebut']);

                        if ($rowClass == '') {
                            $rowClass = 'alt_highlight';
                        } else {
                            $rowClass = '';
                        }
                    } else {
                        $dateToPrint = '';
                    }


                    ?>
                    <tr id="M<?php echo $donnees['idMatch']; ?>" class="<?php echo $rowClass; ?>">
                        <td class="center"><?php echo $dateToPrint; ?></td>
                        <td>
                            <?php
                            echo '<a href="/championnat/match/' . $donnees['idMatch'] . '">';
                            echo '<strong>' . $donnees['equipeA'] . " - " . $donnees['equipeB'] . "</strong> ";
                            echo '</a>';
                            echo "<span class='categorieProgrammeChampionnat'>" . $donnees['categorie'] . "</span>";
                            if ($donnees['dateReportDebut'] != '0000-00-00' AND $donnees['dateReportFin'] != '0000-00-00' AND $donnees['heureReportDebut'] != '00:00:00' AND $donnees['heureReportFin'] != '00:00:00') {
                                echo " : Match reporté au " . date_sql2date($donnees['dateReportDebut']);
                            }
                            ?>
                            <div class="arbitres" style="display: none;">
                                <?php
                                $refereesString = '';
                                foreach ($arbitresMatchs as $arbitreID => $nbTiers) {
                                    if ($arbitreID != 0) {
                                        $referee = $referees[$arbitreID];
                                        if ($referee['public']) {
                                            $refereeFullname = $referee['nom'] . ' ' . $referee['prenom'];
                                        } else {
                                            $refereeFullname = 'Anonyme';
                                        }
                                        reset($arbitresMatchs); //as the foreach works on a copy of the array, this won't change the foreach behavior.
                                        $refereesString .= $arbitreID === key($arbitresMatchs) ? '' : ', ';
                                        $refereesString .= $refereeFullname;
                                        //TODO indiquer le nombre de tiers si pas 3 tiers.
                                    }
                                }
                                if ($refereesString != '') {
                                    echo $refereesString;
                                } else {
                                    echo 'Aucun arbitre indiqué pour le moment.';
                                }
                                ?>
                            </div>
                        </td>
                        <td>
                            <?php
                            if ($donnees['idLieu'] > 0) {
                                echo '<a href="/lieu/' . $donnees['idLieu'] . '">' . $donnees['nomLieu'] . ", " . $donnees['ville'] . '</a>';
                            } else {
                                echo 'Non défini';
                            }
                            ?>
                        </td>
                        <td class="center">
                            <?php echo heure($donnees['heureDebut']) . ':' . minute($donnees['heureDebut']); ?>
                        </td>
                        <td class="center">
                            <?php echo heure($donnees['heureFin']) . ':' . minute($donnees['heureFin']); ?>
                        </td>
                    </tr>
                    <?php
                    $previousMatchDate = $donnees['dateDebut'];
                }
                ?>
            </table>
            <?php
        }
    }
}
?>



