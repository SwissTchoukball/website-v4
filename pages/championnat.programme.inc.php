<?php
include "includes/agenda.utility.inc.php";
$categorie = 7; // ==> catergorie championnat

if (isset($_POST['annee'])) {
    $annee = $_POST['annee'];
} else {
    $annee = "";
}


//Récupération de la liste des arbitres
$referees = getReferees();

if ((!isset($_GET['matchID']) || !isValidMatchID($_GET['matchID'])) && (!isset($_GET['venueID']) || !isValidVenueID($_GET['venueID']))) {
    // ------------------------------------------ //
    // ! Affichage du programme
    // ------------------------------------------ //
    ?>

    <form name="affichage" method="post" action="">
        <input type="hidden" name="lien" value="<?php echo $idPage; ?>"/>
        <table class="formagenda">
            <tr>
                <td align="right" width="50%"><p><?php echo VAR_LANG_SAISON; ?> :</p></td>
                <td align="left">
                    <select name="annee" id="select" onChange="affichage.submit();">
                        <?php
                        // recherche de la premiere date
                        $requeteAnnee = "SELECT MIN( Agenda_Evenement.dateDebut ) FROM `Agenda_Evenement`";
                        $recordset = mysql_query($requeteAnnee) or die ("<H3>Aucune date existe</H3>");
                        $dateMin = mysql_fetch_array($recordset) or die ("<H3>erreur extraction</H3>");
                        $anneeMin = annee($dateMin[0]);
                        $anneeMinAffichee = $anneeMin - annee(date_actuelle());

                        // championnat de aout à aout => deux date de différence => il y a deux années.
                        $nbChampionnatExistant = -$anneeMinAffichee;

                        // si on est en aout, on peut afficher une option en plus pour le nouveau championnat
                        if (mois(date_actuelle()) > 8) {
                            $nbChampionnatExistant++;
                        }

                        $anneDebutChampionnat = $anneeMin;

                        if ($annee == "") {
                            $annee = "Avenir";
                        }

                        if ($annee == "Avenir") {
                            echo "<option selected value='Avenir'>" . VAR_LANG_RENCONTRES_A_VENIR . "</option>";
                        } else {
                            echo "<option value='Avenir'>" . VAR_LANG_RENCONTRES_A_VENIR . "</option>";
                        }

                        for ($i = 0; $i < $nbChampionnatExistant; $i++) {
                            if ($annee == $anneDebutChampionnat) {
                                echo "<option selected value='$anneDebutChampionnat'>" . VAR_LANG_CHAMPIONNAT . " $anneDebutChampionnat-" . ($anneDebutChampionnat + 1) . "</option>";
                            } else {
                                echo "<option value='$anneDebutChampionnat'>" . VAR_LANG_CHAMPIONNAT . " $anneDebutChampionnat-" . ($anneDebutChampionnat + 1) . "</option>";
                            }
                            $anneDebutChampionnat++;
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php
            if ($annee == "Avenir") {
                if (date('m') < 8) {
                    $saison = date('Y') - 1;
                } else {
                    $saison = date('Y');
                }
            } else {
                $saison = $annee;
            }
            ?>
            <tr>
                <td align="right" width="50%"><p><?php echo VAR_LANG_CATEGORIE . " / " . VAR_LANG_EQUIPE; ?> :</p></td>
                <td align="left">
                    <select name="recherche" onChange="affichage.submit();">
                        <option value="tout">Tout</option>
                        <?php
                        $requeteCategorie = "SELECT DISTINCT Championnat_Tours.idCategorie, Championnat_Categories.categorie" . $_SESSION['__langue__'] . " FROM Championnat_Tours, Championnat_Categories WHERE saison=" . $saison . " AND Championnat_Tours.idCategorie=Championnat_Categories.idCategorie";
                        $retourCategorie = mysql_query($requeteCategorie);
                        echo "<optgroup label='" . VAR_LANG_CATEGORIE . "'>";
                        while ($donneesCategorie = mysql_fetch_array($retourCategorie)) {
                            if ($_POST['recherche'] == "cat" . $donneesCategorie['idCategorie']) {
                                $selected = "selected='selected'";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='cat" . $donneesCategorie['idCategorie'] . "' " . $selected . ">" . $donneesCategorie['categorie' . $_SESSION['__langue__']] . "</option>";
                        }
                        echo "</optgroup>";

                        $requeteEquipes = "SELECT DISTINCT Championnat_Equipes.idEquipe, Championnat_Equipes.equipe, Championnat_Equipes_Tours.idCategorie, Championnat_Categories.categorie" . $_SESSION['__langue__'] . " FROM Championnat_Equipes_Tours, Championnat_Equipes, Championnat_Categories WHERE saison=" . $saison . " AND Championnat_Equipes.idEquipe=Championnat_Equipes_Tours.idEquipe AND Championnat_Equipes_Tours.idCategorie!=0 AND Championnat_Equipes_Tours.idCategorie=Championnat_Categories.idCategorie ORDER BY Championnat_Equipes_Tours.idCategorie, Championnat_Equipes.equipe";
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
                            if ($_POST['recherche'] == $donneesEquipes['idEquipe']) {
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
        <input type="hidden" name="menuselection" value="<?php echo $menuselection; ?>"/>
        <input type="hidden" name="smenuselection" value="<?php echo $smenuselection; ?>"/>
    </form>

    <?php

    // affichage des dates
    if ($annee == "Avenir") {
        $annee = date_actuelle();
        $finAffichage = '';
    } else {
        $annee = "$annee-08-00";
        $jusqua = $annee + 1;
        $jusqua .= "-08-00";
        $finAffichage = "AND (dateDebut<='" . $jusqua . "' OR dateFin<='" . $jusqua . "')";
    }
    if (isset($_POST['recherche'])) {
        if ($_POST['recherche'] == "tout") {
            $recherche = "";
        } elseif (preg_match("#^cat#", $_POST['recherche'])) {
            $rechercheCat = preg_replace("#cat(.+)#", "$1", $_POST['recherche']);
            $recherche = "AND c.idCategorie=" . $rechercheCat . "";
        } else {
            $recherche = "AND (equipeA=" . $_POST['recherche'] . " OR equipeB=" . $_POST['recherche'] . ")";
        }
    } else {
        $recherche = "";
    }
    /*$requete = "SELECT cm.idMatch, cea.equipe AS equipeA, ceb.equipe AS equipeB, categorie".$_SESSION['__langue__']." AS categorie, dateDebut, heureDebut, heureFin, salle, ville, dateReportDebut, dateReportFin, heureReportDebut, heureReportFin, cm.idArbitreATiers1, cm.idArbitreATiers2, cm.idArbitreATiers3, cm.idArbitreBTiers1, cm.idArbitreBTiers2, cm.idArbitreBTiers3, cm.idArbitreCTiers1, cm.idArbitreCTiers2, cm.idArbitreCTiers3
                FROM Championnat_Matchs cm, Championnat_Equipes cea, Championnat_Equipes ceb, Championnat_Categories cc
                WHERE (dateDebut>='".$annee."' OR dateFin>='".$annee."') AND cea.idEquipe=cm.equipeA AND ceb.idEquipe=cm.equipeB AND cc.idCategorie=cm.idCategorie ".$finAffichage." ".$recherche."
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
				WHERE (dateDebut>='" . $annee . "' OR dateFin>='" . $annee . "') " . $finAffichage . " " . $recherche . "
				ORDER BY dateDebut, heureDebut";

    //echo $requete;
    $retour = mysql_query($requete);
    if (!$retour) {
        printErrorMessage("Erreur lors de la récupération de la liste des matchs.<br />Message : " . mysql_error() . "<br />Requête : " . $requete);
    } else {
        $nbMatchs = mysql_num_rows($retour);
        if ($nbMatchs == 0) {
            echo "<h3>Planification en cours...</h3>";
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
                            echo '<a href="http://' . $_SERVER['SERVER_NAME'] . '/championnat/match/' . $donnees['idMatch'] . '">';
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
                                echo '<a href="http://' . $_SERVER['SERVER_NAME'] . '/lieu/' . $donnees['idLieu'] . '">' . $donnees['nomLieu'] . ", " . $donnees['ville'] . '</a>';
                            } else {
                                echo 'Non défini';
                            }
                            ?>
                        </td>
                        <td class="center"><?php echo heure($donnees['heureDebut']); ?>
                            :<?php echo minute($donnees['heureDebut']); ?></td>
                        <td class="center"><?php echo heure($donnees['heureFin']); ?>
                            :<?php echo minute($donnees['heureFin']); ?></td>
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



