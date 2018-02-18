<div class="stats-clubs">
    <?php
    echo "<h4>Nombre de clubs</h4>";
    echo "<p>Il y a " . ClubService::getNumberOfClubs() . " clubs membres de " . VAR_LANG_ASSOCIATION_NAME_ARTICLE . ".</p>";

    echo "<h4>Nombre de membres par club</h4>";
    echo "<table>";
    echo "<tr>";
    echo "<th><a href='?" . $navigation->getCurrentPageLinkQueryString() . "&ordre=id'>ID</a></th>";
    echo "<th><a href='?" . $navigation->getCurrentPageLinkQueryString() . "&ordre=club'>Club</a></th>";
    echo "<th><a href='?" . $navigation->getCurrentPageLinkQueryString() . "&ordre=actifs'>Actifs</a></th>";
    echo "<th><a href='?" . $navigation->getCurrentPageLinkQueryString() . "&ordre=juniors'>Juniors</a></th>";
    echo "<th><a href='?" . $navigation->getCurrentPageLinkQueryString() . "&ordre=soutiens'>Soutiens</a></th>";
    echo "<th><a href='?" . $navigation->getCurrentPageLinkQueryString() . "&ordre=passifs'>Passifs</a></th>";
    echo "<th><a href='?" . $navigation->getCurrentPageLinkQueryString() . "&ordre=VIP'>VIP</a></th>";
    echo "<th><a href='?" . $navigation->getCurrentPageLinkQueryString() . "&ordre=autres'>Autres</a></th>";
    echo "<th><a href='?" . $navigation->getCurrentPageLinkQueryString() . "&ordre=total'>Total</a></th>";
    echo "</tr>";
    $totalMembresActifs = 0;
    $totalMembresJuniors = 0;
    $totalMembresSoutiens = 0;
    $totalMembresPassifs = 0;
    $totalMembresVIP = 0;
    $totalMembresAutre = 0;

    $clubs = ClubService::getClubsStats($_GET['ordre']);
    foreach ($clubs as $club) {
        echo "<tr>";
        echo "<td>" . $club['id'] . "</td>";
        echo "<td>" . $club['club'] . "</td>";
        echo "<td>" . $club['nbMembresActifs'] . "</td>";
        echo "<td>" . $club['nbMembresJuniors'] . "</td>";
        echo "<td>" . $club['nbMembresSoutiens'] . "</td>";
        echo "<td>" . $club['nbMembresPassifs'] . "</td>";
        echo "<td>" . $club['nbMembresVIP'] . "</td>";
        echo "<td>" . $club['nbMembresAutres'] . "</td>";
        echo "<td>" . $club['nbMembresTotal'] . "</td>";
        echo "</tr>";
        $totalMembresActifs += $club['nbMembresActifs'];
        $totalMembresJuniors += $club['nbMembresJuniors'];
        $totalMembresSoutiens += $club['nbMembresSoutiens'];
        $totalMembresPassifs += $club['nbMembresPassifs'];
        $totalMembresVIP += $club['nbMembresVIP'];
        $totalMembresAutre += $club['nbMembresAutres'];
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
    $genderDistribution = ClubService::getGenderDistribution();

    $nbHActifs = $genderDistribution['nbHActifs'];
    $nbFActifs = $genderDistribution['nbFActifs'];
    $nbIActifs = $genderDistribution['nbIActifs'];
    $totalActifs = $nbHActifs + $nbFActifs + $nbIActifs;

    $nbHJuniors = $genderDistribution['nbHJuniors'];
    $nbFJuniors = $genderDistribution['nbFJuniors'];
    $nbIJuniors = $genderDistribution['nbIJuniors'];
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
            <th><img src="/admin/images/male.png" alt="hommes"/></th>
            <th><img src="/admin/images/female.png" alt="femmes"/></th>
            <th><img src="/admin/images/question.png" alt="indéfini"/></th>
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
