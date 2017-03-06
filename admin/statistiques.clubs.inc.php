<div class="stats-clubs">
    <?php
    echo "<h4>Nombre de clubs</h4>";
    $requeteNombreClubs = "SELECT COUNT(*) AS nbClubsFSTB FROM ClubsFstb WHERE statusId = 1 OR statusId = 2";
    $retour = mysql_query($requeteNombreClubs);
    $donnee = mysql_fetch_assoc($retour);
    $nbClubsFSTB = $donnee['nbClubsFSTB'];
    echo "<p>Il y a " . $nbClubsFSTB . " clubs membres de " . VAR_LANG_ASSOCIATION_NAME_ARTICLE . ".</p>";

    echo "<h4>Nombre de membres par club</h4>";
    $requeteNombreMembresParClub =
        "SELECT
			c.club,
			c.nbIdClub AS id,
			COUNT(if(p.idStatus=3,1,NULL)) AS nbMembresActifs,
			COUNT(if(p.idStatus=6,1,NULL)) AS nbMembresJuniors,
			COUNT(if(p.idStatus=5,1,NULL)) AS nbMembresSoutiens,
			COUNT(if(p.idStatus=4,1,NULL)) AS nbMembresPassifs,
			COUNT(if(p.idStatus=23,1,NULL)) AS nbMembresVIP,
			COUNT(if(p.idStatus!=3 AND p.idStatus!=4 AND p.idStatus!=5 AND p.idStatus!=6 AND p.idStatus!=23,1,NULL)) AS nbMembresAutres,
			COUNT(p.idDbdPersonne) AS nbMembresTotal
		 FROM ClubsFstb c
		 LEFT OUTER JOIN DBDPersonne p ON c.nbIdClub = p.idClub
		 WHERE c.statusId = 1 OR c.statusId = 2
		 GROUP BY p.idClub ";
    if (isset($_GET['ordre'])) {
        $ordre = $_GET['ordre'];
        if ($ordre == "ID") {
            $requeteNombreMembresParClub .= "ORDER BY id DESC";
        } elseif ($ordre == "club") {
            $requeteNombreMembresParClub .= "ORDER BY club ASC";
        } elseif ($ordre == "actifs") {
            $requeteNombreMembresParClub .= "ORDER BY nbMembresActifs DESC";
        } elseif ($ordre == "juniors") {
            $requeteNombreMembresParClub .= "ORDER BY nbMembresJuniors DESC";
        } elseif ($ordre == "soutiens") {
            $requeteNombreMembresParClub .= "ORDER BY nbMembresSoutiens DESC";
        } elseif ($ordre == "passifs") {
            $requeteNombreMembresParClub .= "ORDER BY nbMembresPassifs DESC";
        } elseif ($ordre == "VIP") {
            $requeteNombreMembresParClub .= "ORDER BY nbMembresVIP DESC";
        } elseif ($ordre == "autres") {
            $requeteNombreMembresParClub .= "ORDER BY nbMembresAutres DESC";
        } elseif ($ordre == "total") {
            $requeteNombreMembresParClub .= "ORDER BY nbMembresTotal DESC";
        }
        $requeteNombreMembresParClub .= ", club ASC";
    } else {
        $requeteNombreMembresParClub .= "ORDER BY c.nomPourTri ASC";
    }

    //echo $requeteNombreMembresParClub;
    $retour = mysql_query($requeteNombreMembresParClub);
    echo "<table>";
    echo "<tr>";
    echo "<th><a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&ordre=id'>ID</a></th>";
    echo "<th><a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&ordre=club'>Club</a></th>";
    echo "<th><a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&ordre=actifs'>Actifs</a></th>";
    echo "<th><a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&ordre=juniors'>Juniors</a></th>";
    echo "<th><a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&ordre=soutiens'>Soutiens</a></th>";
    echo "<th><a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&ordre=passifs'>Passifs</a></th>";
    echo "<th><a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&ordre=VIP'>VIP</a></th>";
    echo "<th><a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&ordre=autres'>Autres</a></th>";
    echo "<th><a href='?menuselection=" . $menuselection . "&smenuselection=" . $smenuselection . "&ordre=total'>Total</a></th>";
    echo "</tr>";
    $totalMembresActifs = 0;
    $totalMembresJuniors = 0;
    $totalMembresSoutiens = 0;
    $totalMembresPassifs = 0;
    $totalMembresVIP = 0;
    $totalMembresAutre = 0;

    while ($donnees = mysql_fetch_assoc($retour)) {
        echo "<tr>";
        echo "<td>" . $donnees['id'] . "</td>";
        echo "<td>" . $donnees['club'] . "</td>";
        echo "<td>" . $donnees['nbMembresActifs'] . "</td>";
        echo "<td>" . $donnees['nbMembresJuniors'] . "</td>";
        echo "<td>" . $donnees['nbMembresSoutiens'] . "</td>";
        echo "<td>" . $donnees['nbMembresPassifs'] . "</td>";
        echo "<td>" . $donnees['nbMembresVIP'] . "</td>";
        echo "<td>" . $donnees['nbMembresAutres'] . "</td>";
        echo "<td>" . $donnees['nbMembresTotal'] . "</td>";
        echo "</tr>";
        $totalMembresActifs += $donnees['nbMembresActifs'];
        $totalMembresJuniors += $donnees['nbMembresJuniors'];
        $totalMembresSoutiens += $donnees['nbMembresSoutiens'];
        $totalMembresPassifs += $donnees['nbMembresPassifs'];
        $totalMembresVIP += $donnees['nbMembresVIP'];
        $totalMembresAutre += $donnees['nbMembresAutres'];

        /*if(isset($_GET['save']) && $_SESSION['__userLevel__'] == 0) {
            $saveStatisticsQuery = "INSERT INTO DBDStatsClubs (idClub, date, nbMembresActifs, nbMembresJuniors, nbMembresSoutiens, nbMembresPassifs, nbMembresVIP, nbMembresAutres) VALUES (".$donnees['id'].", '".date('Y-m-d')."', ".$donnees['nbMembresActifs'].", ".$donnees['nbMembresJuniors'].", ".$donnees['nbMembresSoutiens'].", ".$donnees['nbMembresPassifs'].", ".$donnees['nbMembresVIP'].", ".$donnees['nbMembresAutres'].")";
            //echo $saveStatisticsQuery."<br />";
            if (mysql_query($saveStatisticsQuery)) {
                echo "<p class='notification notification--success'>Statistiques de \"".$donnees['club']."\" enregistrées.</p>";
            } else {
                echo "<p class='notification notification--error'>Erreur lors de l'enregistrement des statistiques pour \"".$donnees['club']."\".<br />Les statistiques ont peut-être déjà été enregistrées aujourd'hui.</p>";
            }
        }*/
    }
    echo "<tr>";
    echo "<th colspan='2'>TOTAUX</th>";
    echo "<th>" . $totalMembresActifs . "</th>";
    echo "<th>" . $totalMembresJuniors . "</th>";
    echo "<th>" . $totalMembresSoutiens . "</th>";
    echo "<th>" . $totalMembresPassifs . "</th>";
    echo "<th>" . $totalMembresVIP . "</th>";
    echo "<th>" . $totalMembresAutre . "</th>";
    $totalMembres = $totalMembresActifs + $totalMembresJuniors + $totalMembresSoutiens + $totalMembresPassifs + $totalMembresVIP + $totalMembresAutre;
    echo "<th class='stats-clubs__total-members' rowspan='2'>" . $totalMembres . "</th>";
    echo "</tr>";

    echo "<tr>";
    echo "<th colspan='2'>Actifs | Inactifs</th>";
    $totalVraisMembres = $totalMembresActifs + $totalMembresJuniors;
    echo "<th colspan='2'>" . $totalVraisMembres . "</th>";
    $totalMembresInactifs = $totalMembresSoutiens + $totalMembresPassifs + $totalMembresVIP + $totalMembresAutre;
    echo "<th colspan='4'>" . $totalMembresInactifs . "</th>";
    echo "</tr>";
    echo "</table>";


    if ($_SESSION['__userLevel__'] == 0) {
        ?>
        <p><a href="crons/save-clubs-stats.php" target="_blank">Enregistrer les statistiques actuelles</a> (action
            automatiquement effectuée tous les 1er janvier)</p>
        <?php
    }


    echo "<h4>Répartition Hommes/Femmes</h4>";
    $requeteRepartitionSexes =
        "SELECT
			COUNT(if(p.idStatus=3 && p.idSexe=2,1,NULL)) AS nbHActifs,
			COUNT(if(p.idStatus=3 && p.idSexe=3,1,NULL)) AS nbFActifs,
			COUNT(if(p.idStatus=3 && p.idSexe=1,1,NULL)) AS nbIActifs,
			COUNT(if(p.idStatus=6 && p.idSexe=2,1,NULL)) AS nbHJuniors,
			COUNT(if(p.idStatus=6 && p.idSexe=3,1,NULL)) AS nbFJuniors,
			COUNT(if(p.idStatus=6 && p.idSexe=1,1,NULL)) AS nbIJuniors
		 FROM DBDPersonne p, ClubsFstb c
		 WHERE (p.idStatus = 3 OR p.idStatus = 6)
		 	AND c.nbIdClub = p.idClub
		 	AND (c.statusId = 1 OR c.statusId = 2)";
    $retourRepartitionSexes = mysql_query($requeteRepartitionSexes);
    $donneesRepSexes = mysql_fetch_assoc($retourRepartitionSexes);

    $nbHActifs = $donneesRepSexes['nbHActifs'];
    $nbFActifs = $donneesRepSexes['nbFActifs'];
    $nbIActifs = $donneesRepSexes['nbIActifs'];
    $totalActifs = $nbHActifs + $nbFActifs + $nbIActifs;

    $nbHJuniors = $donneesRepSexes['nbHJuniors'];
    $nbFJuniors = $donneesRepSexes['nbFJuniors'];
    $nbIJuniors = $donneesRepSexes['nbIJuniors'];
    $totalJuniors = $nbHJuniors + $nbFJuniors + $nbIJuniors;

    $nbHActifsEtJuniors = $nbHActifs + $nbHJuniors;
    $nbFActifsEtJuniors = $nbFActifs + $nbFJuniors;
    $nbIActifsEtJuniors = $nbIActifs + $nbIJuniors;
    $totalActifsEtJuniors = $totalActifs + $totalJuniors;

    $pourcHActifs = round(($nbHActifs / $totalActifs) * 100, 2);
    $pourcFActifs = round(($nbFActifs / $totalActifs) * 100, 2);
    $pourcIActifs = round(($nbIActifs / $totalActifs) * 100, 2);

    $pourcHJuniors = round(($nbHJuniors / $totalJuniors) * 100, 2);
    $pourcFJuniors = round(($nbFJuniors / $totalJuniors) * 100, 2);
    $pourcIJuniors = round(($nbIJuniors / $totalJuniors) * 100, 2);

    $pourcHActifsEtJuniors = round(($nbHActifsEtJuniors / $totalActifsEtJuniors) * 100, 2);
    $pourcFActifsEtJuniors = round(($nbFActifsEtJuniors / $totalActifsEtJuniors) * 100, 2);
    $pourcIActifsEtJuniors = round(($nbIActifsEtJuniors / $totalActifsEtJuniors) * 100, 2);

    ?>
    <table>
        <tr>
            <th></th>
            <th><img src="admin/images/male.png" alt="hommes"/></th>
            <th><img src="admin/images/female.png" alt="femmes"/></th>
            <th><img src="admin/images/question.png" alt="indéfini"/></th>
        </tr>
        <tr>
            <th>Actifs</th>
            <td><?php echo $nbHActifs; ?> (<?php echo $pourcHActifs; ?>%)</td>
            <td><?php echo $nbFActifs; ?> (<?php echo $pourcFActifs; ?>%)</td>
            <td><?php echo $nbIActifs; ?> (<?php echo $pourcIActifs; ?>%)</td>
        </tr>
        <tr>
            <th>Juniors</th>
            <td><?php echo $nbHJuniors; ?> (<?php echo $pourcHJuniors; ?>%)</td>
            <td><?php echo $nbFJuniors; ?> (<?php echo $pourcFJuniors; ?>%)</td>
            <td><?php echo $nbIJuniors; ?> (<?php echo $pourcIJuniors; ?>%)</td>
        </tr>
        <tr>
            <th>Actifs + juniors</th>
            <td><?php echo $nbHActifsEtJuniors; ?> (<?php echo $pourcHActifsEtJuniors; ?>%)</td>
            <td><?php echo $nbFActifsEtJuniors; ?> (<?php echo $pourcFActifsEtJuniors; ?>%)</td>
            <td><?php echo $nbIActifsEtJuniors; ?> (<?php echo $pourcIActifsEtJuniors; ?>%)</td>
        </tr>
    </table>

</div>
