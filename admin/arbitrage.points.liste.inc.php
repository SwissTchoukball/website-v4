<?php
if (isset($_POST['pointCountingPeriodStartYear']) and is_numeric($_POST['pointCountingPeriodStartYear'])) {
    $pointCountingPeriodStartYear = $_POST['pointCountingPeriodStartYear'];
} else {
    if (date('m') >= 9) {
        $pointCountingPeriodStartYear = date('Y') - 1;
    } else {
        $pointCountingPeriodStartYear = date('Y') - 2;
    }
}
$startCountingPointsOnEvenYears = $pointCountingPeriodStartYear % 2 + 1;

$refereeLevels = array();
$queryRefereeLevels = "SELECT idArbitre AS id, descriptionArbitre" . $_SESSION['__langue__'] . " AS niveau, pointsPourGarderNiveau FROM DBDArbitre";
if ($dataRefereeLevels = mysql_query($queryRefereeLevels)) {
    while ($refereeLevel = mysql_fetch_assoc($dataRefereeLevels)) {
        $refereeLevels[$refereeLevel['id']] = $refereeLevel;
    }
} else {
    die(printErrorMessage("Problème lors de la récupération des niveaux d'arbitre."));
}
echo '<form name="pointCountingPeriodSelector" action="" method="post">';
echo '<h2>Volée';
echo ' <select name="pointCountingPeriodStartYear" onChange="pointCountingPeriodSelector.submit();">';
$selectorStart = date('Y') - 3;
$selectorEnd = date('Y');
for ($s = $selectorStart; $s <= $selectorEnd; $s++) {
    $sEnd = $s + 2;
    if ($s == $pointCountingPeriodStartYear) {
        $selected = ' selected="selected"';
    } else {
        $selected = '';
    }
    echo '<option value="' . $s . '"' . $selected . '>';
    echo $s . ' - ' . $sEnd;
    echo '</option>';
}
echo '</select>';
echo '</h2>';
echo '</form>';

echo '<table id="refereePoints" class="adminTable alternateBackground">';
echo '<tr>';
echo '<th>' . ucfirst(VAR_LANG_ARBITRE) . '</th>';
echo '<th>' . ucfirst(VAR_LANG_NIVEAU) . '</th>';
$pointsTypes = array();
// If we change the query ORDER, we have to change it in the second query as well
$queryPointsTypes = "SELECT id, typesPoints" . $_SESSION['__langue__'] . " AS nom FROM Arbitres_Types_Points ORDER BY id";
if ($dataPointsTypes = mysql_query($queryPointsTypes)) {
    while ($pointType = mysql_fetch_assoc($dataPointsTypes)) {
        array_push($pointsTypes, $pointType);
    }
} else {
    die(printErrorMessage("Problème lors de la récupération des types de points."));
}
$totalPointsTypes = array();
foreach ($pointsTypes as $pointType) {
    echo '<th>' . $pointType['nom'] . '</th>';
    $totalPointsTypes[$pointType['id']] = 0;
}
echo '<th>Total</th>';
echo '</tr>';

$queryRefereesPoints = "SELECT ap.idArbitre AS personId, p.nom, p.prenom, a.levelId AS niveauArbitre, atp.id AS idTypesPoints, atp.typesPointsFr,
						(SELECT SUM(apo.points) AS points
						FROM Arbitres_Points apo
						WHERE apo.idArbitre=ap.idArbitre
						AND (apo.idSaison = " . $pointCountingPeriodStartYear . " OR apo.idSaison = " . ($pointCountingPeriodStartYear + 1) . ")
						AND apo.idTypePoints = atp.id) AS points
						FROM Arbitres_Types_Points atp, Arbitres_Points ap
						LEFT OUTER JOIN DBDPersonne p ON p.idDbdPersonne = ap.idArbitre
						LEFT OUTER JOIN Arbitres a ON a.personId = p.idDbdPersonne
						WHERE (ap.idSaison = " . $pointCountingPeriodStartYear . " OR ap.idSaison = " . ($pointCountingPeriodStartYear + 1) . ")
						AND a.startCountingPointsOnEvenYears = " . $startCountingPointsOnEvenYears . "
						GROUP BY p.nom, p.prenom, atp.id
						ORDER BY p.nom, p.prenom, atp.id";

if ($refereesPoints = mysql_query($queryRefereesPoints)) {
    $grandTotalPoints = 0;
    if (mysql_num_rows($refereesPoints) == 0) {
        echo '<tr><td colspan="' . (sizeof($pointsTypes) + 3) . '">Aucun arbitre n\'a obtenu de points dans cette volée</td></tr>';
    } else {
        $currentRefereeID = 0;
        $currentRefereeLevelID = 0;
        $totalPoints = 0;
        $totalPointsClass = 0;
        while ($referee = mysql_fetch_assoc($refereesPoints)) {
            if ($referee['personId'] != $currentRefereeID) {
                if ($currentRefereeID != 0) { // Not first line
                    // Computation of the total number of points of the referee in the previous loop.
                    $grandTotalPoints += $totalPoints;
                    if ($totalPoints < $refereeLevels[$currentRefereeLevelID]['pointsPourGarderNiveau']) {
                        $totalPointsClass = ' class="notOK"';
                    } else {
                        $totalPointsClass = '';
                    }
                    echo '<td' . $totalPointsClass . '>' . $totalPoints . '</td>';
                    echo '</tr>';
                }
                echo '<tr>';
                echo '<td>' . $referee['prenom'] . ' ' . $referee['nom'] . '</td>';
                $refereeLevel = $referee['niveauArbitre'] - 1;
                echo '<td>' . chif_rome($refereeLevel) . '</td>';
                $currentRefereeID = $referee['personId'];
                $currentRefereeLevelID = $referee['niveauArbitre'];
                $totalPoints = 0;
            }
            if ($referee['points'] == '') {
                $points = 0;
            } else {
                $points = $referee['points'];
            }
            echo '<td>' . $points . '</td>';
            $totalPoints += $points;
            $totalPointsTypes[$referee['idTypesPoints']] += $points;
        }
        $grandTotalPoints += $totalPoints;
        echo '<td' . $totalPointsClass . '>' . $totalPoints . '</td>';
        echo '</tr>';
    }

    echo '<tr>';
    echo '<th>Totaux</th>';
    echo '<th></th>';
    foreach ($pointsTypes as $pointType) {
        echo '<th>' . $totalPointsTypes[$pointType['id']] . '</th>';
    }
    echo '<th>' . $grandTotalPoints . '</th>';
    echo '</tr>';
} else {
    printErrorMessage("Problème lors de la récupération de la liste des arbitres");
}
echo '</table>';

echo '<h3>Arbitres n\'ayant obtenus aucun points</h3>';
echo '<p class="noPointsReferees">';
$queryNoPointsReferees =
    "SELECT a.personId, p.nom, p.prenom
    FROM DBDPersonne p
	LEFT OUTER JOIN Arbitres a ON a.personId = p.idDbdPersonne
	WHERE a.personId NOT IN (SELECT ap.idArbitre
	  					     FROM Arbitres_Points ap
  						     WHERE (ap.idSaison=" . $pointCountingPeriodStartYear . " OR ap.idSaison=" . ($pointCountingPeriodStartYear + 1) . "))
	AND a.startCountingPointsOnEvenYears = " . $startCountingPointsOnEvenYears . "
	ORDER BY p.nom, p.prenom";
if ($dataNoPointsReferees = mysql_query($queryNoPointsReferees)) {
    if (mysql_num_rows($dataNoPointsReferees) == 0) {
        echo "Aucun arbitre n'a pas obtenu de points dans cette volée.";
    } else {
        while ($referee = mysql_fetch_assoc($dataNoPointsReferees)) {
            echo '<span>' . $referee['prenom'] . ' ' . $referee['nom'] . '</span>';
        }
    }
} else {
    die(printErrorMessage("Problème lors de la récupération des arbitres sans points."));
}
echo '</p>';

echo '<h3>Nombre de points à obtenir sur deux saisons pour conserver son titre d\'arbitre</h3>';
echo '<ul>';
foreach ($refereeLevels as $refereeLevel) {
    if ($refereeLevel['id'] > 1) {
        echo '<li>' . $refereeLevel['niveau'] . ' : ' . $refereeLevel['pointsPourGarderNiveau'] . ' ' . VAR_LANG_POINTS . '</li>';
    }
}
echo '</ul>';
?>
